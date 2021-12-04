<?php
namespace Mezon\HtmlTemplate\Tests;

use PHPUnit\Framework\TestCase;
use Mezon\HtmlTemplate\HtmlTemplate;

/**
 *
 * @psalm-suppress PropertyNotSetInConstructor
 */
class GetSuccessMessageCodeUnitTest extends TestCase
{

    /**
     * Testing data provider
     *
     * @return array testing data
     */
    public function getSuccessMessageCodeDataProvider(): array
    {
        return [
            // #0, the first case - success message will be returned
            [
                function (): void {
                    // setup method
                    $_GET['success-message'] = 'success-message';
                    $_GET['action-message'] = 'action-message';
                },
                'success-message'
            ],
            // #1, the second case - action message will be returned
            [
                function (): void {
                    // setup method
                    unset($_GET['success-message']);
                    $_GET['action-message'] = 'action-message';
                },
                'action-message'
            ],
            // #2, the third case - empty string will be returned
            [
                function (): void {
                    // setup method
                    unset($_GET['success-message']);
                    unset($_GET['action-message']);
                },
                ''
            ],
            // #3, the forth case - invalid values to check type casts
            [
                function (): void {
                    // setup method
                    $_GET['success-message'] = 1;
                    unset($_GET['action-message']);
                },
                '1'
            ],
            // #4, the fifth case - invalid values to check type casts
            [
                function (): void {
                    // setup method
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
     * @dataProvider getSuccessMessageCodeDataProvider
     */
    public function testGetSuccessMessageCode(callable $setup, string $expected): void
    {
        // setup
        $setup();

        // test body
        $result = HtmlTemplate::getSuccessMessageCode();

        // assertions
        $this->assertEquals($expected, $result);
    }
}
