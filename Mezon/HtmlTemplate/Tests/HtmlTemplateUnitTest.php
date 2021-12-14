<?php
namespace Mezon\HtmlTemplate\Tests;

use Mezon\HtmlTemplate\HtmlTemplate;
use PHPUnit\Framework\TestCase;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class HtmlTemplateUnitTest extends TestCase
{

    /**
     * Data provider for constructor tests
     *
     * @return array
     */
    public function constructorDataProvider(): array
    {
        return [
            [
                HtmlTemplateBaseTest::PATH_TO_TEST_DATA,
                'index'
            ],
            [
                HtmlTemplateBaseTest::PATH_TO_TEST_DATA . 'Res/',
                'index2'
            ],
            [
                [
                    HtmlTemplateBaseTest::PATH_TO_TEST_DATA,
                    HtmlTemplateBaseTest::PATH_TO_TEST_DATA . 'Res/'
                ],
                'index2'
            ]
        ];
    }

    /**
     * Testing that all unused place holders will be removed
     */
    public function testCompile(): void
    {
        // setup
        $template = new HtmlTemplate(HtmlTemplateBaseTest::PATH_TO_TEST_DATA, 'index', [
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
    public function testGetUnexistingBlock(): void
    {
        // setup and test body
        $template = new HtmlTemplate(HtmlTemplateBaseTest::PATH_TO_TEST_DATA, 'index', [
            'main'
        ]);

        $this->expectException(\Exception::class);

        // test body
        $template->getBlock('unexisting');
    }

    /**
     * Testing setPageVars method
     */
    public function testSetPageVars(): void
    {
        // setup
        $template = new HtmlTemplate(HtmlTemplateBaseTest::PATH_TO_TEST_DATA);

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
        $template = new HtmlTemplate(HtmlTemplateBaseTest::PATH_TO_TEST_DATA);

        // test body
        $template->setPageVarFromFile('title', HtmlTemplateBaseTest::PATH_TO_TEST_DATA . '/Res/var.txt');

        // assertions
        $this->assertEquals('some var from file', $template->getPageVar('title'));
    }

    /**
     * Testing method setPageVarFromBlock
     */
    public function testSetPageVarFromBlock(): void
    {
        // setup
        $template = new HtmlTemplate(HtmlTemplateBaseTest::PATH_TO_TEST_DATA);

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
        $template = new HtmlTemplate(HtmlTemplateBaseTest::PATH_TO_TEST_DATA);

        // assertions
        $this->assertContains(HtmlTemplateBaseTest::PATH_TO_TEST_DATA, $template->getPaths());

        // test body
        $template->addPaths([
            'some-path'
        ]);

        // assertions
        $this->assertContains(HtmlTemplateBaseTest::PATH_TO_TEST_DATA, $template->getPaths());
        $this->assertContains('some-path', $template->getPaths());

        // test body
        $template->setPaths([
            'some-path'
        ]);

        // asssertions
        $this->assertNotContains(HtmlTemplateBaseTest::PATH_TO_TEST_DATA, $template->getPaths());
        $this->assertContains('some-path', $template->getPaths());
    }

    /**
     * Testing method
     */
    public function testBlockExists(): void
    {
        // setup
        $template = new HtmlTemplate(HtmlTemplateBaseTest::PATH_TO_TEST_DATA);

        // test body and assertions
        $this->assertTrue($template->blockExists('block1'));
        $this->assertTrue($template->blockExists('block2'));
        $this->assertFalse($template->blockExists('block4'));
    }

    /**
     * Testing method getFile
     */
    public function testGetFile(): void
    {
        // setup
        $template = new HtmlTemplate(HtmlTemplateBaseTest::PATH_TO_TEST_DATA);

        // test body
        $result = $template->getFile('/Blocks/block3.tpl');

        // assertions
        $this->assertEquals('block3', $result);
    }

    /**
     * Testing exception
     */
    public function testGetFileException(): void
    {
        // assertions
        $this->expectException(\Exception::class);

        // setup
        $template = new HtmlTemplate(HtmlTemplateBaseTest::PATH_TO_TEST_DATA);

        // test body
        $template->getFile('/Blocks/unsexisting.tpl');
    }

    /**
     * Testing method
     */
    public function testRecursiveVars(): void
    {
        // setup
        $template = new HtmlTemplate(HtmlTemplateBaseTest::PATH_TO_TEST_DATA);
        $template->setPageVar('var-rec1', 'var1-was-substituted');
        $template->setPageVar('var-rec2', '{var-rec1}');
        $template->setPageVar('title', '{var-rec2}');

        // test body
        $content = $template->compile();

        // assertions
        $this->assertStringContainsString('var1-was-substituted', $content);
    }
}
