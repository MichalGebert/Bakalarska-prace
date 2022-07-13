<?php

use App\Facade\Goodaccess\TeamMembersFacade;
use App\Model\General\OrganizationRepository;
use App\Model\Vpn\VpnUserRepository;
use integration\IntegrationTest;

class TeamMembersFacadeTest extends IntegrationTest
{

    /**
     * @dataProvider getNumberOfMembersProvider
     */
    public function testGetNumberOfMembers($organizationID, $expected): void
    {
        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getByParameter(OrganizationRepository::COL_ID, $organizationID);

        $teamMembersFacade = $this->container->getByType(TeamMembersFacade::class);
        $result = $teamMembersFacade->getNumberOfMembers($organizationIRow);
        $this->assertSame($expected, $result);
    }

    protected function getNumberOfMembersProvider(): array
    {
        return [
            [self::ESSENTIAL_TEST_ID, [
                'inLicence'         => 10,
                'inOrganization'    => 1,
                'remaining'         => 9
            ]],
            [self::ADVANCED_TEST_ID, [
                'inLicence'         => 10,
                'inOrganization'    => 2,
                'remaining'         => 8
            ]],
            [self::PREMIUM_TEST_ID, [
                'inLicence'         => 20,
                'inOrganization'    => 1,
                'remaining'         => 19
            ]],
        ];
    }

    public function testGetAllVpnUsernamesInOrganization(): void
    {
        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getByParameter(OrganizationRepository::COL_ID, self::ADVANCED_TEST_ID);

        $teamMembersFacade = $this->container->getByType(TeamMembersFacade::class);
        $result = $teamMembersFacade->getAllVpnUsernamesInOrganization($organizationIRow);
        $this->assertSame(['tester@goodaccess.com', 'Test'], $result);
    }

    /**
     * @throws \App\Model\OverIPLimit
     * @throws \App\Model\OverLimit
     * @throws \App\Model\EmailAlreadyUsedException
     *
     */
    public function testCreateTeamMember(): void
    {
        $this->expectWarning();

        $values = [
            'Username' => 'Testovaci',
            'Password' => 'password',
            'accessCircle' => null
        ];
        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getByParameter(OrganizationRepository::COL_ID, self::ADVANCED_TEST_ID);

        $teamMembersFacade = $this->container->getByType(TeamMembersFacade::class);
        $teamMembersFacade->createTeamMember($organizationIRow, $values);

        $vpnUserRepository = $this->container->getByType(VpnUserRepository::class);
        $vpnUserCount = $vpnUserRepository->getCountByParameter([VpnUserRepository::COL_ORGANIZATION_ID, VpnUserRepository::COL_USERNAME], [self::ADVANCED_TEST_ID, 'Testovaci']);
        $this->assertSame(1, $vpnUserCount);
    }

    public function testGetTeamMemberInfo(): void
    {
        $vpnUserRepository = $this->container->getByType(VpnUserRepository::class);
        $vpnUserIRow = $vpnUserRepository->getByParameter([VpnUserRepository::COL_ORGANIZATION_ID, VpnUserRepository::COL_USERNAME], [self::ADVANCED_TEST_ID, 'Testovaci']);

        $expected = [
            'name' => 'Testovaci',
            'username' => 'Testovaci',
            'avatarID' => null,
            'accountID' => null,
            'isBlocked' => 0,
            'isIdp' => 0,
            'password' => true,
            'uuid' => $vpnUserIRow[VpnUserRepository::COL_UUID],
            'twoFa' => 0,
            'isOwner' => false,
            'displaySendConfigEmailBtn' => false,
            'accessCards' => [],
            'tagsArr' => []
        ];
        $organizationRepository = $this->container->getByType(OrganizationRepository::class);
        $organizationIRow = $organizationRepository->getByParameter(OrganizationRepository::COL_ID, self::ADVANCED_TEST_ID);

        $teamMembersFacade = $this->container->getByType(TeamMembersFacade::class);
        $result = $teamMembersFacade->getTeamMemberInfo($vpnUserIRow, $organizationIRow);
        $this->assertSame($expected, $result);
    }

    public function testGetSetupFiles(): void
    {
        $vpnUserRepository = $this->container->getByType(VpnUserRepository::class);
        $vpnUserIRow = $vpnUserRepository->getByParameter([VpnUserRepository::COL_ORGANIZATION_ID, VpnUserRepository::COL_USERNAME], [self::ADVANCED_TEST_ID, 'Testovaci']);

        $teamMembersFacade = $this->container->getByType(TeamMembersFacade::class);
        $result = $teamMembersFacade->getSetupFiles($vpnUserIRow);
        $this->assertNull($result);
    }


    public function testBlockTeamMember(): void
    {
        $vpnUserRepository = $this->container->getByType(VpnUserRepository::class);
        $vpnUserIRow = $vpnUserRepository->getByParameter([VpnUserRepository::COL_ORGANIZATION_ID, VpnUserRepository::COL_USERNAME], [self::ADVANCED_TEST_ID, 'Testovaci']);

        $teamMembersFacade = $this->container->getByType(TeamMembersFacade::class);
        $teamMembersFacade->blockTeamMember($vpnUserIRow);

        $vpnUserIRow = $vpnUserRepository->getByParameter([VpnUserRepository::COL_ORGANIZATION_ID, VpnUserRepository::COL_USERNAME], [self::ADVANCED_TEST_ID, 'Testovaci']);
        $this->assertSame(1, $vpnUserIRow[VpnUserRepository::COL_IS_BLOCKED]);
    }

    public function testUnblockTeamMember(): void
    {
        $vpnUserRepository = $this->container->getByType(VpnUserRepository::class);
        $vpnUserIRow = $vpnUserRepository->getByParameter([VpnUserRepository::COL_ORGANIZATION_ID, VpnUserRepository::COL_USERNAME], [self::ADVANCED_TEST_ID, 'Testovaci']);

        $teamMembersFacade = $this->container->getByType(TeamMembersFacade::class);
        $teamMembersFacade->unblockTeamMember($vpnUserIRow);

        $vpnUserIRow = $vpnUserRepository->getByParameter([VpnUserRepository::COL_ORGANIZATION_ID, VpnUserRepository::COL_USERNAME], [self::ADVANCED_TEST_ID, 'Testovaci']);
        $this->assertSame(0, $vpnUserIRow[VpnUserRepository::COL_IS_BLOCKED]);
    }

    public function testEditTeamMember(): void
    {
        $values = [
            'name' => 'TestovaciMember',
            'password' => '',
            'confirmPassword' => '',
            'tags' => '[{"value":"ahoj"}]'
        ];
        $vpnUserRepository = $this->container->getByType(VpnUserRepository::class);
        $vpnUserIRow = $vpnUserRepository->getByParameter([VpnUserRepository::COL_ORGANIZATION_ID, VpnUserRepository::COL_USERNAME], [self::ADVANCED_TEST_ID, 'Testovaci']);

        $teamMembersFacade = $this->container->getByType(TeamMembersFacade::class);
        $teamMembersFacade->editTeamMember($vpnUserIRow, $values);

        $vpnUserRepository = $this->container->getByType(VpnUserRepository::class);
        $vpnUserCount = $vpnUserRepository->getCountByParameter([VpnUserRepository::COL_ORGANIZATION_ID, VpnUserRepository::COL_NAME], [self::ADVANCED_TEST_ID, 'TestovaciMember']);
        $this->assertSame(1, $vpnUserCount);
    }

    public function testRemoveTeamMember(): void
    {
        $this->expectException(App\Model\NotExistingVpnUser::class);
        $vpnUserRepository = $this->container->getByType(VpnUserRepository::class);
        $vpnUserIRow = $vpnUserRepository->getByParameter([VpnUserRepository::COL_ORGANIZATION_ID, VpnUserRepository::COL_NAME], [self::ADVANCED_TEST_ID, 'TestovaciMember']);

        $teamMembersFacade = $this->container->getByType(TeamMembersFacade::class);
        $vpnUserRepository->removeByParameter([VpnUserRepository::COL_ORGANIZATION_ID, VpnUserRepository::COL_NAME], [self::ADVANCED_TEST_ID, 'TestovaciMember']);
        $teamMembersFacade->removeTeamMember($vpnUserIRow);

        $vpnUserCount = $vpnUserRepository->getCountByParameter([VpnUserRepository::COL_ORGANIZATION_ID, VpnUserRepository::COL_NAME], [self::ADVANCED_TEST_ID, 'TestovaciMember']);
        $this->assertSame(0, $vpnUserCount);
    }
}
