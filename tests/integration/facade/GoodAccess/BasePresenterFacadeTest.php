<?php

use App\Facade\Goodaccess\BasePresenterFacade;
use App\Model\General\LicenceRepository;
use App\Model\General\OrganizationRepository;
use App\Model\Factory\Account\AccountFactory;
use App\Model\Account\AccountRepository;
use integration\IntegrationTest;

class BasePresenterFacadeTest extends IntegrationTest
{

    /**
     * @throws Exception
     */
    public function testGetOrganizationExpireStatus(): void
    {
        $accountRepository = $this->container->getByType(AccountRepository::class);
        $accountIRow = $accountRepository->getByParameter(AccountRepository::COL_ID, self::ACCOUNT_ID);
        $organizationIRows = $accountIRow->related(OrganizationRepository::TABLE_NAME)
            ->where(OrganizationRepository::TABLE_NAME . '.' . OrganizationRepository::COL_ARCHIVED_AT, null)
            ->where(LicenceRepository::TABLE_NAME . '.' . LicenceRepository::COL_PRODUCT_CATALOG_ID . ' != ?', 27)
            ->order(OrganizationRepository::TABLE_NAME . '.' . OrganizationRepository::COL_TIMESTAMP . ' ASC');

        $basePresenterFacade = $this->container->getByType(BasePresenterFacade::class);
        $result = $basePresenterFacade->getOrganizationExpireStatus($organizationIRows);
        $this->assertSame(['expiring' => [], 'canceling' => []], $result);
    }
}
