<?php
namespace Mezon\HtmlTemplate\Tests;

use PHPUnit\Framework\TestCase;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class CompilePageVarsUnitTest extends TestCase
{

    /**
     * Testing compilePageVars method
     */
    public function testCompilePageVarsSimple(): void
    {
        // setup
        $template = new TestTemplate(HtmlTemplateBaseTest::PATH_TO_TEST_DATA);
        $template->setPageVar('var', 'val');
        $content = '{var}';

        // test body
        $template->compilePageVars($content);

        // assertions
        $this->assertEquals('val', $content);
    }
}
