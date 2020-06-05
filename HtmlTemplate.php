<?php
namespace Mezon\HtmlTemplate;

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
     */
    private $template = false;

    /**
     * Loaded resources
     */
    private $resources = false;

    /**
     * Path to the template folder
     *
     * @var array
     */
    private $paths = false;

    /**
     * Page blocks
     *
     * @var array
     */
    private $blocks = [];

    /**
     * Page variables
     *
     * @var array
     */
    private $pageVars = [];

    /**
     * Template сonstructor
     *
     * @param string|array $path
     *            Path to template
     * @param string $template
     *            Page layout
     * @param array $blocks
     *            Page blocks
     */
    public function __construct($path, string $template = 'index', array $blocks = [])
    {
        if (is_string($path)) {
            $this->paths = [
                $path
            ];
        } elseif (is_array($path)) {
            $this->paths = $path;
        } else {
            throw (new \Exception('Invalid type for $path parameter'));
        }

        $this->resetLayout($template);

        $this->resources = new \Mezon\HtmlTemplate\TemplateResources();

        $this->blocks = [];

        foreach ($blocks as $blockName) {
            $this->addBlock($blockName);
        }

        // output all blocks in one place
        // but each block can be inserted in {$blockName} places
        $this->setPageVar('content-blocks', implode('', $this->blocks));
    }

    /**
     * Method adds paths to the setup ones
     *
     * @param array $paths
     *            paths to directories with the template's static content
     */
    public function addPaths(array $paths): void
    {
        $this->paths = array_merge($paths, $this->paths);
    }

    /**
     * Resetting paths
     *
     * @param array $paths
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
     *            Variable name
     * @param mixed $value
     *            Variable value
     */
    public function setPageVar(string $var, $value): void
    {
        $this->pageVars[$var] = $value;
    }

    /**
     * Method sets multiple variables
     *
     * @param array $vars
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
        if (isset($this->pageVars[$var]) === false) {
            throw (new \Exception('Template variable ' . $var . ' was not set'));
        }

        return $this->pageVars[$var];
    }

    /**
     * Setting page variables from file's content
     *
     * @param string $var
     *            Variable name
     * @param mixed $path
     *            Path to file
     */
    public function setPageVarFromFile(string $var, string $path): void
    {
        $this->setPageVar($var, file_get_contents($path));
    }

    /**
     * Compiling the page with it's variables
     *
     * @param string $content
     *            Compiling content
     */
    public function compilePageVars(string &$content): void
    {
        foreach ($this->pageVars as $key => $value) {
            if (is_array($value) === false && is_object($value) === false) {
                // only scalars can be substituted in this way
                $content = str_replace('{' . $key . '}', $value, $content);
            }
        }

        $content = \Mezon\TemplateEngine\TemplateEngine::unwrapBlocks($content, $this->pageVars);

        $content = \Mezon\TemplateEngine\TemplateEngine::compileSwitch($content);
    }

    /**
     * Checking if the file exists
     *
     * @param string $fileSubPath
     *            file sub path
     * @return bool true if the file exists, false otherwise
     */
    protected function fileExists(string $fileSubPath): bool
    {
        foreach ($this->paths as $path) {
            print(trim($path, '/\\') . '/' . trim($fileSubPath, '/\\')."\r\n");
            if (file_exists(trim($path, '/\\') . '/' . trim($fileSubPath, '/\\'))) {
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
            $finalPath = trim($path, '/\\') . '/' . trim($fileSubPath, '/\\');
            if (file_exists($finalPath)) {
                return file_get_contents($finalPath);
            }
        }
// @codeCoverageIgnoreStart
        return '';
    }// @codeCoverageIgnoreEnd

    /**
     * Method resets layout
     *
     * @param string $template
     *            Template name
     */
    public function resetLayout(string $template): void
    {
        $template .= '.html';

        if ($this->fileExists($template)) {
            $this->template = $this->fileGetContents($template);
        } elseif ($this->fileExists('res/templates/' . $template)) {
            $this->template = $this->fileGetContents('res/templates/' . $template);
        } else {
            throw (new \Exception(
                'Template file ' . $template . ' on the paths ' . implode(', ', $this->paths) . ' was not found',
                - 1));
        }
    }

    /**
     * Method returns compiled page resources
     *
     * @return string Compiled resources includers
     */
    private function _getResources(): string
    {
        $content = '';

        $cSSFiles = $this->resources->getCssFiles();
        foreach ($cSSFiles as $cSSFile) {
            $content .= '
        <link href="' . $cSSFile . '" rel="stylesheet" type="text/css">';
        }

        $jSFiles = $this->resources->getJsFiles();
        foreach ($jSFiles as $jSFile) {
            $content .= '
        <script src="' . $jSFile . '"></script>';
        }

        return $content;
    }

    /**
     * Compile template
     *
     * @return string Compiled template
     */
    public function compile(): string
    {
        $this->setPageVar('resources', $this->_getResources());
        $this->setPageVar('mezon-http-path', \Mezon\Conf\Conf::getConfigValue('@mezon-http-path'));
        $this->setPageVar('service-http-path', \Mezon\Conf\Conf::getConfigValue('@service-http-path'));
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
     * Method returns block's content
     *
     * @param string $blockName
     * @return string block's content
     */
    protected function readBlock(string $blockName): string
    {
        if ($this->fileExists('res/blocks/' . $blockName . '.tpl')) {
            $blockContent = $this->fileGetContents('res/blocks/' . $blockName . '.tpl');
        } elseif ($this->fileExists('blocks/' . $blockName . '.tpl')) {
            $blockContent = $this->fileGetContents('blocks/' . $blockName . '.tpl');
        } else {
            throw (new \Exception('Block ' . $blockName . ' was not found', - 1));
        }

        return $blockContent;
    }

    /**
     * Method adds block to page
     *
     * @param string $blockName
     *            Name of the additing block
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
}
