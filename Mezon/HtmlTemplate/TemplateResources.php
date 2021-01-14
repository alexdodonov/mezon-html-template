<?php
namespace Mezon\HtmlTemplate;

use Mezon\Conf\Conf;

/**
 * Class TemplateResources
 *
 * @package Mezon
 * @subpackage TemplateResources
 * @author Dodonov A.A.
 * @version v.1.0 (2019/08/17)
 * @copyright Copyright (c) 2019, aeon.org
 */

/**
 * Class collects resources for page.
 *
 * Any including components can add to the page their own resources without having access to the application or template.
 */
class TemplateResources
{

    /**
     * Custom CSS files to be included
     */
    private $cssFiles = false;

    /**
     * Custom JS files to be included
     */
    private $jsFiles = false;

    /**
     * Constructor.
     */
    function __construct()
    {
        if ($this->cssFiles === false) {
            $this->cssFiles = [];
        }
        if ($this->jsFiles === false) {
            $this->jsFiles = [];
        }
    }

    /**
     * Additing single CSS file
     *
     * @param string $cssFile
     *            CSS file
     */
    function addCssFile(string $cssFile)
    {
        // additing only unique paths
        if (array_search($cssFile, $this->cssFiles) === false) {
            $this->cssFiles[] = Conf::expandString($cssFile);
        }
    }

    /**
     * Additing multyple CSS files
     *
     * @param array $cssFiles
     *            CSS files
     */
    function addCssFiles(array $cssFiles)
    {
        foreach ($cssFiles as $cssFile) {
            $this->addCssFile($cssFile);
        }
    }

    /**
     * Method returning added CSS files
     */
    function getCssFiles()
    {
        return $this->cssFiles;
    }

    /**
     * Additing single CSS file
     *
     * @param string $jsFile
     *            JS file
     */
    function addJsFile($jsFile)
    {
        // additing only unique paths
        if (array_search($jsFile, $this->jsFiles) === false) {
            $this->jsFiles[] = Conf::expandString($jsFile);
        }
    }

    /**
     * Additing multyple CSS files
     *
     * @param array $jsFiles
     *            JS files
     */
    function addJsFiles(array $jsFiles)
    {
        foreach ($jsFiles as $jsFile) {
            $this->addJsFile($jsFile);
        }
    }

    /**
     * Method returning added JS files.
     */
    function getJsFiles()
    {
        return $this->jsFiles;
    }

    /**
     * Method clears loaded resources.
     */
    function clear()
    {
        $this->cssFiles = [];

        $this->jsFiles = [];
    }

    /**
     * Method returns compiled page resources
     *
     * @return string Compiled resources includers
     */
    public function compileResources(): string
    {
        $content = '';

        foreach ($this->cssFiles as $cssFile) {
            $content .= '
        <link href="' . $cssFile . '" rel="stylesheet" type="text/css">';
        }

        foreach ($this->jsFiles as $jsFile) {
            $content .= '
        <script src="' . $jsFile . '"></script>';
        }

        return $content;
    }
}
