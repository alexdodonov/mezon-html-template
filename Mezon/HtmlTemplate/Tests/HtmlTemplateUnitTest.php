<?php
namespace Mezon\HtmlTemplate\Tests;

use Mezon\HtmlTemplate\HtmlTemplate;
use Mezon\HtmlTemplate\TemplateResources;

class HtmlTemplateUnitTest extends \PHPUnit\Framework\TestCase
{

    const PATH_TO_TEST_DATA = __DIR__ . '/TestData/';

    /**
     * Data provider for constructor tests
     *
     * @return array
     */
    public function constructorDataProvider(): array
    {
        return [
            [
                HtmlTemplateUnitTest::PATH_TO_TEST_DATA,
                'index'
            ],
            [
                HtmlTemplateUnitTest::PATH_TO_TEST_DATA . 'Res/',
                'index2'
            ],
            [
                [
                    HtmlTemplateUnitTest::PATH_TO_TEST_DATA,
                    HtmlTemplateUnitTest::PATH_TO_TEST_DATA . 'Res/'
                ],
                'index2'
            ]
        ];
    }

    /**
     * Testing construction with default path
     *
     * @param string|array $path
     *            paths to content
     * @param string $template
     *            template's name
     * @dataProvider constructorDataProvider
     */
    public function testConstructor($path, string $template)
    {
        // setup and test body
        $resources = new TemplateResources();
        $resources->addCssFile('./some.css');
        $resources->addJsFile('./some.js');

        $template = new HtmlTemplate($path, $template, [
            'main'
        ]);
        $template->setResources($resources);

        $content = $template->compile();

        // assertions
        $this->assertStringContainsString('<body>', $content, 'Layout was not setup');
        $this->assertStringContainsString('<section>', $content, 'Block was not setup');
    }

    /**
     * Data provider for constructor tests
     *
     * @return array
     */
    public function invalidConstructorDataProvider(): array
    {
        return [
            [
                __DIR__,
                'index3'
            ],
            [
                false,
                'index4'
            ]
        ];
    }

    /**
     * Testing invalid construction
     *
     * @param string|array $path
     *            paths to content
     * @param string $template
     *            template's name
     * @dataProvider invalidConstructorDataProvider
     */
    public function testInvalidConstructor($path, string $template)
    {
        $this->expectException(\Exception::class);

        // setup and test body
        $template = new HtmlTemplate($path, $template, [
            'main'
        ]);

        // debug if the exception was not thrown
        var_dump($template);
    }

    /**
     * Testing that all unused place holders will be removed
     */
    public function testCompile()
    {
        // setup
        $template = new HtmlTemplate(HtmlTemplateUnitTest::PATH_TO_TEST_DATA, 'index', [
            'main'
        ]);
        $_SERVER['HTTP_HOST'] = 'host';

        // test body
        $result = $template->compile();

        // assertions
        $this->assertStringNotContainsStringIgnoringCase('{title}', $result);
    }

    /**
     * Testing unexisting block
     */
    public function testGetUnexistingBlock()
    {
        // setup and test body
        $template = new HtmlTemplate(HtmlTemplateUnitTest::PATH_TO_TEST_DATA, 'index', [
            'main'
        ]);

        $this->expectException(\Exception::class);

        // test body
        $template->getBlock('unexisting');
    }

    /**
     * Test existing var fetch
     */
    public function testGetExistingVar(): void
    {
        // setup
        $template = new HtmlTemplate(HtmlTemplateUnitTest::PATH_TO_TEST_DATA);
        $template->setPageVar('existing-var', 'existing value');

        // test body and assertions
        $this->assertEquals('existing value', $template->getPageVar('existing-var'));
    }

    /**
     * Test unexisting var fetch
     */
    public function testGetUnExistingVar(): void
    {
        // setup
        $template = new HtmlTemplate(HtmlTemplateUnitTest::PATH_TO_TEST_DATA);

        // assertions
        $this->expectException(\Exception::class);

        // test body
        $template->getPageVar('unexisting-var');
    }

    /**
     * Testing setPageVars method
     */
    public function testSetPageVars(): void
    {
        // setup
        $template = new HtmlTemplate(HtmlTemplateUnitTest::PATH_TO_TEST_DATA);

        // test body
        $template->setPageVars([
            'title' => 'stitle',
            'resources' => 'sresources',
            'main' => 'smain'
        ]);

        // assertions
        $this->assertEquals('stitle', $template->getPageVar('title'));
        $this->assertEquals('sresources', $template->getPageVar('resources'));
        $this->assertEquals('smain', $template->getPageVar('main'));
    }

    /**
     * Testing method setPageVarFromFile
     */
    public function testSetPageVarFromFile(): void
    {
        // setup
        $template = new HtmlTemplate(HtmlTemplateUnitTest::PATH_TO_TEST_DATA);

        // test body
        $template->setPageVarFromFile('title', HtmlTemplateUnitTest::PATH_TO_TEST_DATA . '/Res/var.txt');

        // assertions
        $this->assertEquals('some var from file', $template->getPageVar('title'));
    }

    /**
     * Testing method setPageVarFromBlock
     */
    public function testSetPageVarFromBlock(): void
    {
        // setup
        $template = new HtmlTemplate(HtmlTemplateUnitTest::PATH_TO_TEST_DATA);

        // test body
        $template->setPageVarFromBlock('block-var', 'block3');

        // assertions
        $this->assertEquals('block3', $template->getPageVar('block-var'));
    }

    /**
     * Testing methods addPaths, setPaths, getPaths
     */
    public function testPathsManipulations(): void
    {
        // setup
        $template = new HtmlTemplate(HtmlTemplateUnitTest::PATH_TO_TEST_DATA);

        // assertions
        $this->assertContains(HtmlTemplateUnitTest::PATH_TO_TEST_DATA, $template->getPaths());

        // test body
        $template->addPaths([
            'some-path'
        ]);

        // assertions
        $this->assertContains(HtmlTemplateUnitTest::PATH_TO_TEST_DATA, $template->getPaths());
        $this->assertContains('some-path', $template->getPaths());

        // test body
        $template->setPaths([
            'some-path'
        ]);

        // asssertions
        $this->assertNotContains(HtmlTemplateUnitTest::PATH_TO_TEST_DATA, $template->getPaths());
        $this->assertContains('some-path', $template->getPaths());
    }

    /**
     * Testing method
     */
    public function testBlockExists(): void
    {
        // setup
        $template = new HtmlTemplate(HtmlTemplateUnitTest::PATH_TO_TEST_DATA);

        // test body and assertions
        $this->assertTrue($template->blockExists('block1'));
        $this->assertTrue($template->blockExists('block2'));
        $this->assertFalse($template->blockExists('block4'));
    }
}
