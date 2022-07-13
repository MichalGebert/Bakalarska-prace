<?php

use Page\AcceptanceChrome\ControlPanel\DesktopHeader;
use Page\AcceptanceChrome\ControlPanel\LeftDesktopMenu;
use Page\AcceptanceChrome\ControlPanel\AccessControl;
use Page\AcceptanceChrome\Login;

class AccessControlCest
{
    /**
     * @throws Exception
     */
    public function _before(AcceptanceTester $I, Login $loginPage, LeftDesktopMenu $leftDesktopMenu): void
    {
        $I->maximizeWindow();
        $loginPage->login('tester@goodaccess.com', 'password');
        $I->waitPageLoad();
        $leftDesktopMenu->selectTeamByName('Advanced-Test');
        $I->waitPageLoad();
        $leftDesktopMenu->goToAccessControl();
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
    public function accessControlActions(\AcceptanceTester $I, AccessControl $accessControl, LeftDesktopMenu $leftDesktopMenu): void
    {
//        $I->closeTracy();
        $accessControl->activateAccessControl();
        $I->waitPageLoad();
        $accessControl->seeAccessCardItem('VIP', 'Tester GoodAccess [Me]');
        $accessControl->addAccessCard('Testik');
        $accessControl->removeAccessCard('Testik');
        $accessCardMembers = [
            [
                'name' => 'tester@goodaccess.com',
                'add' => true
            ],
            [
                'name' => 'SomeName',
                'add' => false
            ]
        ];
        $accessControl->editAccessCardMembers('VIP', $accessCardMembers);
        $I->reloadPage();
        $accessControl->removeAccessCard('VIP');
    }

}
