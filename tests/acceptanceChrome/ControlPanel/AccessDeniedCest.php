<?php

use Page\AcceptanceChrome\ControlPanel\DesktopHeader;
use Page\AcceptanceChrome\ControlPanel\LeftDesktopMenu;
use Page\AcceptanceChrome\Login;

class AccessDeniedCest
{
    /**
     * @throws Exception
     */
    public function _before(AcceptanceTester $I, Login $loginPage): void
    {
        $I->maximizeWindow();
        $loginPage->login('tester@goodaccess.com', 'password');
        $I->waitPageLoad();
    }

    /**
     * @throws Exception
     */
    public function _after(\AcceptanceTester $I, DesktopHeader $desktopHeader): void
    {
        $desktopHeader->logout();
    }

    /**
     * @throws Exception
     */
    public function essentialTest(\AcceptanceTester $I, LeftDesktopMenu $leftDesktopMenu): void
    {
        $leftDesktopMenu->selectTeamByName('Essential-Test');
        $leftDesktopMenu->goToCloudBranches();
        $I->waitForElementVisible('.access-denied a.goodaccess-button-orange');
        $leftDesktopMenu->goToAccessControl();
        $I->waitForElementVisible('.access-denied a.goodaccess-button-orange');
        $leftDesktopMenu->goToSettings();
    }

    public function advancedTest(\AcceptanceTester $I, LeftDesktopMenu $leftDesktopMenu): void
    {
        $leftDesktopMenu->selectTeamByName('Advanced-Test');
    }

    public function premiumTest(\AcceptanceTester $I, LeftDesktopMenu $leftDesktopMenu): void
    {
        $leftDesktopMenu->selectTeamByName('Premium-Test');
    }

    /**
     * @throws Exception
     */
    public function freemiumTest(\AcceptanceTester $I, LeftDesktopMenu $leftDesktopMenu): void
    {
        $leftDesktopMenu->selectTeamByName('Freemium-Test');
        $leftDesktopMenu->goToGateways();
        $I->waitForElementVisible('.access-denied a.goodaccess-button-orange');
        $leftDesktopMenu->goToSystems();
        $I->waitForElementVisible('.access-denied a.goodaccess-button-orange');
        $leftDesktopMenu->goToCloudBranches();
        $I->waitForElementVisible('.access-denied a.goodaccess-button-orange');
        $leftDesktopMenu->goToAccessControl();
        $I->waitForElementVisible('.access-denied a.goodaccess-button-orange');
        $leftDesktopMenu->goToAccessLogs();
        $I->waitForElementVisible('.access-denied a.goodaccess-button-orange');
        $leftDesktopMenu->goToSettings();
    }

    public function freeTrialTest(\AcceptanceTester $I, LeftDesktopMenu $leftDesktopMenu): void
    {
        $leftDesktopMenu->selectTeamByName('FreeTrial-Test');
    }

}
