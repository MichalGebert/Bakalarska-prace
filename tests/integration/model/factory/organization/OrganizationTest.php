<?php

namespace integration\model\factory\organization;

use App\Model\Authorizator\Block\ControlPanel;
use App\Model\Factory\Organization\OrganizationFactory;
use Codeception\Util\Debug;
use integration\IntegrationTest;
use Nette\Database\Table\ActiveRow;
use Nette\Http\Session;
use Nette\Security\User;

class OrganizationTest extends IntegrationTest
{
    /**
     * @dataProvider getGatewayCountProvider
     * @throws \Nette\Application\UI\InvalidLinkException
     */
    public function testGetGatewayCount($used, $licenced, $organizationID): void
    {
        $expected = [
            'used' => $used,
            'licenced' => $licenced
        ];
        $organizationFactory = $this->container->getByType(OrganizationFactory::class);
        $organization = $organizationFactory->create($organizationID);
        $result = $organization->getGatewayCount();
        $this->assertSame($expected, $result);
    }

    /**
     * @return array
     */
    protected function getGatewayCountProvider(): array
    {
        return [
            [0, 1, self::ESSENTIAL_TEST_ID],
            [0, 1, self::ADVANCED_TEST_ID],
            [0, 2, self::PREMIUM_TEST_ID]
        ];
    }

    /**
     * @throws \Nette\Security\AuthenticationException
     * @throws \Nette\Application\UI\InvalidLinkException
     */
    public function testIsPermitted(): void
    {
        $organizationFactory = $this->container->getByType(OrganizationFactory::class);
        $organization = $organizationFactory->create(self::ESSENTIAL_MONTH_ID);
        $this->assertFalse($organization->isPermitted());
    }

    /**
     * @dataProvider getMemberCountProvider
     * @throws \Nette\Application\UI\InvalidLinkException
     */
    public function testGetMemberCount($used, $licenced, $organizationID): void
    {
        $expected = [
            'used' => $used,
            'licenced' => $licenced
        ];
        $organizationFactory = $this->container->getByType(OrganizationFactory::class);
        $organization = $organizationFactory->create($organizationID);
        $result = $organization->getMemberCount();
        $this->assertSame($expected, $result);
    }

    /**
     * @return array
     */
    protected function getMemberCountProvider(): array
    {
        return [
            [1, 10, self::ESSENTIAL_TEST_ID],
            [2, 10, self::ADVANCED_TEST_ID],
            [1, 20, self::PREMIUM_TEST_ID]
        ];
    }

    /**
     * @dataProvider getRenewalPriceProvider
     * @throws \Nette\Application\UI\InvalidLinkException
     */
    public function testGetRenewalPrice($organizationID, $expected): void
    {
        $organizationFactory = $this->container->getByType(OrganizationFactory::class);
        $organization = $organizationFactory->create($organizationID);
        $result = $organization->getRenewalPrice('us');
        $this->assertSame($expected, $result);
    }

    /**
     * @return array
     */
    protected function getRenewalPriceProvider(): array
    {
        return [
            [self::ESSENTIAL_TEST_ID, [480.0, 0.0, 0.0]],
            [self::ADVANCED_TEST_ID, [960.0, 0.0, 0.0]],
            [self::PREMIUM_TEST_ID, [2400.0, 0.0, 0.0]]
        ];
    }

    /**
     * @dataProvider getAvailableBranchesProvider
     * @throws \Nette\Application\UI\InvalidLinkException
     */
    public function testGetAvailableBranches($organizationID, $expected): void
    {
        $organizationFactory = $this->container->getByType(OrganizationFactory::class);
        $organization = $organizationFactory->create($organizationID);
        $result = $organization->getAvailableBranches('us');
        $this->assertSame($expected, $result);
    }

    /**
     * @return array
     */
    protected function getAvailableBranchesProvider(): array
    {
        return [
            [self::ESSENTIAL_TEST_ID, [
                'licenced'  => 0,
                'occupied'  => 0,
                'available' => 0
            ]],
            [self::ADVANCED_TEST_ID, [
                'licenced'  => 1,
                'occupied'  => 0,
                'available' => 1
            ]],
            [self::PREMIUM_TEST_ID, [
                'licenced'  => 5,
                'occupied'  => 0,
                'available' => 5
            ]]
        ];
    }

    /**
     * @dataProvider isFeatureAllowedProvider
     * @throws \Nette\Application\UI\InvalidLinkException
     */
    public function testIsFeatureAllowed($organizationID, $feature, $expected): void
    {
        $organizationFactory = $this->container->getByType(OrganizationFactory::class);
        $organization = $organizationFactory->create($organizationID);
        $result = $organization->isFeatureAllowed($feature);
        $this->assertSame($expected, $result);
    }

    /**
     * @return array
     */
    protected function isFeatureAllowedProvider(): array
    {
        return [
            [self::ESSENTIAL_TEST_ID, ControlPanel::BRANCHES, false],
            [self::ESSENTIAL_TEST_ID, ControlPanel::ACCESS_CONTROL, false],
            [self::ADVANCED_TEST_ID, ControlPanel::BRANCHES, true],
            [self::ADVANCED_TEST_ID, ControlPanel::ACCESS_CONTROL, true],
            [self::PREMIUM_TEST_ID, ControlPanel::CUSTOM_DNS_SERVERS, true],
            [self::ESSENTIAL_TEST_ID, ControlPanel::CUSTOM_DNS_SERVERS, false],
        ];
    }

    /**
     * @dataProvider getMrrProvider
     * @throws \Nette\Application\UI\InvalidLinkException
     * @throws \Exception
     */
    public function testGetMrr($organizationID, $expected)
    {
        $organizationFactory = $this->container->getByType(OrganizationFactory::class);
        $organization = $organizationFactory->create($organizationID);
        $result = $organization->getMrr();
        $this->assertSame($expected, $result);
    }

    /**
     * @return array
     */
    protected function getMrrProvider(): array
    {
        return [
            [self::ESSENTIAL_TEST_ID, [
                'mrr'       => 40.0,
                'addOnMrr'  => 0
            ]],
            [self::ADVANCED_TEST_ID, [
                'mrr'       => 80.0,
                'addOnMrr'  => 0
            ]],
            [self::PREMIUM_TEST_ID, [
                'mrr'       => 200.0,
                'addOnMrr'  => 0
            ]],
        ];
    }

    /**
     * @throws \Nette\Application\UI\InvalidLinkException
     */
    public function testGetIRow(): void
    {
        $organizationFactory = $this->container->getByType(OrganizationFactory::class);
        $organization = $organizationFactory->create(self::ESSENTIAL_TEST_ID);
        $result = $organization->getIRow();
        $this->assertInstanceOf(ActiveRow::class, $result);
    }

    /**
     * @dataProvider getInvoiceMrrProvider
     * @throws \Nette\Application\UI\InvalidLinkException
     * @throws \Exception
     */
    public function testGetInvoiceMrr($organizationID, $expected)
    {
        $organizationFactory = $this->container->getByType(OrganizationFactory::class);
        $organization = $organizationFactory->create($organizationID);
        $result = $organization->getInvoiceMrr();
        $this->assertSame($expected, $result);
    }

    /**
     * @return array
     */
    protected function getInvoiceMrrProvider(): array
    {
        return [
            [self::ESSENTIAL_TEST_ID, 40.0],
            [self::ADVANCED_TEST_ID, 80.0],
            [self::PREMIUM_TEST_ID, 200.0],
        ];
    }
}
