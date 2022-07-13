<?php

namespace integration;

use Codeception\TestCase\Test;
use Codeception\Util\Fixtures;
use Nette\DI\Container;
use Nette\Security\User;

abstract class IntegrationTest extends Test {

    /** @var Container */
    protected Container $container;

    public const
        /** Login details */
        ACCOUNT_ID = 1,
        USERNAME = 'tester@goodaccess.com',
        PASSWORD = 'password',
        MANAGER_ACCOUNT_ID = 2,
        /** Vpn users */
        ESSENTIAL_VPN_USER = 1,
        /** Organizations ID */
        FREEMIUM_TEST_ID   = 1,
        FREE_TRIAL_TEST_ID = 2,
        ESSENTIAL_TEST_ID  = 3,
        ADVANCED_TEST_ID   = 4,
        PREMIUM_TEST_ID    = 5,
        TESTER_ID          = 6,
        /** Product catalog ID */
        ESSENTIAL_MONTH_ID      = 1,
        ESSENTIAL_ANNUALLY_ID   = 2,
        ADVANCED_MONTH_ID       = 3,
        ADVANCED_ANNUALLY_ID    = 4,
        PREMIUM_MONTH_ID        = 5,
        PREMIUM_ANNUALLY_ID     = 6;

    protected function _before(): void
    {
        parent::_before();
        $this->container = Fixtures::get('container');
    }

    /**
     * @throws \Nette\Security\AuthenticationException
     */
    protected function login($username = null, $password = null): void
    {
        $user = $this->container->getByType(User::class);
        if($username && $password){
            $user->login($username, $password);
        } else {
            $user->login(self::USERNAME, self::PASSWORD);
        }
    }
}
