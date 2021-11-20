<?php
namespace Mezon\HtmlTemplate\Tests;

use Mezon\HtmlTemplate\HtmlTemplate;
use Mezon\HtmlTemplate\TemplateResources;
use PHPUnit\Framework\TestCase;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class ConstructorUnitTest extends TestCase
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
     * Testing construction with default path
     *
     * @param string|array $path
     *            paths to content
     * @param string $template
     *            template's name
     * @dataProvider constructorDataProvider
     */
    public function testConstructor($path, string $template): void
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
     * Testing invalid construction
     */
    public function testInvalidConstructorFileNotFound(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionCode(- 1);
        $this->expectExceptionMessage(
            'Template file index3.html on the paths [C:\xampp\mezon-html-template\Mezon\HtmlTemplate\Tests] was not found');

        // setup and test body
        new HtmlTemplate(__DIR__, 'index3', [
            'main'
        ]);
    }

    /**
     * Testing invalid construction
     */
    public function testInvalidConstructorInvalidPaths(): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionCode(- 1);
        $this->expectExceptionMessage('Invalid type for $path parameter');

        // setup and test body
        new HtmlTemplate(false, 'index3', [
            'main'
        ]);
    }
}
