<?php

use Page\AcceptanceChrome\ControlPanel\DesktopHeader;
use Page\AcceptanceChrome\ControlPanel\LeftDesktopMenu;
use Page\AcceptanceChrome\ControlPanel\Systems;
use Page\AcceptanceChrome\Login;

class SystemsCest
{
    /**
     * @throws Exception
     */
    public function _before(AcceptanceTester $I, Login $loginPage, LeftDesktopMenu $leftDesktopMenu): void
    {
        $I->maximizeWindow();
        $loginPage->login('tester@goodaccess.com', 'password');
        $I->waitPageLoad();
        $leftDesktopMenu->selectTeamByName('Essential-Test');
        $I->waitPageLoad();
        $leftDesktopMenu->goToSystems();
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
    public function systemActions(\AcceptanceTester $I, Systems $systems): void
    {
//        $I->closeTracy();
        $systems->addSystem('Test VNC', 'VNC', 'test', null, 'seznam.cz');
        $I->seeAndCloseToastSuccessNotification();
//        $I->closeTracy();
        $systems->editSystem('Test VNC', 'Test');
        $I->seeAndCloseToastSuccessNotification();
//        $I->closeTracy();
        $systems->removeSystem('Test');
//        $I->closeTracy();
    }

}
