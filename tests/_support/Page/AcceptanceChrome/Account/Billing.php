<?php
namespace Page\AcceptanceChrome\Account;

use Codeception\Util\Locator;

class Billing
{
    public static string $URL = '/';

    private string $teamSelector         = '#dropTeams .m-menu__link';
    private string $teamRows             = '.m-dropdown__wrapper .changeorg';
    private string $planName             = '.custom-grid-subscription .col-lg-7 b';
    private string $paymentDatatable     = '#payment_datatable';
    private string $datatableRow         = '#payment_datatable .m-datatable__cell span';
    private string $cancelSubscription   = '#cancelSubscription';

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

    public function selectTeamByName($name): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->teamSelector);
        $I->click(Locator::contains($this->teamRows, $name));
    }

    public function seeInPaymentDatatable($name): void
    {
        $I = $this->acceptanceTester;
        $I->click(Locator::contains($this->datatableRow, $name));
    }

    public function checkCancelSubscriptionModal(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->cancelSubscription);
        $I->see('We’re sorry to see you go!', 'h3');
    }

    /**
     * @throws \Exception
     */
    public function checkTeamPlan($name): void
    {
        $I = $this->acceptanceTester;
        $I->waitForElementVisible($this->planName);
        $I->see($name, $this->planName);
    }

}
