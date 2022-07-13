<?php
namespace Page\AcceptanceChrome\ControlPanel;

class DesktopHeader
{
    public static string $URL = '/';

    private string $logo                 = 'header #samohyb_logo';
    private string $accountNameHeader    = 'header .m-topbar__user-profile .m-topbar__username';
    private string $accountImgHeader     = 'header .m-topbar__user-profile img';
    private string $showAccountMenu      = 'header .m-topbar__user-profile .headname';

    private string $accountInfoMenu      = '#m_header_topbar #sh_header_user_profile';
    private string $billingMenu          = '#m_header_topbar #sh_header_user_payments';
    private string $securityMenu         = '#m_header_topbar #sh_header_user_security';
    private string $helpdeskMenu         = '#m_header_topbar #sh_header_user_helpdesk';
    private string $logoutButton         = '#m_header_topbar .m-nav__item a.btn';

    private string $accountNameMenu      = '#m_header_topbar .m-dropdown__wrapper .m-card-user__name';
    private string $accountEmailMenu     = '#m_header_topbar .m-dropdown__wrapper .m-card-user__email';
    private string $accountImgMenu       = '#m_header_topbar .m-dropdown__wrapper .m-card-user__pic img';

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
    public function logout(): void
    {
        $I = $this->acceptanceTester;
        $I->waitForElementVisible($this->showAccountMenu);
        $I->click($this->showAccountMenu);
        $I->click($this->logoutButton);
    }

    public function goToAccountInfo(): void
    {
        $I = $this->acceptanceTester;
        $I->waitPageLoad();
        $I->click($this->showAccountMenu);
        $I->click($this->accountInfoMenu);
    }

    public function goToBilling(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->showAccountMenu);
        $I->click($this->billingMenu);
    }

    public function goToSecurity(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->showAccountMenu);
        $I->click($this->securityMenu);
    }

    public function goToHelpdesk(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->showAccountMenu);
        $I->click($this->helpdeskMenu);
    }

    public function goToHomePage(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->logo);
    }

    public function checkHeaderName($name): void
    {
        $I = $this->acceptanceTester;
        $I->see($name, $this->accountNameHeader);
    }

    public function checkMenuNameAndEmail($name, $email): void
    {
        $I = $this->acceptanceTester;
        $I->see($name, $this->accountNameMenu);
        $I->see($email, $this->accountEmailMenu);
    }

}
