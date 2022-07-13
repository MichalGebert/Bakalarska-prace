<?php

class LoginCest
{
    public function _before(AcceptanceTester $I): void
    {
    }

    /**
     * @throws Exception
     */
    public function successfulLoginTest(AcceptanceTester $I, \Page\AcceptanceChrome\Login $loginPage): void
    {
        $loginPage->login('tester@goodaccess.com', 'password');
        $I->see('Tester GoodAccess', '.m-topbar__username.email');
    }
}
