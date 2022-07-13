<?php
namespace Page\AcceptanceChrome\ControlPanel;

use Codeception\Util\Locator;

class OrderPricing
{
    public static string $URL = '/';

    public string $packageTopItem = '.m-pricing-table-4__top-items .m-pricing-table-4__top-item';
    public string $packageName = '.m-pricing-table-4__top-items .m-pricing-table-4__top-item span';
    public string $duration = '.duration input[name=duration]';
    public string $monthlyButton = 'label[for=MonthlyMobile]';
    public string $annuallyButton = 'label[for=AnnuallyMobile]';


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

    public function checkIfProductIsAvailableForBuy($name): void
    {
        $I = $this->acceptanceTester;

        $package = Locator::contains($this->packageTopItem, $name);
        $I->seeElement($package."/descendant-or-self::*/*[@class and contains(concat(' ', normalize-space(@class), ' '), ' btn ')][contains(., 'Go with ".$name."')]");
    }

    public function changeDuration($type): void
    {
        $I = $this->acceptanceTester;
        if($type === 'Monthly'){
            $I->click($this->monthlyButton);
        } elseif($type === 'Annually') {
            $I->click($this->annuallyButton);
        }
    }

    public function goWithProduct($name): void
    {
        $I = $this->acceptanceTester;

        $package = Locator::contains($this->packageTopItem, $name);
        $I->click($package."/descendant-or-self::*/*[@class and contains(concat(' ', normalize-space(@class), ' '), ' btn ')][contains(., 'Go with ".$name."')]");
    }

}
