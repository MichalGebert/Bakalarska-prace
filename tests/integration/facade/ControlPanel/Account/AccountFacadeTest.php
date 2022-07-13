<?php

namespace integration\facade\ControlPanel\Account;

use App\Facade\ControlPanel\Account\AccountFacade;
use App\Model\Account\AccountRepository;
use integration\IntegrationTest;

class AccountFacadeTest extends IntegrationTest
{

    /**
     * @throws \Exception
     */
    public function testChangeAvatar(): void
    {
        $randomImg = random_int(1,70);
        $avatarID = $randomImg < 10 ? '0'.$randomImg : $randomImg;
        $accountFacade = $this->container->getByType(AccountFacade::class);
        $accountFacade->changeAvatar(self::ACCOUNT_ID, $avatarID);

        $accountRepository = $this->container->getByType(AccountRepository::class);
        $accountIRow = $accountRepository->getById(self::ACCOUNT_ID);

        $this->assertEquals($accountIRow[AccountRepository::COL_AVATAR_ID], $avatarID);
    }


    public function testGetTwoFA(): void
    {
        $accountFacade = $this->container->getByType(AccountFacade::class);
        $result = $accountFacade->getTwoFA(self::ACCOUNT_ID);

        $this->assertSame(0, $result);
    }

    public function testChangeAccountBilling(): void
    {
        $values = [
            'countrySelect' => 216,
            'Email' => '',
            'CompanyName' => 'Goodaccess s.r.o.',
            'PhoneNumber' => '+420730730730',
            'AccountName' => 'Tester GoodAccess',
            'Address' => 'Špitálské náměstí 1b',
            'vat_id' => '',
            'company_id' => ''
        ];

        $accountFacade = $this->container->getByType(AccountFacade::class);
        $accountFacade->changeAccountBilling(self::ACCOUNT_ID, $values);

        $accountRepository = $this->container->getByType(AccountRepository::class);
        $accountIRow = $accountRepository->getById(self::ACCOUNT_ID);

        $this->assertSame('+420730730730', $accountIRow[AccountRepository::COL_PHONE]);
        $this->assertSame('Špitálské náměstí 1b', $accountIRow[AccountRepository::COL_ADDRESS]);
    }

    public function testCheckIfAccountIsClosed(): void
    {
        $accountFacade = $this->container->getByType(AccountFacade::class);
        $result = $accountFacade->checkIfAccountIsClosed(self::ACCOUNT_ID);

        $this->assertFalse($result);
    }
}
