<?php
namespace Mezon\HtmlTemplate\Tests;

use PHPUnit\Framework\TestCase;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class VisibilityUnitTest extends TestCase
{

    /**
     * Testing method's visibility
     */
    public function testVisibilityOfMethods(): void
    {
        // setup
        $template = new TestTemplate(HtmlTemplateBaseTest::PATH_TO_TEST_DATA);

        // test body and assertions
        $this->assertEquals('error-message', $template->getErrorMessageContentTest('error-message'));
        $this->assertEquals('success-message', $template->getSuccessMessageContentTest('success-message'));
    }
}
