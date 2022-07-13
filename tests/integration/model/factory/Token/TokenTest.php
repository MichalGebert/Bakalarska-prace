<?php

namespace integration\model\factory\Token;

use App\Model\Factory\Token\TokenFactory;
use App\Model\Factory\VpnUser\VpnUser;
use App\Model\Factory\VpnUser\VpnUserFactory;
use App\Model\Vpn\VpnUserTokenRepository;
use integration\IntegrationTest;

class TokenTest extends IntegrationTest
{

    /**
     * @throws \App\Model\NotExistingVpnUser
     */
    public function testInsert(): void
    {
        $tokenFactory = $this->container->getByType(TokenFactory::class);
        $vpnUserFactory = $this->container->getByType(VpnUserFactory::class);
        $vpnUser = $vpnUserFactory->create(self::ESSENTIAL_VPN_USER);
        $token = $tokenFactory->create($vpnUser);
        $token->insert(3);

        $vpnUserTokenRepository = $this->container->getByType(VpnUserTokenRepository::class);
        $vpnUserTokenIRow = $vpnUserTokenRepository->getByParameter([VpnUserTokenRepository::COL_TOKEN_TYPE_ID, VpnUserTokenRepository::COL_VPN_USER_ID], [3, self::ESSENTIAL_VPN_USER]);
        $this->assertSame('windows', $vpnUserTokenIRow[VpnUserTokenRepository::COL_OS]);
    }

    /**
     * @throws \App\Model\TokenNotExist
     * @throws \App\Model\TokenExpired
     * @throws \App\Model\NotExistingVpnUser
     */
    public function testCheck(): void
    {
        $vpnUserTokenRepository = $this->container->getByType(VpnUserTokenRepository::class);
        $vpnUserTokenIRow = $vpnUserTokenRepository->getByParameter([VpnUserTokenRepository::COL_TOKEN_TYPE_ID, VpnUserTokenRepository::COL_VPN_USER_ID], [3, self::ESSENTIAL_VPN_USER]);

        $tokenFactory = $this->container->getByType(TokenFactory::class);
        $vpnUserFactory = $this->container->getByType(VpnUserFactory::class);
        $vpnUser = $vpnUserFactory->create(self::ESSENTIAL_VPN_USER);
        $token = $tokenFactory->create($vpnUser);
        $this->assertTrue($token->check($vpnUserTokenIRow[VpnUserTokenRepository::COL_TOKEN]));
        $vpnUserTokenIRow->delete();
    }
}
