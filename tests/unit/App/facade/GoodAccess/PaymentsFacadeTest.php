<?php

namespace unit\App\facade\GoodAccess;

use App\Facade\Goodaccess\PaymentsFacade;
use App\Model\General\OrganizationRepository;
use App\Model\Payment\PaymentRepository;
use App\Services\General\LogService;
use Nette\Database\Table\ActiveRow;
use Nette\Security\User;

class PaymentsFacadeTest extends \Codeception\Test\Unit
{

    public function testGetPaymentInfo()
    {
        $organizationIRow = [
            OrganizationRepository::COL_LICENCE_ID => 1
        ];
        $paymentIRow = [
            PaymentRepository::COL_LICENCE_ID => 2
        ];

        $organizationRepository = $this->createMock(OrganizationRepository::class);
        $organizationRepository->method('getByParameter')->willReturn($organizationIRow);
        $paymentRepository = $this->createMock(PaymentRepository::class);
        $paymentRepository->method('getByID')->willReturn($paymentIRow);
        $user = $this->createMock(User::class);
        $logService = $this->createMock(LogService::class);

        $paymentFacade = new PaymentsFacade($organizationRepository, $paymentRepository, $logService, $user);

        $result = $paymentFacade->getPaymentInfo(1, 1);
        $this->assertNull($result);
    }

    public function testGetOrganizationById()
    {
        $organizationRepository = $this->createMock(OrganizationRepository::class);
        $organizationRepository->method('getByParameter')->willReturn(ActiveRow::class);
        $paymentRepository = $this->createMock(PaymentRepository::class);
        $user = $this->createMock(User::class);
        $logService = $this->createMock(LogService::class);

        $paymentFacade = new PaymentsFacade($organizationRepository, $paymentRepository, $logService, $user);

        $organizationIRow = $paymentFacade->getOrganizationById(1);
        $this->assertSame(ActiveRow::class, $organizationIRow);
    }

    public function testCheckPaymentIdForAccount()
    {
        $organizationIRow = [
            OrganizationRepository::COL_LICENCE_ID => 1
        ];
        $paymentIRow = [
            PaymentRepository::COL_ID => 1
        ];
        $paymentIRow2 = null;

        $organizationRepository = $this->createMock(OrganizationRepository::class);
        $organizationRepository->method('getByParameter')->willReturn($organizationIRow);
        $paymentRepository = $this->createMock(PaymentRepository::class);
        $paymentRepository->method('getByParameter')->will($this->onConsecutiveCalls($paymentIRow, $paymentIRow2));
        $user = $this->createMock(User::class);
        $logService = $this->createMock(LogService::class);

        $paymentFacade = new PaymentsFacade($organizationRepository, $paymentRepository, $logService, $user);
        $result = $paymentFacade->checkPaymentIdForAccount(1,1);
        $this->assertTrue($result);
        $result = $paymentFacade->checkPaymentIdForAccount(1,1);
        $this->assertFalse($result);
    }
}
