<?php

namespace integration\Facade\GoodAccess;

use App\Facade\Goodaccess\AccessControlFacade;
use App\Model\Access\AccessCardRepository;
use App\Model\General\OrganizationRepository;
use App\Model\NotPermitted;
use integration\IntegrationTest;

class AccessControlFacadeTest extends IntegrationTest
{

    public function testCreateAccessCircle(): void
    {
        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getByParameter(OrganizationRepository::COL_ID, self::ADVANCED_TEST_ID);

        $accessControlFacade = $this->container->getByType(AccessControlFacade::class);
        $accessControlFacade->createAccessCircle($organizationIRow, ['Name' => 'TEST']);

        $accessCardRepository = $this->container->getByType(AccessCardRepository::class);
        $accessCardCount = $accessCardRepository->getCountByParameter([AccessCardRepository::COL_ORGANIZATION_ID, AccessCardRepository::COL_NAME], [self::ADVANCED_TEST_ID, 'TEST']);
        $this->assertSame(1, $accessCardCount);
    }

    public function testGetAccessCircle(): void
    {
        $this->expectException(NotPermitted::class);
        $accessControlFacade = $this->container->getByType(AccessControlFacade::class);
        $accessControlFacade->getAccessCircle(12);
    }

    public function testRemoveAccessCard(): void
    {
        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getByParameter(OrganizationRepository::COL_ID, self::ADVANCED_TEST_ID);
        $accessCardRepository = $this->container->getByType(AccessCardRepository::class);
        $accessCardIRows = $accessCardRepository->getAllByParameter([AccessCardRepository::COL_ORGANIZATION_ID, AccessCardRepository::COL_NAME], [self::ADVANCED_TEST_ID, 'TEST']);

        $accessControlFacade = $this->container->getByType(AccessControlFacade::class);
        foreach ($accessCardIRows as $accessCardIRow){
            $accessControlFacade->removeAccessCard($organizationIRow, $accessCardIRow);
        }

        $accessCardCount = $accessCardRepository->getCountByParameter([AccessCardRepository::COL_ORGANIZATION_ID, AccessCardRepository::COL_NAME], [self::ADVANCED_TEST_ID, 'TEST']);
        $this->assertSame(0, $accessCardCount);
    }

    /**
     * @dataProvider getNumberOfArrayProvider
     */
    public function testGetNumberOfArray($organizationID, $expected): void
    {
        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getByParameter(OrganizationRepository::COL_ID, $organizationID);

        $accessControlFacade = $this->container->getByType(AccessControlFacade::class);
        $result = $accessControlFacade->getNumberOfArray($organizationIRow);
        $this->assertSame($expected, $result);
    }

    /**
     * @return array
     */
    protected function getNumberOfArrayProvider(): array
    {
        return [
            [self::ESSENTIAL_TEST_ID, [
                'branches'      => 0,
                'systems'       => 0,
                'members'       => 1
            ]],
            [self::ADVANCED_TEST_ID, [
                'branches'      => 0,
                'systems'       => 1,
                'members'       => 2
            ]],
            [self::PREMIUM_TEST_ID, [
                'branches'      => 0,
                'systems'       => 0,
                'members'       => 1
            ]]
        ];
    }

    public function testActivateAccessControl(): void
    {
        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getByParameter(OrganizationRepository::COL_ID, self::ADVANCED_TEST_ID);

        $accessControlFacade = $this->container->getByType(AccessControlFacade::class);
        $accessControlFacade->activateAccessControl($organizationIRow);

        $accessCardRepository = $this->container->getByType(AccessCardRepository::class);
        $accessCardIRow = $accessCardRepository->getByParameter([AccessCardRepository::COL_ORGANIZATION_ID, AccessCardRepository::COL_NAME], [self::ADVANCED_TEST_ID, 'VIP']);
        $this->assertNotNull($accessCardIRow);
    }

    public function testChangeAccessCardName(): void
    {
        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getByParameter(OrganizationRepository::COL_ID, self::ADVANCED_TEST_ID);

        $accessCardRepository = $this->container->getByType(AccessCardRepository::class);
        $accessCardIRow = $accessCardRepository->getByParameter([AccessCardRepository::COL_ORGANIZATION_ID, AccessCardRepository::COL_NAME], [self::ADVANCED_TEST_ID, 'VIP']);

        $accessControlFacade = $this->container->getByType(AccessControlFacade::class);
        $accessControlFacade->changeAccessCardName($organizationIRow, $accessCardIRow, 'TestovaciCard');

        $count = $accessCardRepository->getCountByParameter([AccessCardRepository::COL_ORGANIZATION_ID, AccessCardRepository::COL_NAME], [self::ADVANCED_TEST_ID, 'TestovaciCard']);
        $this->assertSame(1, $count);
        $accessCardIRow->delete();
    }
}
