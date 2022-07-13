<?php

use Page\AcceptanceChrome\ControlPanel\DesktopHeader;
use Page\AcceptanceChrome\Account\LeftAccountDesktopMenu;
use Page\AcceptanceChrome\Account\Billing;
use Page\AcceptanceChrome\Account\Security;
use Page\AcceptanceChrome\Account\AccountInfo;
use Page\AcceptanceChrome\Login;

class AccountCest
{
    /**
     * @throws Exception
     */
    public function _before(AcceptanceTester $I, Login $loginPage, DesktopHeader $desktopHeader): void
    {
        $I->maximizeWindow();
        $loginPage->login('tester@goodaccess.com', 'password');
        $I->waitPageLoad();
        $desktopHeader->goToAccountInfo();
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
    public function changeAvatarTest(\AcceptanceTester $I, LeftAccountDesktopMenu $leftAccountDesktopMenu): void
    {
        $leftAccountDesktopMenu->viewFullMenu();
        $leftAccountDesktopMenu->changeAccountAvatar();
    }

    /**
     * @throws Exception
     */
    public function viewBillingTest(\AcceptanceTester $I, LeftAccountDesktopMenu $leftAccountDesktopMenu, Billing $billing): void
    {
        $leftAccountDesktopMenu->goToBilling();
        $billing->selectTeamByName('Premium-Test');
        $billing->checkTeamPlan('PREMIUM plan');
        $billing->seeInPaymentDatatable('2022-06-07');
        $billing->seeInPaymentDatatable('GoodAccess: PREMIUM - 12M (20 Team Members included, 12 months)');
        $billing->seeInPaymentDatatable('$2,904.00');
    }

    /**
     * @throws Exception
     */
    public function viewSecurityTest(\AcceptanceTester $I, LeftAccountDesktopMenu $leftAccountDesktopMenu, Security $security): void
    {
        $leftAccountDesktopMenu->goToSecurity();
        $security->checkNotEnabledTwoFA();
    }

    /**
     * @throws Exception
     */
    public function changeAccountDetails(\AcceptanceTester $I, AccountInfo $accountInfo): void
    {
        $accountInfo->changeAccountDetails();
    }

}
