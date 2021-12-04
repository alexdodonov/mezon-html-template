<?php
namespace Mezon\HtmlTemplate\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\HtmlTemplate\HtmlTemplate;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class GetActionMessageCodeUnitTest extends TestCase
{

    /**
     * Testing data provider
     *
     * @return array testing data
     */
    public function getActionMessageCodeDataProvider(): array
    {
        return [
            // #0, the first case - error message will be returned
            [
                function (): void {
                    // setup method
                    $_GET['error-message'] = 'error-message';
                    $_GET['success-message'] = 'success-message';
                    $_GET['action-message'] = 'action-message';
                },
                'error-message'
            ],
            // #1, the second case - success message will be returned
            [
                function (): void {
                    // setup method
                    unset($_GET['error-message']);
                    $_GET['success-message'] = 'success-message';
                    $_GET['action-message'] = 'action-message';
                },
                'success-message'
            ],
            // #2, the third case - action message will be returned
            [
                function (): void {
                    // setup method
                    unset($_GET['error-message']);
                    unset($_GET['success-message']);
                    $_GET['action-message'] = 'action-message';
                },
                'action-message'
            ],
            // #3, the forth case - empty string will be returned
            [
                function (): void {
                    // setup method
                    unset($_GET['error-message']);
                    unset($_GET['success-message']);
                    unset($_GET['action-message']);
                },
                ''
            ],
            // #4, the fifth case - invalid values to check type casts
            [
                function (): void {
                    // setup method
                    $_GET['error-message'] = 1;
                    unset($_GET['success-message']);
                    unset($_GET['action-message']);
                },
                '1'
            ],
            // #5, the sixth case - empty string will be returned
            [
                function (): void {
                    // setup method
                    unset($_GET['error-message']);
                    $_GET['success-message'] = 1;
                    unset($_GET['action-message']);
                },
                '1'
            ],
            // #7, the seventh case - empty string will be returned
            [
                function (): void {
                    // setup method
                    unset($_GET['error-message']);
                    unset($_GET['success-message']);
                    $_GET['action-message'] = 1;
                },
                '1'
            ]
        ];
    }

    /**
     * Testing method getActionMessageCode
     *
     * @param callable $setup
     *            setup method
     * @param string $expected
     *            expected value
     * @dataProvider getActionMessageCodeDataProvider
     */
    public function testGetActionMessageCode(callable $setup, string $expected): void
    {
        // setup
        $setup();

        // test body
        $result = HtmlTemplate::getActionMessageCode();

        // assertions
        $this->assertEquals($expected, $result);
    }
}
