<?php

namespace unit;

use App\Facade\PartnerPanel\SettingsFacade;
use App\Model\Account\AccountRepository;
use App\Model\ASync\ASyncHubSpotMailer;
use App\Model\Factory\Account\Account;
use App\Model\General\PartnerProgramManagerRepository;
use App\Model\General\PartnerProgramRepository;
use App\Model\General\PartnerTypeRepository;
use App\Services\General\LogService;
use DG\BypassFinals;
use Nette\Application\LinkGenerator;
use Nette\Security\User;
use Codeception\Test\Unit;

class SettingsFacadeTest extends Unit
{

    protected function setUp(): void
    {
       BypassFinals::enable();
    }

    public function testGetManagerEmailsArr(): void
    {
        $partnerProgramManagerIRows = [
            [
                AccountRepository::TABLE_NAME => [
                    AccountRepository::COL_EMAIL => 'tester@goodaccess.com'
                ]
            ]
        ];

        $partnerProgramManagerRepository = $this->createMock(PartnerProgramManagerRepository::class);
        $partnerProgramManagerRepository->method('getAllByParameter')->willReturn($partnerProgramManagerIRows);

        $accountRepository = $this->createMock(AccountRepository::class);
        $logService = $this->createMock(LogService::class);
        $user = $this->createMock(User::class);
        $ASyncHubSpotMailer = $this->createMock(ASyncHubSpotMailer::class);
        $linkGenerator = $this->createMock(LinkGenerator::class);

        $settingsFacade = new SettingsFacade($accountRepository, $partnerProgramManagerRepository, $logService, $user, $ASyncHubSpotMailer, $linkGenerator);
        $returnArr = $settingsFacade->getManagerEmailsArr([PartnerProgramRepository::COL_ID => 1]);
        $this->assertSame(['tester@goodaccess.com'], $returnArr);

    }

    /**
     * @throws \App\Model\NoRecipient
     */
    public function testInvitePartnerManager(): void
    {
        $this->expectException(\Exception::class);

        $partnerProgramManagerRepository = $this->createMock(PartnerProgramManagerRepository::class);
        $accountRepository = $this->createMock(AccountRepository::class);
        $logService = $this->createMock(LogService::class);
        $user = $this->createMock(User::class);
        $ASyncHubSpotMailer = $this->createMock(ASyncHubSpotMailer::class);
        $linkGenerator = $this->createMock(LinkGenerator::class);
        $account = $this->getMockBuilder(Account::class)->disableOriginalConstructor()->getMock();
        $account->iRow[AccountRepository::COL_ID] = 2;

        $settingsFacade = new SettingsFacade($accountRepository, $partnerProgramManagerRepository, $logService, $user, $ASyncHubSpotMailer, $linkGenerator);
        $settingsFacade->invitePartnerManager([PartnerProgramRepository::COL_ACCOUNT_ID => 1], null, $account);
    }

    public function testRemoveManager(): void
    {
        $partnerProgramManagerIRow = null;

        $partnerProgramManagerRepository = $this->createMock(PartnerProgramManagerRepository::class);
        $partnerProgramManagerRepository->method('getByParameter')->willReturn($partnerProgramManagerIRow);
        $accountRepository = $this->createMock(AccountRepository::class);
        $logService = $this->createMock(LogService::class);
        $user = $this->createMock(User::class);
        $ASyncHubSpotMailer = $this->createMock(ASyncHubSpotMailer::class);
        $linkGenerator = $this->createMock(LinkGenerator::class);

        $settingsFacade = new SettingsFacade($accountRepository, $partnerProgramManagerRepository, $logService, $user, $ASyncHubSpotMailer, $linkGenerator);
        $return = $settingsFacade->removeManager(5);
        $this->assertFalse($return);
    }

    public function testResendInvitationToManager(): void
    {
        $partnerManagerIRow = [
            PartnerProgramManagerRepository::COL_ACCEPTED => false,
            PartnerProgramRepository::TABLE_NAME => [
                PartnerProgramRepository::COL_ACCOUNT_ID => 1,
                PartnerTypeRepository::TABLE_NAME => [
                    PartnerTypeRepository::COL_NAME => 'test'
                ],
                AccountRepository::TABLE_NAME => [
                    AccountRepository::COL_NAME => 'Tester'
                ]
            ],
            AccountRepository::TABLE_NAME => [
                AccountRepository::COL_EMAIL => 'tester@goodaccess.com',
                AccountRepository::COL_ID => 1
            ],
            PartnerProgramManagerRepository::COL_TOKEN => 'token'
        ];
        $partnerProgramManagerRepository = $this->getMockBuilder(PartnerProgramManagerRepository::class)->disableOriginalConstructor()->getMock();
        $partnerProgramManagerRepository->method('getByParameter')->will($this->onConsecutiveCalls($partnerManagerIRow, false));
        $accountRepository = $this->createMock(AccountRepository::class);
        $logService = $this->createMock(LogService::class);
        $user = $this->createMock(User::class);
        $ASyncHubSpotMailer = $this->createMock(ASyncHubSpotMailer::class);
        $linkGenerator = $this->createMock(LinkGenerator::class);

        $settingsFacade = new SettingsFacade($accountRepository, $partnerProgramManagerRepository, $logService, $user, $ASyncHubSpotMailer, $linkGenerator);

        $result1 = $settingsFacade->resendInvitationToManager(1);
        $result2 = $settingsFacade->resendInvitationToManager(1);

        $this->assertTrue($result1);
        $this->assertFalse($result2);
    }
}
