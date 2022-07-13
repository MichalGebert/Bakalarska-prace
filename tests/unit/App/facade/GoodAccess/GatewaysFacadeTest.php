<?php

namespace unit\App\facade\GoodAccess;

use App;
use App\Facade\Goodaccess\GatewaysFacade;
use App\Model\Account\AccountRepository;
use App\Model\Factory\VpnServer\VpnServerFactory;
use App\Model\General\OrganizationRepository;
use App\Model\General\PortForwardRepository;
use App\Model\Product\ProductAddOnCatalogRepository;
use App\Model\System\ProtocolTypeRepository;
use App\Model\Vpn\VpnServerRepository;
use App\Model\Vpn\VpnUserRepository;
use App\Services\General\LogService;
use Nette\Database\Table\ActiveRow;
use Nette\Security\User;

class GatewaysFacadeTest extends \Codeception\Test\Unit
{

    public function testGetPortForwardById()
    {
        $accountRepository = $this->createMock(AccountRepository::class);
        $logService = $this->createMock(LogService::class);
        $vpnUserRepository = $this->createMock(VpnUserRepository::class);
        $vpnServerRepository = $this->createMock(VpnServerRepository::class);
        $organizationRepository = $this->createMock(OrganizationRepository::class);
        $portForwardRepository = $this->createMock(PortForwardRepository::class);
        $portForwardRepository->method('getById')->willReturn(ActiveRow::class);
        $protocolTypeRepository = $this->createMock(ProtocolTypeRepository::class);
        $productAddOnCatalogRepository = $this->createMock(ProductAddOnCatalogRepository::class);
        $vpnServerFactory = $this->createMock(VpnServerFactory::class);
        $user = $this->createMock(User::class);

        $gatewaysFacade = new GatewaysFacade(
            $accountRepository, $logService, $vpnUserRepository, $vpnServerRepository, $organizationRepository,
            $portForwardRepository, $protocolTypeRepository, $productAddOnCatalogRepository, $vpnServerFactory, $user);

        $result = $gatewaysFacade->getPortForwardById(1);
        $this->assertSame(ActiveRow::class, $result);
    }

    public function testGetVpnServer()
    {
        $this->expectException(\App\Model\NotPermitted::class);
        $vpnServer = $this->createMock(App\Model\Factory\VpnServer\VpnServer::class);

        $accountRepository = $this->createMock(AccountRepository::class);
        $logService = $this->createMock(LogService::class);
        $vpnUserRepository = $this->createMock(VpnUserRepository::class);
        $vpnServerRepository = $this->createMock(VpnServerRepository::class);
        $organizationRepository = $this->createMock(OrganizationRepository::class);
        $portForwardRepository = $this->createMock(PortForwardRepository::class);
        $protocolTypeRepository = $this->createMock(ProtocolTypeRepository::class);
        $productAddOnCatalogRepository = $this->createMock(ProductAddOnCatalogRepository::class);
        $vpnServerFactory = $this->createMock(VpnServerFactory::class);
        $vpnServerFactory->method('create')->willReturn($vpnServer);
        $user = $this->createMock(User::class);

        $gatewaysFacade = new GatewaysFacade(
            $accountRepository, $logService, $vpnUserRepository, $vpnServerRepository, $organizationRepository,
            $portForwardRepository, $protocolTypeRepository, $productAddOnCatalogRepository, $vpnServerFactory, $user);

        $gatewaysFacade->getVpnServer(1);
    }

    public function testGetPortForwardDetail()
    {
        $portForwardIRow = [
            PortForwardRepository::COL_VPN_USER_ID => 1,
            PortForwardRepository::COL_NAME => 'name',
            PortForwardRepository::COL_PUBLIC_PORT => 1,
            PortForwardRepository::COL_PRIVATE_PORT => 1,
            PortForwardRepository::COL_PROTOCOL_TYPE_ID => 1
        ];

        $accountRepository = $this->createMock(AccountRepository::class);
        $logService = $this->createMock(LogService::class);
        $vpnUserRepository = $this->createMock(VpnUserRepository::class);
        $vpnServerRepository = $this->createMock(VpnServerRepository::class);
        $organizationRepository = $this->createMock(OrganizationRepository::class);
        $portForwardRepository = $this->createMock(PortForwardRepository::class);
        $portForwardRepository->method('getByParameter')->willReturn($portForwardIRow);
        $protocolTypeRepository = $this->createMock(ProtocolTypeRepository::class);
        $productAddOnCatalogRepository = $this->createMock(ProductAddOnCatalogRepository::class);
        $vpnServerFactory = $this->createMock(VpnServerFactory::class);
        $user = $this->createMock(User::class);

        $gatewaysFacade = new GatewaysFacade(
            $accountRepository, $logService, $vpnUserRepository, $vpnServerRepository, $organizationRepository,
            $portForwardRepository, $protocolTypeRepository, $productAddOnCatalogRepository, $vpnServerFactory, $user);

        $result = $gatewaysFacade->getPortForwardDetail(1);
        $this->assertSame($portForwardIRow, $result);
    }

    public function testGetAllPortForwardsByVpnServerID()
    {
        $portForwardIRow = [];

        $accountRepository = $this->createMock(AccountRepository::class);
        $logService = $this->createMock(LogService::class);
        $vpnUserRepository = $this->createMock(VpnUserRepository::class);
        $vpnServerRepository = $this->createMock(VpnServerRepository::class);
        $organizationRepository = $this->createMock(OrganizationRepository::class);
        $portForwardRepository = $this->createMock(PortForwardRepository::class);
        $portForwardRepository->method('getAllByParameter')->willReturn($portForwardIRow);
        $protocolTypeRepository = $this->createMock(ProtocolTypeRepository::class);
        $productAddOnCatalogRepository = $this->createMock(ProductAddOnCatalogRepository::class);
        $vpnServerFactory = $this->createMock(VpnServerFactory::class);
        $user = $this->createMock(User::class);

        $gatewaysFacade = new GatewaysFacade(
            $accountRepository, $logService, $vpnUserRepository, $vpnServerRepository, $organizationRepository,
            $portForwardRepository, $protocolTypeRepository, $productAddOnCatalogRepository, $vpnServerFactory, $user);

        $result = $gatewaysFacade->getAllPortForwardsByVpnServerID(1);
        $this->assertSame([], $result);

    }
}
