<?php

namespace integration\services\payment;

use App\Model\General\OrganizationRepository;
use App\Model\Payment\PaymentItemRepository;
use App\Model\Product\ProductCatalogRepository;
use App\Services\payment\PriceService;
use integration\IntegrationTest;
class PriceServiceTest extends IntegrationTest
{
    /**
     * @dataProvider countPriceProvider
     */
    public function testCountPrice($countryID, $order, $vat_id, $lang, $organizationID, $isManagedByPartner, $upgradingDiscount, $expectedResult): void
    {
        $priceService = $this->container->getByType(PriceService::class);
        $price = $priceService->countPrice($countryID, $order, $vat_id, $lang, $organizationID, $isManagedByPartner, $upgradingDiscount);
        $this->assertEquals($expectedResult, $price);
    }

    /**
     * @return array
     */
    protected function countPriceProvider(): array
    {
        return [
            [ 54, null, null, null, self::ESSENTIAL_TEST_ID, 0, null, 580.8 ],
            [ 128, null, null, null, self::ADVANCED_TEST_ID, 0, null, 1132.8 ],
            [ 216, null, null, null, self::PREMIUM_TEST_ID, 0, null, 2400 ],
            [ 216, null, null, null, self::FREE_TRIAL_TEST_ID, 0, null, 0 ],
            [ 54, null, null, null, self::FREEMIUM_TEST_ID, 0, null, 0 ],
        ];
    }

    /**
     * @dataProvider countVatPriceProvider
     */
    public function testCountVatPriceArr($countryID, $vatID, $totalPrice, $expectedResult): void
    {
        $priceService = $this->container->getByType(PriceService::class);
        $priceArr = $priceService->countVatPriceArr($countryID, $vatID, $totalPrice);
        $this->assertEquals($expectedResult, $priceArr);
    }

    /**
     * @return array
     */
    protected function countVatPriceProvider(): array
    {
        return [
            [ 54, 'U12345678', 50,
                [
                    PaymentItemRepository::COL_VAT_PERCENT => 21,
                    PaymentItemRepository::COL_NET_PRICE => 50,
                    PaymentItemRepository::COL_TOTAL_PRICE => 60.5,
                    PaymentItemRepository::COL_VAT_PRICE => 10.5
                ]
            ],
            [ 216, 'U12345678', 59.12,
                [
                    PaymentItemRepository::COL_VAT_PERCENT => 0,
                    PaymentItemRepository::COL_NET_PRICE => 59.12,
                    PaymentItemRepository::COL_TOTAL_PRICE => 59.12,
                    PaymentItemRepository::COL_VAT_PRICE => 0.0
                ]
            ],
            [ 216, null, 314,
                [
                    PaymentItemRepository::COL_VAT_PERCENT => 0,
                    PaymentItemRepository::COL_NET_PRICE => 314,
                    PaymentItemRepository::COL_TOTAL_PRICE => 314.0,
                    PaymentItemRepository::COL_VAT_PRICE => 0.0
                ]
            ],
            [ 54, null, 15,
                [
                    PaymentItemRepository::COL_VAT_PERCENT => 21,
                    PaymentItemRepository::COL_NET_PRICE => 15,
                    PaymentItemRepository::COL_TOTAL_PRICE => 18.15,
                    PaymentItemRepository::COL_VAT_PRICE => 3.1499999999999986
                ]
            ],
        ];
    }

    /**
     * @dataProvider countUpgradingPriceProvider
     */
    public function testCountUpgradingPrice($upgradingProductID, $organizationID, $lang, $includedVpnUsers, $discountIRow, $expectedResult): void
    {
        $productCatalogRepository = $this->container->getByType(ProductCatalogRepository::class);
        $upgradingProductIRow = $productCatalogRepository->getById($upgradingProductID);
        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getById($organizationID);

        $priceService = $this->container->getByType(PriceService::class);
        $priceArr = $priceService->countUpgradingPrice($upgradingProductIRow, $organizationIRow, $lang, $includedVpnUsers, $discountIRow);
        $this->assertEquals($expectedResult, $priceArr);
    }

    /**
     * @return array
     */
    protected function countUpgradingPriceProvider(): array
    {

        return [
            [ self::ESSENTIAL_MONTH_ID, self::ESSENTIAL_TEST_ID, 'us', 10, null,
                [
                    542.46, 433.97, 108.49
                ]
            ],
            [ self::ADVANCED_MONTH_ID, self::ADVANCED_TEST_ID, 'us', 25, null,
                [
                    2441.09, 867.94, 1573.15
                ]
            ]
        ];
    }

    public function testGetRemainingMembersForUpgrade(): void
    {
        $productCatalogRepository = $this->container->getByType(ProductCatalogRepository::class);
        $currentProductCatalogIRow = $productCatalogRepository->getById(self::ESSENTIAL_MONTH_ID);
        $upgradingProductCatalogIRow = $productCatalogRepository->getById(self::PREMIUM_MONTH_ID);

        $priceService = $this->container->getByType(PriceService::class);
        $result = $priceService->getRemainingMembersForUpgrade($currentProductCatalogIRow, $upgradingProductCatalogIRow);
        $this->assertSame(10, $result);
    }
}
