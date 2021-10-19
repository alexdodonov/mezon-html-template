<?php
use PHPUnit\Framework\TestCase;
use Mezon\HtmlTemplate\TemplateResources;

/**
 * 
 * @psalm-suppress PropertyNotSetInConstructor
 */
class TemplateResourcesUnitTest extends TestCase
{

    /**
     * Testing additing CSS file
     */
    public function testAdditingSingleCSSFile(): void
    {
        $templateResources = new TemplateResources();

        $this->assertEquals(0, count($templateResources->getCssFiles()));

        $templateResources->addCssFile('./Res/test.css');

        $this->assertEquals(1, count($templateResources->getCssFiles()));

        $templateResources->clear();
    }

    /**
     * Testing additing CSS files
     */
    public function testAdditingMultypleCSSFiles(): void
    {
        $templateResources = new TemplateResources();

        $this->assertEquals(0, count($templateResources->getCssFiles()));

        $templateResources->addCssFiles([
            './res/test.css',
            './res/test2.css'
        ]);

        $this->assertEquals(2, count($templateResources->getCssFiles()));

        $templateResources->clear();
    }

    /**
     * Testing additing CSS files
     */
    public function testDoublesCSSExcluding(): void
    {
        $templateResources = new TemplateResources();

        $this->assertEquals(0, count($templateResources->getCssFiles()));

        $templateResources->addCssFiles([
            './res/test.css',
            './res/test.css'
        ]);

        $this->assertEquals(1, count($templateResources->getCssFiles()));

        $templateResources->addCssFile('./res/test.css');

        $this->assertEquals(1, count($templateResources->getCssFiles()));

        $templateResources->clear();
    }

    /**
     * Testing additing JS file
     */
    public function testAdditingSingleJSFile(): void
    {
        $templateResources = new TemplateResources();

        $this->assertEquals(0, count($templateResources->getJsFiles()));

        $templateResources->addJsFile('./include/js/test.js');

        $this->assertEquals(1, count($templateResources->getJsFiles()));

        $templateResources->clear();
    }

    /**
     * Testing additing JS files
     */
    public function testAdditingMultypleJSFiles(): void
    {
        $templateResources = new TemplateResources();

        $this->assertEquals(0, count($templateResources->getJsFiles()));

        $templateResources->addJsFiles([
            './include/js/test.js',
            './include/js//test2.js'
        ]);

        $this->assertEquals(2, count($templateResources->getJsFiles()));

        $templateResources->clear();
    }

    /**
     * Testing additing JS files
     */
    public function testDoublesJSExcluding(): void
    {
        $templateResources = new TemplateResources();

        $this->assertEquals(0, count($templateResources->getJsFiles()));

        $templateResources->addJsFiles([
            './include/js/test.js',
            './include/js/test.js'
        ]);

        $this->assertEquals(1, count($templateResources->getJsFiles()));

        $templateResources->addJsFile('./include/js/test.js');

        $this->assertEquals(1, count($templateResources->getJsFiles()));

        $templateResources->clear();
    }
}
