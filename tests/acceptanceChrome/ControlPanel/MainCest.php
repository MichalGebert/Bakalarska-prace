<?php

use Page\AcceptanceChrome\ControlPanel\DesktopHeader;
use Page\AcceptanceChrome\ControlPanel\LeftDesktopMenu;
use Page\AcceptanceChrome\Login;

class MainCest
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

    public function tryToLoadEveryPageTest(\AcceptanceTester $I, LeftDesktopMenu $leftDesktopMenu): void
    {
        $leftDesktopMenu->selectTeamByName('FreeTrial-Test');
        $I->waitPageLoad();
        $leftDesktopMenu->viewFullMenu();

        $leftDesktopMenu->goToDashboard();
        $leftDesktopMenu->goToGateways();
        $leftDesktopMenu->goToMembers();
        $leftDesktopMenu->goToSystems();
        $leftDesktopMenu->goToCloudBranches();
        $leftDesktopMenu->goToAccessControl();
        $leftDesktopMenu->goToAccessLogs();
        $leftDesktopMenu->goToPayments();
        $leftDesktopMenu->goToSettings();
    }

}
