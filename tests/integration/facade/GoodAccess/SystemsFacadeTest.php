<?php

namespace integration\facade\GoodAccess;

use App\Facade\Goodaccess\SystemsFacade;
use App\Model\General\OrganizationRepository;
use App\Model\General\SystemRepository;
use App\Model\System\SystemPreferenceRepository;
use App\Model\Factory\System\System;
use integration\IntegrationTest;

class SystemsFacadeTest extends IntegrationTest
{

    /**
     * @throws \App\Model\SystemInvalidPortRange
     * @throws \App\Model\SystemPortCollision
     * @throws \App\Model\SystemInvalidUrl
     */
    public function testCreateSystem(): void
    {
        $values = [
            'iconID' => '232',
            'name' => 'TestujuSystem',
            'tags' => '[{"value":"testju"}]',
            'type' => 'FTP',
            'host' => 'seznam.cz',
            'customUri' => 'ftp://seznam.cz:21'
        ];
        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getByParameter(OrganizationRepository::COL_ID, self::PREMIUM_TEST_ID);

        $systemFacade = $this->container->getByType(SystemsFacade::class);
        $systemFacade->createSystem($organizationIRow, $values);

        $systemRepository = $this->container->getByType(SystemRepository::class);
        $systemCount = $systemRepository->getCountByParameter([SystemRepository::COL_ORGANIZATION_ID, SystemRepository::COL_NAME], [self::PREMIUM_TEST_ID, 'TestujuSystem']);
        $this->assertSame(1, $systemCount);
    }

    public function testGetSystemInfo(): void
    {
        $systemRepository = $this->container->getByType(SystemRepository::class);
        $systemIRow = $systemRepository->getByParameter([SystemRepository::COL_ORGANIZATION_ID, SystemRepository::COL_NAME], [self::PREMIUM_TEST_ID, 'TestujuSystem']);

        $expected = [
            'uuid' => $systemIRow[SystemRepository::COL_UUID],
            'name' => 'TestujuSystem',
            'url' => 'ftp://seznam.cz:21',
            'type' => 'FTP',
            'protocol' => 1,
            'host' => 'seznam.cz',
            'port' => '21',
            'isValid' => 1,
            'apiIsDisabled' => null,
            'iconID' => 232,
            'accessCards' => [],
            'tagsArr' => [
                0 => 'testju'
            ]
        ];
        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getByParameter(OrganizationRepository::COL_ID, self::PREMIUM_TEST_ID);

        $systemFacade = $this->container->getByType(SystemsFacade::class);
        $result = $systemFacade->getSystemInfo($systemIRow, $organizationIRow);
        $this->assertSame($expected, $result);
    }

    public function testCheckIfSystemIsInOrganization(): void
    {
        $systemRepository = $this->container->getByType(SystemRepository::class);
        $systemIRow = $systemRepository->getByParameter([SystemRepository::COL_ORGANIZATION_ID, SystemRepository::COL_NAME], [self::PREMIUM_TEST_ID, 'TestujuSystem']);

        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getByParameter(OrganizationRepository::COL_ID, self::PREMIUM_TEST_ID);

        $systemFacade = $this->container->getByType(SystemsFacade::class);
        $this->assertTrue($systemFacade->checkIfSystemIsInOrganization($organizationIRow ,$systemIRow));
    }

    public function testChangeApiIsDisabled(): void
    {
        $systemRepository = $this->container->getByType(SystemRepository::class);
        $systemIRow = $systemRepository->getByParameter([SystemRepository::COL_ORGANIZATION_ID, SystemRepository::COL_NAME], [self::PREMIUM_TEST_ID, 'TestujuSystem']);

        $systemFacade = $this->container->getByType(SystemsFacade::class);
        $this->assertTrue($systemFacade->changeApiIsDisabled($systemIRow));

        $systemIRow = $systemRepository->getByParameter([SystemRepository::COL_ORGANIZATION_ID, SystemRepository::COL_NAME], [self::PREMIUM_TEST_ID, 'TestujuSystem']);
        $this->assertSame(1, $systemIRow[SystemRepository::COL_API_IS_DISABLED]);
    }

    public function testGetActivationCode(): void
    {
        $systemRepository = $this->container->getByType(SystemRepository::class);
        $systemIRow = $systemRepository->getByParameter([SystemRepository::COL_ORGANIZATION_ID, SystemRepository::COL_NAME], [self::PREMIUM_TEST_ID, 'TestujuSystem']);

        $systemFacade = $this->container->getByType(SystemsFacade::class);
        $result = $systemFacade->getActivationCode($systemIRow);

        $this->assertNotNull($result);
    }

    /**
     * @throws \App\Model\NotPermitted
     */
    public function testGetSystem(): void
    {
        $systemRepository = $this->container->getByType(SystemRepository::class);
        $systemIRow = $systemRepository->getByParameter([SystemRepository::COL_ORGANIZATION_ID, SystemRepository::COL_NAME], [self::PREMIUM_TEST_ID, 'TestujuSystem']);

        $systemFacade = $this->container->getByType(SystemsFacade::class);
        $system = $systemFacade->getSystem($systemIRow[SystemRepository::COL_ID]);
        $this->assertInstanceOf(System::class, $system);
    }

    /**
     * @throws \App\Model\SystemInvalidPortRange
     * @throws \App\Model\SystemPortCollision
     * @throws \App\Model\SystemInvalidUrl
     */
    public function testEditSystemsInfo(): void
    {
        $values = [
            'name' => 'TestujuSystem Chnage',
            'customUri' => 'vnc://seznam.cz:21',
            'type' => 'VNC',
            'protocol' => 1,
            'host' => 'seznam.cz',
            'port' => '5900',
            'iconID' => 231,
            'accessCards' => [],
            'tagsArr' => [
                0 => 'testju'
            ]
        ];
        $systemRepository = $this->container->getByType(SystemRepository::class);
        $systemIRow = $systemRepository->getByParameter([SystemRepository::COL_ORGANIZATION_ID, SystemRepository::COL_NAME], [self::PREMIUM_TEST_ID, 'TestujuSystem']);

        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getByParameter(OrganizationRepository::COL_ID, self::PREMIUM_TEST_ID);

        $systemFacade = $this->container->getByType(SystemsFacade::class);
        $this->assertTrue($systemFacade->editSystemsInfo($systemIRow, $organizationIRow, $values));
    }

    public function testRemoveSystem(): void
    {
        $systemRepository = $this->container->getByType(SystemRepository::class);
        $systemIRow = $systemRepository->getByParameter([SystemRepository::COL_ORGANIZATION_ID, SystemRepository::COL_NAME], [self::PREMIUM_TEST_ID, 'TestujuSystem Chnage']);

        $systemFacade = $this->container->getByType(SystemsFacade::class);
        $systemFacade->removeSystem($systemIRow);

        $systemCount = $systemRepository->getCountByParameter([SystemRepository::COL_ORGANIZATION_ID, SystemRepository::COL_NAME], [self::PREMIUM_TEST_ID, 'TestujuSystem Chnage']);
        $this->assertSame(0, $systemCount);
    }

    public function testGetVpnServersIPsByOrganizationID(): void
    {
        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getByParameter(OrganizationRepository::COL_ID, self::PREMIUM_TEST_ID);

        $systemFacade = $this->container->getByType(SystemsFacade::class);
        $result = $systemFacade->getVpnServersIPsByOrganizationID($organizationIRow);
        $this->assertSame([], $result);
    }

    /**
     * @dataProvider checkSystemValidationProvider
     */
    public function testCheckSystemValidation($host, $expected): void
    {
        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getByParameter(OrganizationRepository::COL_ID, self::PREMIUM_TEST_ID);

        $systemFacade = $this->container->getByType(SystemsFacade::class);
        $result = $systemFacade->checkSystemValidation($host, $organizationIRow);
        $this->assertSame($expected, $result);
    }

    /**
     * @return array
     */
    protected function checkSystemValidationProvider(): array
    {
        return [
            ['www.seznam.cz', true],
            ['172.24.0.34', false],
            ['karel.sk', true],
            ['test', false],
        ];
    }

}
