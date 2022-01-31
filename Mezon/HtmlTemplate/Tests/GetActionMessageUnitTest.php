<?php
namespace Mezon\HtmlTemplate\Tests;

use Mezon\HtmlTemplate\HtmlTemplate;
use PHPUnit\Framework\TestCase;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class GetActionMessageUnitTest extends TestCase
{

    /**
     * Testing exception while getting action message
     */
    public function testGetActionMessageException(): void
    {
        // assertions
        $this->expectException(\Exception::class);
        $this->expectExceptionCode(- 1);
        $this->expectExceptionMessage('The message with locator "unexisting" was not found');

        // setup
        $template = new HtmlTemplate(HtmlTemplateBaseTest::PATH_TO_TEST_DATA, 'index', [
            'main'
        ]);

        // test body
        $template->setSuccessMessage('unexisting');
    }

    /**
     * Testing method getActionMessage when file with strings was not found
     */
    public function testFileWithMessagesWasNotFound(): void
    {
        // setup
        $template = new HtmlTemplate(HtmlTemplateBaseTest::PATH_TO_TEST_DATA . '/Res', 'index2', [
            'main'
        ]);

        // test body
        $template->setErrorMessage('message-from-unexisting-file');

        // assertions
        $this->assertEquals('', $template->getPageVar('action-message'));
    }

    /**
     * Testing method method getActionMessage when multyple files with strings were found
     */
    public function testMultypleFileWithMessages(): void
    {
        // setup
        $template = new HtmlTemplate(
            [
                HtmlTemplateBaseTest::PATH_TO_TEST_DATA,
                HtmlTemplateBaseTest::PATH_TO_TEST_DATA . '/Res2'
            ],
            'index',
            [
                'main'
            ]);

        // test body
        $template->setErrorMessage('other');

        // assertions
        $this->assertEquals('other', $template->getPageVar('action-message'));
    }
}
