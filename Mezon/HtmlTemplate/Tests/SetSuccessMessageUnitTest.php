<?php
namespace Mezon\HtmlTemplate\Tests;

use Mezon\HtmlTemplate\HtmlTemplate;
use PHPUnit\Framework\TestCase;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class SetSuccessMessageUnitTest extends TestCase
{

    /**
     * Testing method setSuccessMessage
     */
    public function testSetSuccessMessage(): void
    {
        // setup
        $template = new HtmlTemplate(HtmlTemplateBaseTest::PATH_TO_TEST_DATA, 'index', [
            'main'
        ]);

        // test body
        $template->setSuccessMessage('success');

        // assertions
        $this->assertEquals('success', $template->getPageVar('action-message'));
    }
}
