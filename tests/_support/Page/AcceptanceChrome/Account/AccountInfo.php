<?php
namespace Page\AcceptanceChrome\Account;

use Codeception\Util\Locator;

class AccountInfo
{
    public static string $URL = '/';

    private string $name                 = '#frm-changeBillingForm input[name=AccountName]';
    private string $phoneNumber          = '#frm-changeBillingForm input[name=PhoneNumber]';
    private string $companyName          = '#frm-changeBillingForm input[name=CompanyName]';
    private string $address              = '#frm-changeBillingForm textarea[name=Address]';
    private string $vatID                = '#frm-changeBillingForm input[name=vat_id]';
    private string $companyID            = '#frm-changeBillingForm input[name=company_id]';
    private string $country              = '#frm-changeBillingForm select[name=countrySelect]';

    public string $saveChanges           = '#frm-changeBillingForm input[name=_submit]';

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
    public function changeAccountDetails(): void
    {
        $I = $this->acceptanceTester;

        $vatID = '';
        $companyID = '';
        $characters = 'abcdefghijklmnopqrstuvwxyz ';

        $phoneNumber = '+';
        for($i = 0; $i < 12; $i++){
            $phoneNumber .= random_int(0,10);
        }
        $I->fillField($this->phoneNumber, $phoneNumber);

        $companyName = '';
        for ($i = 0; $i < 20; $i++) {
            $index = random_int(0, strlen($characters) - 1);
            $companyName .= $characters[$index];
        }
        $I->fillField($this->companyName, $companyName);

        $address = '';
        for ($i = 0; $i < 20; $i++) {
            $index = random_int(0, strlen($characters) - 1);
            $address .= $characters[$index];
        }
        $I->fillField($this->address, $address);

        $countryID = random_int(1,227);
        $I->selectOption($this->country, $countryID);

        $I->scrollTo($this->saveChanges);
        $I->click($this->saveChanges);

        $I->seeAndCloseToastSuccessNotification('Billing data have been updated successfully.');

        $I->seeInField($this->name, 'Tester GoodAccess');
        $I->seeInField($this->phoneNumber, $phoneNumber);
        $I->seeInField($this->companyName, trim($companyName));
        $I->seeInField($this->address, trim($address));
        $I->seeInField($this->vatID, $vatID);
        $I->seeInField($this->companyID, $companyID);
        $I->seeInField($this->country, (string)$countryID);
    }

}
