<?php
namespace Page\AcceptanceChrome;

class Login
{
    public static string $URL = '/';

    public string $pageLoginHeader              = 'h1 span.access-color';
    public string $usernameField                = '#frm-loginForm input[name=username]';
    public string $passwordField                = '#frm-loginForm input[name=password]';
    public string $loginButton                  = '#frm-loginForm input[type=submit]';
    public string $forgotPassword               = '#frm-loginForm #m_login_forget_password';
    public string $dontHaveAccount              = '#frm-loginForm .m-login__form-action';
    public string $pageForgotPasswordHeader     = '.m-login__forget-password h1 span';
    public string $forgotPasswordUsernameField  = '#frm-forgottenPasswordForm input[name=username]';
    public string $forgotPasswordButton         = '#frm-forgottenPasswordForm input[type=submit]';
    public string $backToLogin                  = '#frm-forgottenPasswordForm #m_login_forget_password_cancel';

    public static function route($param): string
    {
        return static::$URL.$param;
    }

    /**
     * @var \AcceptanceTester;
     */
    protected \AcceptanceTester $acceptanceTester;

    public function __construct(\AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
    }

    /**
     * @throws \Exception
     */
    public function login($email, $password): void
    {
        $I = $this->acceptanceTester;

        $I->amOnPage(self::$URL);
        $I->amOnSubdomain('sign');
        $I->waitPageLoad();
//        $I->see('Account Login', $this->pageLoginHeader);
        $I->fillField($this->usernameField, $email);
        $I->fillField($this->passwordField, $password);
        $I->click($this->loginButton);
    }

    public function forgotPassword($email): void
    {
        $I = $this->acceptanceTester;

        $I->amOnPage(self::$URL);
        $I->amOnSubdomain('sign');
        $I->click($this->forgotPassword);
        $I->see('Forgotten Password', $this->pageForgotPasswordHeader);
        $I->fillField($this->forgotPasswordUsernameField, $email);
        $I->click($this->forgotPasswordButton);
    }

    public function backToLogin(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->backToLogin);
    }

    public function dontHaveAccount(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->dontHaveAccount);
    }

}
