<?php

class AccountTestCest
{
    /**
     * @throws Exception
     */
    public function _before(AcceptanceTester $I, \Page\AcceptanceChrome\Login $loginPage): void
    {
        $loginPage->login('tester@goodaccess.com', 'password');
        $I->click('.m-nav__link.m-dropdown__toggle.headname');
        $I->click('#sh_header_user_profile a');
    }

    /**
     * @throws Exception
     */
    public function viewAccountInfoTest(AcceptanceTester $I): void
    {
        $I->seeElement('#accName');
        $I->see('Billing Details');
        $I->seeInField('input[name=PhoneNumber]', '+420730730730');
        $I->seeInField('input[name=AccountName]', 'Tester GoodAccess');
        $I->click('.kt-avatar__upload');
        $I->see('Choose Avatar', '#exampleModalLabel');
    }

    public function viewTwoFactorAuthenticationTest(AcceptanceTester $I): void
    {
        $I->seeElement('#sh_userblock_security');
        $I->click('Security');
        $I->see('Two Factor Authentication', 'h3');
        $I->seeElement('input[name=allow2FA]');
        $I->seeCheckboxIsChecked('input[name=allow2FA]');
    }
}
