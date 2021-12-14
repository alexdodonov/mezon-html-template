<?php
namespace Mezon\HtmlTemplate\Tests;

use Mezon\HtmlTemplate\HtmlTemplate;
use PHPUnit\Framework\TestCase;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class GetPageVarUnitTest extends TestCase
{

    /**
     * Test existing var fetch
     */
    public function testGetExistingVar(): void
    {
        // setup
        $template = new HtmlTemplate(HtmlTemplateBaseTest::PATH_TO_TEST_DATA);
        $template->setPageVar('existing-var', 'existing value');

        // test body and assertions
        $this->assertEquals('existing value', $template->getPageVar('existing-var'));
    }

    /**
     * Test unexisting var fetch
     */
    public function testGetUnexistingVar(): void
    {
        // setup
        $template = new HtmlTemplate(HtmlTemplateBaseTest::PATH_TO_TEST_DATA);

        // assertions
        $this->expectException(\Exception::class);
        $this->expectExceptionCode(- 1);
        $this->expectExceptionMessage('Template variable unexisting-var was not set');

        // test body
        $template->getPageVar('unexisting-var');
    }
}
