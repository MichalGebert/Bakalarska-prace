<?php
namespace Helper;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

use App\Logging\Slack;
use Codeception\Exception\ModuleException;
use Codeception\Test\Descriptor;
use Codeception\TestCase;
use Codeception\Util\Debug;

class Acceptance extends \Codeception\Module
{

    private $webDriver = null;
    private $webDriverModule = null;

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

    /**
     * Event hook before a test starts.
     *
     * @param \Codeception\TestCase $test
     *
     * @throws \Exception
     */
    public function _before(\Codeception\TestCase $test): void
    {
        if (!$this->hasModule('WebDriver') && !$this->hasModule('Selenium2') && !$this->hasModule('PhpBrowser')) {
            throw new \Exception('PageWait uses the WebDriver or PhpBrowser. Please be sure that this module is activated.');
        }

        // Use WebDriver
        if ($this->hasModule('WebDriver')) {
            $this->webDriverModule = $this->getModule('WebDriver');
            $this->webDriver = $this->webDriverModule->webDriver;
        }
    }

    /**
     * @param int $timeout
     * @throws \JsonException
     */
    public function waitAjaxLoad(int $timeout = 10): void
    {
        if ($this->hasModule('WebDriver')) {
            $this->webDriverModule->waitForJS('return !!window.jQuery && window.jQuery.active == 0;', $timeout);
          $this->webDriverModule->wait(1);
          $this->dontSeeJsError();
        }
    }

    /**
     * @param int $timeout
     * @throws \JsonException
     */
    public function waitPageLoad(int $timeout = 10): void
    {
        if ($this->hasModule('WebDriver')) {
            $this->webDriverModule->waitForJs('return document.readyState == "complete"', $timeout);
            $this->waitAjaxLoad($timeout);
            $this->dontSeeJsError();
        }
    }

    /**
     * @param $link
     * @param int $timeout
     */
    public function amOnPage($link, int $timeout = 10): void
    {
        if ($this->hasModule('WebDriver')) {
            $this->webDriverModule->amOnPage($link);
            $this->waitPageLoad($timeout);
        } else {
            $this->getModule('PhpBrowser')->amOnPage($link);
        }
    }

    /**
     * @param $identifier
     * @param $elementID
     * @param $excludeElements
     * @param $element
     * @return void
     * @throws ModuleException
     * @throws \JsonException
     */
    public function dontSeeVisualChanges($identifier, $elementID = null, $excludeElements = null, $element = false): void
    {
        if ($this->hasModule('WebDriver')) {
            if ($element !== false) {
                $this->webDriverModule->moveMouseOver($element);
            }
            $this->getModule('VisualCeption')->dontSeeVisualChanges($identifier, $elementID, $excludeElements);
            $this->dontSeeJsError();
        }
    }


    /**
     * @throws ModuleException
     * @throws \JsonException
     */
    public function dontSeeJsError(): void
    {
        if ($this->hasModule('WebDriver')) {
            $logs = $this->webDriver->manage()->getLog('browser');
            foreach ($logs as $log) {
                if ($log['level'] === 'SEVERE') {
                    throw new ModuleException($this, 'Some error in JavaScript: ' . json_encode($log, JSON_THROW_ON_ERROR));
                }
            }
        }
    }
}
