<?php

use Page\AcceptanceChrome\ControlPanel\DesktopHeader;
use Page\AcceptanceChrome\ControlPanel\LeftDesktopMenu;
use Page\AcceptanceChrome\ControlPanel\Payments;
use Page\AcceptanceChrome\Login;

class PaymentsCest
{
    /**
     * @throws Exception
     */
    public function _before(AcceptanceTester $I, Login $loginPage, LeftDesktopMenu $leftDesktopMenu): void
    {
        $I->maximizeWindow();
        $loginPage->login('tester@goodaccess.com', 'password');
        $I->waitPageLoad();

        $leftDesktopMenu->selectTeamByName('Premium-Test');
        $I->waitPageLoad();
        $leftDesktopMenu->goToPayments();
        $I->waitPageLoad();
    }

    public function _after(\AcceptanceTester $I, DesktopHeader $desktopHeader): void
    {

    }

    /**
     * @throws Exception
     */
    public function paymentsActions(\AcceptanceTester $I, Payments $payments): void
    {
        $payments->seeInPaymentDatatable('GoodAccess: PREMIUM - 12M (20 Team Members included, 12 months)');
        $payments->openAndCheckInvoice('GoodAccess: PREMIUM - 12M (20 Team Members included, 12 months)');
    }

}
