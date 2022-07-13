<?php

use Page\AcceptanceChrome\ControlPanel\DesktopHeader;
use Page\AcceptanceChrome\ControlPanel\LeftDesktopMenu;
use Page\AcceptanceChrome\ControlPanel\Members;
use Page\AcceptanceChrome\Login;

class MembersCest
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
        $leftDesktopMenu->goToMembers();
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
    public function memberActions(\AcceptanceTester $I, Members $members): void
    {
//        $I->closeTracy();
        $members->addDevice('Member', 'password');
        $I->seeAndCloseToastSuccessNotification();
//        $I->closeTracy();
        $members->editMember('Member', 'MegaMember');
        $I->seeAndCloseToastSuccessNotification();
//        $I->closeTracy();
        $members->removeMember('MegaMember');
//        $I->closeTracy();
    }

}
