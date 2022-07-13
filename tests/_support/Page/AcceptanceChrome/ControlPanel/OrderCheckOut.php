<?php
namespace Page\AcceptanceChrome\ControlPanel;

use Codeception\Util\Locator;
use namespacetest\model\User;

class OrderCheckOut
{
    public static string $URL = '/';

    public string $productName = '#frm-checkOutSecondForm-checkOutSecondForm select[name=productSelection]';
    public string $duration = '#frm-checkOutSecondForm-checkOutSecondForm select[name=duration]';

    public string $companyName = '#frm-checkOutSecondForm-checkOutSecondForm input[name=CompanyName]';
    public string $companyID = '#frm-checkOutSecondForm-checkOutSecondForm input[name=company_id]';
    public string $vatID = '#frm-checkOutSecondForm-checkOutSecondForm input[name=vat_id]';
    public string $address = '#frm-checkOutSecondForm-checkOutSecondForm textarea[name=Address]';
    public string $country = '.select2-selection__rendered';
    public string $countrySearch = '.select2-search__field';
    public string $countrySearchResult = '.select2-results__option';

    public string $members = '#members_input';
    public string $gateways = '#gateways_input';
    public string $branches = '#branches_input';

    public string $priceForMembers = '.price-for-member';
    public string $priceForGateways = '.price-for-gateways';
    public string $priceForBranches = '.price-for-branches';
    public string $vatPrice = '.price-vat-place';
    public string $totalPrice = '.price';

    public string $choosePayment = '#frm-checkOutSecondForm-checkOutSecondForm input[name=payment]';

    public string $goBackToPricing = '.row.back-link a';

    public string $productRow = '.row.product-row .col-lg-12';

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

    public function fillBillingInfo($companyName = null, $address = null, $countryName = null, $vatID = null, $companyID = null): void
    {
        $I = $this->acceptanceTester;

        if($companyName){
            $I->fillField($this->companyName, $companyName);
        }
        if($address){
            $I->fillField($this->address, $address);
        }
        if($countryName){
            $I->click($this->country);
            $I->fillField($this->countrySearch, $countryName);
            $searchResult = Locator::contains($this->countrySearchResult, $countryName);
            $I->click($searchResult);
        }
        if($vatID){
            $I->selectOption($this->vatID, $vatID);
        }
        if($companyID){
            $I->selectOption($this->companyID, $companyID);
        }
    }

    public function seeConfiguration($productName, $duration, $members, $gateways, $branches): void
    {
        $I = $this->acceptanceTester;

        $I->seeOptionIsSelected($this->productName, $productName);
        $I->seeOptionIsSelected($this->duration, $duration);

        $I->seeInField($this->members, $members);
        $I->seeInField($this->gateways, $gateways);
        $I->seeInField($this->branches, $branches);
    }

    public function addMembers($count): void
    {
        $I = $this->acceptanceTester;

        $rowForEdit = Locator::contains($this->productRow, 'Members');
        $addMembersButton = $rowForEdit."/descendant-or-self::*/*[@class and contains(concat(' ', normalize-space(@class), ' '), ' quantity-up ')]";

        for($i = 0; $i < round($count/5); $i++){
            $I->click($addMembersButton);
        }
    }

    public function addGateways($count): void
    {
        $I = $this->acceptanceTester;

        $rowForEdit = Locator::contains($this->productRow, 'Dedicated gateways');
        $addGatewaysButton = $rowForEdit."/descendant-or-self::*/*[@class and contains(concat(' ', normalize-space(@class), ' '), ' quantity-up ')]";

        for($i = 0; $i < $count; $i++){
            $I->click($addGatewaysButton);
        }
    }

    public function addBranches($count): void
    {
        $I = $this->acceptanceTester;

        $rowForEdit = Locator::contains($this->productRow, 'branch');
        $addBranchesButton = $rowForEdit."/descendant-or-self::*/*[@class and contains(concat(' ', normalize-space(@class), ' '), ' quantity-up ')]";

        for($i = 0; $i < $count; $i++){
            $I->click($addBranchesButton);
        }
    }

    public function removeMembers($count): void
    {
        $I = $this->acceptanceTester;

        $rowForEdit = Locator::contains($this->productRow, 'Members');
        $removeMembersButton = $rowForEdit."/descendant-or-self::*/*[@class and contains(concat(' ', normalize-space(@class), ' '), ' quantity-down ')]";

        for($i = 0; $i < round($count/5); $i++){
            $I->click($removeMembersButton);
        }
    }

    public function removeGateways($count): void
    {
        $I = $this->acceptanceTester;

        $rowForEdit = Locator::contains($this->productRow, 'gateways');
        $removeGatewaysButton = $rowForEdit."/descendant-or-self::*/*[@class and contains(concat(' ', normalize-space(@class), ' '), ' quantity-down ')]";

        for($i = 0; $i < $count; $i++){
            $I->click($removeGatewaysButton);
        }
    }

    public function removeBranches($count): void
    {
        $I = $this->acceptanceTester;

        $rowForEdit = Locator::contains($this->productRow, 'branch');
        $removeBranchesButton = $rowForEdit."/descendant-or-self::*/*[@class and contains(concat(' ', normalize-space(@class), ' '), ' quantity-down ')]";

        for($i = 0; $i < $count; $i++){
            $I->click($removeBranchesButton);
        }
    }

    public function checkPrice($membersPrice = null, $gatewayPrice = null, $branchesPrice = null, $totalPrice = null, $vatPrice = null): void
    {
        $I = $this->acceptanceTester;

        if($membersPrice){
            $I->see($membersPrice, $this->priceForMembers);
        }
        if($gatewayPrice){
            $I->see($gatewayPrice, $this->priceForGateways);
        }
        if($branchesPrice){
            $I->see($branchesPrice, $this->priceForBranches);
        }
        if($totalPrice){
            $I->see($totalPrice, $this->totalPrice);
        }
        if($vatPrice){
            $I->see($vatPrice, $this->vatPrice);
        }
    }

    public function selectPaymentMethod($name): void
    {
        $I = $this->acceptanceTester;

        if($name === 'paypal'){
            $I->selectOption($this->choosePayment, ['value' => 'paypal']);
        } else {
            $I->selectOption($this->choosePayment, ['value' => 'credit_card']);
        }
    }

    public function goBackToPricing(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->goBackToPricing);
    }
}
