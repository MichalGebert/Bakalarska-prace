<?php
namespace Page\AcceptanceChrome\ControlPanel;

use Codeception\Util\Locator;
use PHPUnit\Framework\Assert;
use Tracy\Debugger;

class Payments
{
    public static string $URL = '/';

    private string $datatableTextRow     = '#payment_datatable .m-datatable__cell span';
    private string $datatableRow         = '#payment_datatable .m-datatable__row';
    private string $invoiceButtonClass   = 'invoicebtn';

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

    public function seeInPaymentDatatable($name): void
    {
        $I = $this->acceptanceTester;
        $I->seeElement(Locator::contains($this->datatableTextRow, $name));
    }

    public function openAndCheckInvoice($rowIncludes): void
    {
        $I = $this->acceptanceTester;

        $row = Locator::contains($this->datatableRow, $rowIncludes);
        $I->seeElement($row);
        $price = $I->grabTextFrom(Locator::find($row.'/descendant-or-self::*/*', ['data-field' => 'Price']));
        $I->click($row."/descendant-or-self::*/*[@class and contains(concat(' ', normalize-space(@class), ' '), ' ".$this->invoiceButtonClass." ')][contains(., 'Invoice')]");
        $I->see($price, 'body');
    }

}
