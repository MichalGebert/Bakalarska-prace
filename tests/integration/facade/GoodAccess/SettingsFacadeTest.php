<?php

use App\Facade\Goodaccess\SettingsFacade;
use App\Model\General\CustomDnsFilteringRepository;
use App\Model\General\OrganizationRepository;
use App\Model\General\CustomDnsRepository;
use App\Model\General\OrganizationManagerRepository;
use App\Model\Factory\Manager\Manager;
use integration\IntegrationTest;

class SettingsFacadeTest extends IntegrationTest
{

    public function testGetAllManagers(): void
    {
        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getByParameter(OrganizationRepository::COL_ID, self::PREMIUM_TEST_ID);

        $settingsFacade = $this->container->getByType(SettingsFacade::class);
        $result = $settingsFacade->getAllManagers($organizationIRow);
        $this->assertSame(['manager@goodaccess.com', 'tester@goodaccess.com'], $result);
    }

    public function testGetIfUserIsManager(): void
    {
        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getByParameter(OrganizationRepository::COL_ID, self::PREMIUM_TEST_ID);

        $settingsFacade = $this->container->getByType(SettingsFacade::class);
        $result = $settingsFacade->getIfUserIsManager($organizationIRow, self::ACCOUNT_ID);
        $this->assertFalse($result);
    }

    /**
     * @throws \App\Model\OverLimitDnsRecords
     */
    public function testCreateCustomDnsFiltering(): void
    {
        $query = [
            'hostnameFiltering' => '[{"value":"karel.sk"}]',
            'includeAllSubdomains' => true
        ];
        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getByParameter(OrganizationRepository::COL_ID, self::PREMIUM_TEST_ID);

        $settingsFacade = $this->container->getByType(SettingsFacade::class);
        $result = $settingsFacade->createCustomDnsFiltering($query, $organizationIRow);
        $this->assertTrue($result);
    }

    public function testGetCustomDNSFilteringJSON(): void
    {
        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getByParameter(OrganizationRepository::COL_ID, self::PREMIUM_TEST_ID);

        $settingsFacade = $this->container->getByType(SettingsFacade::class);
        $result = $settingsFacade->getCustomDNSFilteringJSON(1, 1, 100, '', null, $organizationIRow);
        $this->assertSame('facebook.com', $result['data'][1]['hostname']);
        $this->assertSame('*.karel.sk, karel.sk', $result['data'][2]['hostname']);
        $this->assertSame('*.seznam.cz, seznam.cz', $result['data'][0]['hostname']);
    }

    public function testCheckIfDnsIsSaved(): void
    {
        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getByParameter(OrganizationRepository::COL_ID, self::PREMIUM_TEST_ID);

        $settingsFacade = $this->container->getByType(SettingsFacade::class);
        $result = $settingsFacade->checkIfDnsIsSaved($organizationIRow);
        $this->assertFalse($result);
    }

    public function testRemoveCustomDnsFiltering(): void
    {
        $idArr = [];
        $customDnsFilteringRepository = $this->container->getByType(CustomDnsFilteringRepository::class);
        $customDnsFilteringIRows = $customDnsFilteringRepository->getAllByParameter([CustomDnsFilteringRepository::COL_ORGANIZATION_ID, CustomDnsFilteringRepository::COL_HOSTNAME.' LIKE ?'], [self::PREMIUM_TEST_ID, '%karel.sk%']);
        foreach ($customDnsFilteringIRows as $customDnsFilteringIRow){
            $idArr[] = $customDnsFilteringIRow[CustomDnsFilteringRepository::COL_UUID];
        }

        $settingsFacade = $this->container->getByType(SettingsFacade::class);
        $settingsFacade->removeCustomDnsFiltering($idArr);

        $dnsFilteringCount = $customDnsFilteringRepository->getCountByParameter([
            CustomDnsFilteringRepository::COL_ORGANIZATION_ID,
            CustomDnsFilteringRepository::COL_HOSTNAME.' LIKE ?',
            CustomDnsFilteringRepository::COL_WAITING_FOR_REMOVE
        ], [self::PREMIUM_TEST_ID, '%karel.sk%', 1]);
        $this->assertSame(2, $dnsFilteringCount);

        $customDnsFilteringRepository->removeByParameter([CustomDnsFilteringRepository::COL_ORGANIZATION_ID, CustomDnsFilteringRepository::COL_HOSTNAME.' LIKE ?'], [self::PREMIUM_TEST_ID, '%karel.sk%']);
    }

    public function testGetAllDnsFilteringHostnames(): void
    {
        $resultArr = ['seznam.cz', '*.seznam.cz', 'facebook.com'];
        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getByParameter(OrganizationRepository::COL_ID, self::PREMIUM_TEST_ID);

        $settingsFacade = $this->container->getByType(SettingsFacade::class);
        $result = $settingsFacade->getAllDnsFilteringHostnames($organizationIRow);
        $this->assertSame($resultArr, $result);
    }

    public function testGetVpnServersForOrganization(): void
    {
        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getByParameter(OrganizationRepository::COL_ID, self::PREMIUM_TEST_ID);

        $settingsFacade = $this->container->getByType(SettingsFacade::class);
        $result = $settingsFacade->getVpnServersForOrganization($organizationIRow);
        $this->assertSame([], $result);
    }

    /**
     * @throws \App\Model\OverLimitDnsRecords
     */
    public function testCreateCustomDns(): void
    {
        $query = [
            'hostname' => 'kamera.kancl',
            'ip' => '192.168.223.18'
        ];

        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getByParameter(OrganizationRepository::COL_ID, self::PREMIUM_TEST_ID);

        $settingsFacade = $this->container->getByType(SettingsFacade::class);
        $result = $settingsFacade->createCustomDns($query, $organizationIRow);
        $this->assertTrue($result);
    }

    public function testRemoveCustomDns(): void
    {
        $idArr = [];
        $customDnsRepository = $this->container->getByType(CustomDnsRepository::class);
        $customDnsIRows = $customDnsRepository->getAllByParameter([CustomDnsRepository::COL_ORGANIZATION_ID, CustomDnsRepository::COL_HOSTNAME], [self::PREMIUM_TEST_ID, 'kamera.kancl']);
        foreach ($customDnsIRows as $customDnsIRow){
            $idArr[] = $customDnsIRow[CustomDnsRepository::COL_UUID];
        }

        $settingsFacade = $this->container->getByType(SettingsFacade::class);
        $settingsFacade->removeCustomDns($idArr);

        $dnsFilteringCount = $customDnsRepository->getCountByParameter([
            CustomDnsFilteringRepository::COL_ORGANIZATION_ID,
            CustomDnsFilteringRepository::COL_HOSTNAME,
            CustomDnsFilteringRepository::COL_WAITING_FOR_REMOVE
        ], [self::PREMIUM_TEST_ID, 'kamera.kancl', 1]);
        $this->assertSame(1, $dnsFilteringCount);

        $customDnsRepository->removeByParameter([CustomDnsRepository::COL_ORGANIZATION_ID, CustomDnsRepository::COL_HOSTNAME], [self::PREMIUM_TEST_ID, 'kamera.kancl']);
    }

    public function testGetManagerInfo(): void
    {
        $organizationManagerRepository = $this->container->getByType(OrganizationManagerRepository::class);
        $managerIRow = $organizationManagerRepository->getByParameter([OrganizationManagerRepository::COL_ORGANIZATION_ID, OrganizationManagerRepository::COL_ACCOUNT_ID], [self::PREMIUM_TEST_ID, self::MANAGER_ACCOUNT_ID]);

        $settingsFacade = $this->container->getByType(SettingsFacade::class);
        $result = $settingsFacade->getManagerInfo($managerIRow);
        $this->assertContains('manager@goodaccess.com', $result);
        $this->assertContains('Viewer', $result);
        $this->assertContains(1, $result);
    }

    /**
     * @throws \App\Model\NotPermitted
     */
    public function testGetManager(): void
    {
        $organizationManagerRepository = $this->container->getByType(OrganizationManagerRepository::class);
        $managerIRow = $organizationManagerRepository->getByParameter([OrganizationManagerRepository::COL_ORGANIZATION_ID, OrganizationManagerRepository::COL_ACCOUNT_ID], [self::PREMIUM_TEST_ID, self::MANAGER_ACCOUNT_ID]);

        $settingsFacade = $this->container->getByType(SettingsFacade::class);
        $result = $settingsFacade->getManager($managerIRow[OrganizationManagerRepository::COL_UUID]);
        $this->assertInstanceOf(Manager::class, $result);
    }

    public function testGetIfTwoFactor(): void
    {
        $returnArr = [
            OrganizationRepository::COL_TWO_FA_APPLICATION_LOGIN => 0,
            OrganizationRepository::COL_TWO_FA_EACH_CONNECTION => 0
        ];

        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getByParameter(OrganizationRepository::COL_ID, self::PREMIUM_TEST_ID);

        $settingsFacade = $this->container->getByType(SettingsFacade::class);
        $result = $settingsFacade->getIfTwoFactor($organizationIRow[OrganizationManagerRepository::COL_UUID]);
        $this->assertSame($returnArr, $result);

    }

    public function testCheckIfSAML(): void
    {
        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getByParameter(OrganizationRepository::COL_ID, self::PREMIUM_TEST_ID);

        $settingsFacade = $this->container->getByType(SettingsFacade::class);
        $result = $settingsFacade->checkIfSAML($organizationIRow);
        $this->assertSame([], $result);
    }
}
