<?php
namespace Mezon\HtmlTemplate\Tests;

use Mezon\HtmlTemplate\HtmlTemplate;
use PHPUnit\Framework\TestCase;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class PageVarExistsUnitTest extends TestCase
{

    /**
     * Testing method pageVarExists
     */
    public function testPageVarExists(): void
    {
        // setup
        $template = new HtmlTemplate(HtmlTemplateBaseTest::PATH_TO_TEST_DATA . '/Res', 'index2', [
            'main'
        ]);
        $template->setPageVar('var', 'val');

        // test body and assertions
        $this->assertTrue($template->pageVarExists('var'));
        $this->assertFalse($template->pageVarExists('unexisting'));
    }
}
