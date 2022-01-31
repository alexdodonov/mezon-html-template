<?php
namespace Mezon\HtmlTemplate;

use Mezon\Conf\Conf;
use Mezon\TemplateEngine\TemplateEngine;

/**
 * Class HtmlTemplate
 *
 * @package Mezon
 * @subpackage HtmlTemplate
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/07)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Template class
 *
 * @author Dodonov A.A.
 */
class HtmlTemplate
{

    /**
     * Loaded template content
     *
     * @var string
     */
    private $template = '';

    /**
     * Loaded resources
     *
     * @var ?TemplateResources
     */
    private $resources = null;

    /**
     * Path to the template folder
     *
     * @var string[]
     */
    private $paths = [];

    /**
     * Page blocks
     *
     * @var array<string, string>
     */
    private $blocks = [];

    /**
     * Page variables
     *
     * @var array<string, string|array|object|mixed>
     */
    private $pageVars = [];

    use ActionMessages;

    /**
     * Template сonstructor
     *
     * @param string|string[]|mixed $path
     *            path to template
     * @param string $template
     *            page layout
     * @param array $blocks
     *            page blocks
     */
    public function __construct($path, string $template = 'index', array $blocks = [])
    {
        if (is_string($path)) {
            $this->paths = [
                $path
            ];
        } elseif (is_array($path)) {
            /** @var string[] $path */
            $this->paths = $path;
        } else {
            throw (new \Exception('Invalid type for $path parameter', - 1));
        }

        $this->resetLayout($template);

        $this->blocks = [];

        /** @var string $blockName */
        foreach ($blocks as $blockName) {
            $this->addBlock($blockName);
        }
    }

    /**
     * Method adds paths to the setup ones
     *
     * @param string[] $paths
     *            paths to directories with the template's static content
     */
    public function addPaths(array $paths): void
    {
        $this->paths = array_merge($paths, $this->paths);
    }

    /**
     * Resetting paths
     *
     * @param string[] $paths
     */
    public function setPaths(array $paths): void
    {
        $this->paths = $paths;
    }

    /**
     * Method returns all setup puths of the template
     *
     * @return array
     */
    public function getPaths(): array
    {
        return $this->paths;
    }

    /**
     * Setting page variables
     *
     * @param string $var
     *            variable name
     * @param mixed $value
     *            variable value
     */
    public function setPageVar(string $var, $value): void
    {
        $this->pageVars[$var] = $value;
    }

    /**
     * Method sets multiple variables
     *
     * @param
     *            array<string, mixed> $vars variables
     * @psalm-suppress MixedArgumentTypeCoercion, MixedAssignment
     */
    public function setPageVars(array $vars): void
    {
        foreach ($vars as $var => $value) {
            $this->setPageVar($var, $value);
        }
    }

    /**
     * Getting page var
     *
     * @param string $var
     *            variable name
     * @return mixed variable value, or exception if the variable was not found
     */
    public function getPageVar(string $var)
    {
        if (! isset($this->pageVars[$var])) {
            throw (new \Exception('Template variable ' . $var . ' was not set', - 1));
        }

        return $this->pageVars[$var];
    }

    /**
     * Checking that var exists
     *
     * @param string $var
     *            page var name
     * @return bool true if the page var exists, false otherwise
     */
    public function pageVarExists(string $var): bool
    {
        return isset($this->pageVars[$var]);
    }

    /**
     * Setting page variables from file's content
     *
     * @param string $var
     *            variable name
     * @param string $path
     *            path to file
     */
    public function setPageVarFromFile(string $var, string $path): void
    {
        $this->setPageVar($var, file_get_contents($path));
    }

    /**
     * Setting page variables from block
     *
     * @param string $var
     *            variable name
     * @param string $blockName
     *            block name
     */
    public function setPageVarFromBlock(string $var, string $blockName): void
    {
        $this->setPageVar($var, $this->getBlock($blockName));
    }

    /**
     * Compiling the page with it's variables
     *
     * @param string $content
     *            compiling content
     */
    public function compilePageVars(string &$content): void
    {
        $prevVarsHash = '';

        do {
            /** @var string|object|array $value */
            foreach ($this->pageVars as $key => $value) {
                if (! is_array($value) && ! is_object($value)) {
                    // only scalars can be substituted in this way
                    $content = str_replace('{' . $key . '}', $value, $content);
                }
            }

            // trying to substitute recursive
            $matches = [];
            preg_match('/\{[a-zA-Z0-9\-]{1,1000}\}/m', $content, $matches);
            if ($prevVarsHash !== md5(implode('', $matches))) {
                $prevVarsHash = md5(implode('', $matches));
            } else {
                break;
            }
        } while (true);

        $content = TemplateEngine::unwrapBlocks($content, $this->pageVars);

        $content = TemplateEngine::compileSwitch($content);
    }

    /**
     * Checking if the file exists
     *
     * @param string $fileSubPath
     *            file sub path
     * @return bool true if the file exists, false otherwise
     */
    public function fileExists(string $fileSubPath): bool
    {
        foreach ($this->paths as $path) {
            if (file_exists(rtrim($path, '/\\') . '/' . trim($fileSubPath, '/\\'))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Getting content of the file
     *
     * @param string $fileSubPath
     *            file sub path
     * @return string file content
     */
    protected function fileGetContents(string $fileSubPath): string
    {
        foreach ($this->paths as $path) {
            $finalPath = rtrim($path, '/\\') . '/' . trim($fileSubPath, '/\\');
            if (file_exists($finalPath)) {
                return file_get_contents($finalPath);
            }
        }
        // @codeCoverageIgnoreStart
        return '';
    }

    // @codeCoverageIgnoreEnd

    /**
     * Method resets layout
     *
     * @param string $template
     *            template name
     */
    public function resetLayout(string $template): void
    {
        $template .= '.html';

        if ($this->fileExists($template)) {
            $this->template = $this->fileGetContents($template);
        } elseif ($this->fileExists('Res/Templates/' . $template)) {
            $this->template = $this->fileGetContents('Res/Templates/' . $template);
        } else {
            throw (new \Exception(
                'Template file ' . $template . ' on the paths [' . implode(', ', $this->paths) . '] was not found',
                - 1));
        }
    }

    /**
     * Compile template
     *
     * @return string compiled template
     */
    public function compile(): string
    {
        $this->setPageVar('resources', $this->resources === null ? '' : $this->resources->compileResources());
        $this->setPageVar('mezon-http-path', Conf::getConfigValueAsString('@mezon-http-path'));
        $this->setPageVar('service-http-path', Conf::getConfigValueAsString('@service-http-path'));
        if (isset($_SERVER['HTTP_HOST'])) {
            $this->setPageVar('host', $_SERVER['HTTP_HOST']);
        }

        foreach ($this->blocks as $blockName => $block) {
            $this->setPageVar($blockName, $block);
        }

        $this->compilePageVars($this->template);

        $this->template = preg_replace('/\{[a-zA-z0-9\-]*\}/', '', $this->template);

        return $this->template;
    }

    /**
     * Does block exist
     *
     * @param string $blockName
     *            block name
     * @return bool does block exist?
     */
    public function blockExists(string $blockName): bool
    {
        if ($this->fileExists('Res/Blocks/' . $blockName . '.tpl')) {
            return true;
        } elseif ($this->fileExists('Blocks/' . $blockName . '.tpl')) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Method returns block's content
     *
     * @param string $blockName
     * @return string block's content
     */
    protected function readBlock(string $blockName): string
    {
        if ($this->fileExists('Res/Blocks/' . $blockName . '.tpl')) {
            $blockContent = $this->fileGetContents('Res/Blocks/' . $blockName . '.tpl');
        } elseif ($this->fileExists('Blocks/' . $blockName . '.tpl')) {
            $blockContent = $this->fileGetContents('Blocks/' . $blockName . '.tpl');
        } else {
            throw (new \Exception('Block ' . $blockName . ' was not found', - 1));
        }

        return $blockContent;
    }

    /**
     * Method adds block to page
     *
     * @param string $blockName
     *            name of the additing block
     */
    public function addBlock(string $blockName): void
    {
        $this->blocks[$blockName] = $this->readBlock($blockName);
    }

    /**
     * Method returns block's content
     *
     * @param string $blockName
     * @return string block's content
     */
    public function getBlock(string $blockName): string
    {
        return $this->readBlock($blockName);
    }

    /**
     * Method returns file data
     *
     * @param string $filePath
     *            path to file
     * @return string file content
     */
    public function getFile(string $filePath): string
    {
        foreach ($this->paths as $path) {
            $fullPath = rtrim($path, '\\/') . '/' . ltrim($filePath, '\\/');

            if (file_exists($fullPath)) {
                return file_get_contents($fullPath);
            }
        }

        throw (new \Exception('File "' . $filePath . '" was not found in paths : ' . implode($this->paths)));
    }

    /**
     * Method sets template resources
     *
     * @param TemplateResources $resources
     *            resources
     */
    public function setResources(TemplateResources $resources): void
    {
        $this->resources = $resources;
    }

    /**
     * Method reads all files $fileName found in $this->paths and joins them
     *
     * @param string $fileName
     *            file name to be fetched
     * @return array<string, string> compound JSON object as assoc array
     */
    protected function getJoinedJsonFilesData(string $fileName): array
    {
        /** @var array<string, string> $result */
        $result = [];

        foreach ($this->paths as $path) {
            $fullPath = rtrim($path, '/\\') . '/' . $fileName;

            if (file_exists($fullPath)) {
                /** @var array<string, string> $fileData */
                $fileData = json_decode(file_get_contents($fullPath), true);
                $result = array_merge($result, $fileData);
            }
        }

        return $result;
    }
}
