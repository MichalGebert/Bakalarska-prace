<?php
namespace Page\AcceptanceChrome\ControlPanel;

use Codeception\Util\Locator;

class LeftDesktopMenu
{
    public static string $URL = '/';

    public string $teamSelector     = '#m_ver_menu #dropTeams .m-menu__link';
    public string $teamRows         = '.teams-submenu .changeorg';
    public string $orderAnotherTeam = '.teams-submenu a.btn';
    public string $dashboard        = '#m_ver_menu #sh_sidebar_non_protected';
    public string $gateways         = '#m_ver_menu #sh_sidebar_ga_view_gateway';
    public string $members          = '#m_ver_menu #sh_sidebar_ga_view_teamMembers';
    public string $systems          = '#m_ver_menu #sh_sidebar_ga_view_systems';
    public string $cloudBranches    = '#m_ver_menu #sh_sidebar_ga_view_branches';
    public string $accessControl    = '#m_ver_menu #sh_sidebar_ga_view_accessControl';
    public string $accessLogs       = '#m_ver_menu #sh_sidebar_ga_view_accessLogs';
    public string $payments         = '#m_ver_menu #sh_sidebar_ga_payments';
    public string $settings         = '#m_ver_menu #sh_sidebar_ga_view_settings';
    public string $downloadApp      = '#m_ver_menu #sh_sidebar_Goodaccess_download';
    public string $integrations     = '#m_ver_menu #sh_sidebar_Goodaccess_integration';

    public static function route($param)
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

    public function viewFullMenu(): void
    {
        $I = $this->acceptanceTester;
        $I->seeElement($this->teamSelector);
        $I->seeElement($this->dashboard);
        $I->seeElement($this->gateways);
        $I->seeElement($this->members);
        $I->seeElement($this->systems);
        $I->seeElement($this->cloudBranches);
        $I->seeElement($this->accessControl);
        $I->seeElement($this->accessLogs);
        $I->seeElement($this->payments);
        $I->seeElement($this->settings);
        $I->seeElement($this->downloadApp);
        $I->seeElement($this->integrations);
    }

    public function selectTeamByName($name): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->teamSelector);
        $I->waitForElementVisible($this->teamRows);
        $I->click(Locator::contains($this->teamRows, $name));
    }

    public function goToDashboard(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->dashboard);
        $I->waitPageLoad();
    }

    public function goToGateways(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->gateways);
        $I->waitPageLoad();
    }

    public function goToMembers(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->members);
        $I->waitPageLoad();
    }

    public function goToSystems(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->systems);
        $I->waitPageLoad();
    }

    public function goToCloudBranches(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->cloudBranches);
        $I->waitPageLoad();
    }

    public function goToAccessControl(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->accessControl);
        $I->waitPageLoad();
    }

    public function goToAccessLogs(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->accessLogs);
        $I->waitPageLoad();
    }

    public function goToPayments(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->payments);
        $I->waitPageLoad();
    }

    public function goToSettings(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->settings);
        $I->waitPageLoad();
    }

    public function goToDownloadApp(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->downloadApp);
        $I->waitPageLoad();
    }

    public function goToIntegrations(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->integrations);
        $I->waitPageLoad();
    }

    public function goToOrderAnotherTeam(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->teamSelector);
        $I->waitForElementVisible($this->orderAnotherTeam);
        $I->click($this->orderAnotherTeam);
    }

}
