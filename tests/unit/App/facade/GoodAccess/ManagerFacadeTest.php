<?php

use App\Facade\Goodaccess\ManagerFacade;
use App\Model\Account\AccountRepository;
use App\Model\General\CountryRepository;
use App\Model\General\OrganizationManagerRepository;
use Nette\Security\Passwords;
use Codeception\Test\Unit;

class ManagerFacadeTest extends Unit
{

    public function testCheckManagerInvitationToken(): void
    {
        /** Arrange (make mocks) */
        $organizationManagerRepository = $this->getMockBuilder(OrganizationManagerRepository::class)->disableOriginalConstructor()->getMock();
        $organizationManagerRepository->method('getByParameter')->will($this->onConsecutiveCalls('testToken', false));

        $accountRepository = $this->getMockBuilder(AccountRepository::class)->disableOriginalConstructor()->getMock();
        $passwords = $this->getMockBuilder(Passwords::class)->disableOriginalConstructor()->getMock();
        $countryRepository = $this->getMockBuilder(CountryRepository::class)->disableOriginalConstructor()->getMock();

        /** Act */
        $managerFacade = new ManagerFacade($organizationManagerRepository, $accountRepository, $passwords, $countryRepository);
        $token1 = $managerFacade->checkManagerInvitationToken('testToken');
        $token2 = $managerFacade->checkManagerInvitationToken('testTokenNotWorking');

        /** Assert */
        $this->assertSame('testToken', $token1);
        $this->assertFalse($token2);
    }
}
