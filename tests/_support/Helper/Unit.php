<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use App\Logging\Slack;
use Codeception\TestCase;

class Unit extends \Codeception\Module
{
    /**
     * @throws \JsonException
     */
    public function _failed(TestCase $test, $fail): void
    {
        Slack::sendMessage(
            "Tester-bot",
            "testing-channel",
            "Test failed!",
            "File - " .$test->getMetadata()->getFilename().
            "\n Test - *" .$test->getMetadata()->getName()."*".
            "\n Error - " .$fail->getMessage(),
            null,
            true
        );
    }
}
