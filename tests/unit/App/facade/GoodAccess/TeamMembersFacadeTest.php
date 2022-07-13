<?php

namespace unit\App\facade\GoodAccess;

use App\Facade\Goodaccess\TeamMembersFacade;
use App\Model\Access\AccessCardSystemRepository;
use App\Model\Access\AccessCircleSystemRepository;
use App\Model\ASync\ASyncHubSpotMailer;
use App\Model\Authorizator\BlockAuthorizator;
use App\Model\Factory\Organization\OrganizationFactory;
use App\Model\Factory\VpnUser\VpnUserFactory;
use App\Model\General\AccessCardVpnUserRepository;
use App\Model\General\AccessCircleVpnUserRepository;
use App\Model\General\OrganizationManagerRepository;
use App\Model\Product\ProductAddOnCatalogRepository;
use App\Model\Resource\TagRepository;
use App\Model\Vpn\VpnUserRepository;
use App\Model\Vpn\VpnUserTagRepository;
use App\Model\Vpn\VpnUserTokenRepository;
use App\Services\Config\ConfigService;
use App\Services\General\LogService;
use App\Services\Organization\TeamMemberService;
use App\Services\Token\VpnUserTokenService;
use DG\BypassFinals;
use Nette\Application\LinkGenerator;
use Nette\Security\Passwords;
use Nette\Security\User;
use PHPUnit\Framework\TestCase;

/**
 * Class TeamMembersFacadeTest
 * @package App\Facade\Goodaccess
 * @covers \App\Facade\Goodaccess\TeamMembersFacade
 */
class TeamMembersFacadeTest extends TestCase
{
    /**
     * @covers \App\Facade\Goodaccess\TeamMembersFacade::getTeamMemberVPNCredentials
     */
    public function testGetTeamMemberVPNCredentials(): void
    {
        BypassFinals::enable();
        $arg1Mock = $this->getMockBuilder(VpnUserFactory::class)->disableOriginalConstructor()->getMock();
        $arg2Mock = $this->getMockBuilder(VpnUserRepository::class)->disableOriginalConstructor()->getMock();
        $arg3Mock = $this->getMockBuilder(ProductAddOnCatalogRepository::class)->disableOriginalConstructor()->getMock();
        $arg4Mock = $this->getMockBuilder(BlockAuthorizator::class)->disableOriginalConstructor()->getMock();
        $arg5Mock = $this->getMockBuilder(VpnUserTagRepository::class)->disableOriginalConstructor()->getMock();
        $arg6Mock = $this->getMockBuilder(OrganizationManagerRepository::class)->disableOriginalConstructor()->getMock();
        $arg7Mock = $this->getMockBuilder(AccessCardVpnUserRepository::class)->disableOriginalConstructor()->getMock();
        $arg8Mock = $this->getMockBuilder(AccessCardSystemRepository::class)->disableOriginalConstructor()->getMock();
        $arg9Mock = $this->getMockBuilder(VpnUserTokenRepository::class)->disableOriginalConstructor()->getMock();
        $arg10Mock = $this->getMockBuilder(TagRepository::class)->disableOriginalConstructor()->getMock();
        $arg11Mock = $this->getMockBuilder(TeamMemberService::class)->disableOriginalConstructor()->getMock();
        $arg12Mock = $this->getMockBuilder(ConfigService::class)->disableOriginalConstructor()->getMock();
        $arg13Mock = $this->getMockBuilder(Passwords::class)->disableOriginalConstructor()->getMock();
        $arg14Mock = $this->getMockBuilder(LogService::class)->disableOriginalConstructor()->getMock();
        $arg15Mock = $this->getMockBuilder(OrganizationFactory::class)->disableOriginalConstructor()->getMock();
        $arg16Mock = $this->getMockBuilder(User::class)->disableOriginalConstructor()->getMock();
        $arg17Mock = $this->getMockBuilder(VpnUserTokenService::class)->disableOriginalConstructor()->getMock();
        $arg18Mock = $this->getMockBuilder(ASyncHubSpotMailer::class)->disableOriginalConstructor()->getMock();
        $arg19Mock = $this->getMockBuilder(LinkGenerator::class)->disableOriginalConstructor()->getMock();

        $teamMemberFacade = new TeamMembersFacade($arg1Mock, $arg2Mock, $arg3Mock, $arg4Mock, $arg5Mock, $arg6Mock, $arg7Mock, $arg8Mock, $arg9Mock,
            $arg10Mock, $arg11Mock, $arg12Mock, $arg13Mock, $arg14Mock, $arg15Mock, $arg16Mock, $arg17Mock, $arg18Mock, $arg19Mock);

        $result = $teamMemberFacade->getTeamMemberVPNCredentials([
            'vpn_username'      => 'besdfget9@goodaccess.com',
            'vpn_password'      => '$2y$10$40oKpTlosmMsdf4g65dsf4g9841Izahnr3Euo0T1w.a',
            'username'          => 'tester@goodaccess.com',
            'organization_id'   => 1
        ]);

        $this->assertSame([
            'vpn_username'  => 'p3o2gs@goodaccess.com',
            'vpn_password'  => '$2y$1a98fd765as4df56JIHOTLyIEbDvqsR4Vc1Izahnr3Euo0T1w.a',
            'username'      => 'tester@goodaccess.com'
        ],$result);
    }
}
