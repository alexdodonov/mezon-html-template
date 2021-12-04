<?php
namespace Mezon\HtmlTemplate\Tests;

use Mezon\HtmlTemplate\HtmlTemplate;

class TestTemplate extends HtmlTemplate
{

    public function getSuccessMessageContentTest(string $successMessage): string
    {
        return parent::getSuccessMessageContent($successMessage);
    }

    public function getErrorMessageContentTest(string $errorMessage): string
    {
        return parent::getErrorMessageContent($errorMessage);
    }
}
