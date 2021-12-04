<?php
namespace Mezon\HtmlTemplate\Tests;

use Mezon\HtmlTemplate\HtmlTemplate;
use PHPUnit\Framework\TestCase;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class SetErrorMessageUnitTest extends TestCase
{

    /**
     * Testing method setErrorMessage
     */
    public function testSetErrorMessage(): void
    {
        // setup
        $template = new HtmlTemplate(HtmlTemplateBaseTest::PATH_TO_TEST_DATA, 'index', [
            'main'
        ]);

        // test body
        $template->setErrorMessage('error');

        // assertions
        $this->assertEquals('error', $template->getPageVar('action-message'));
    }
}
