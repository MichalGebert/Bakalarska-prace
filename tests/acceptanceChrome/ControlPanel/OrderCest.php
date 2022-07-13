<?php

namespace acceptanceChrome\ControlPanel;
use AcceptanceTester;
use Page\AcceptanceChrome\ControlPanel\LeftDesktopMenu;
use Page\AcceptanceChrome\ControlPanel\OrderCheckOut;
use Page\AcceptanceChrome\Login;
use Page\AcceptanceChrome\ControlPanel\OrderPricing;

class OrderCest
{
    /**
     * @throws \Exception
     */
    public function _before(AcceptanceTester $I, Login $loginPage, LeftDesktopMenu $leftDesktopMenu): void
    {
        $I->maximizeWindow();
        $loginPage->login('tester@goodaccess.com', 'password');
        $I->waitPageLoad();
        $leftDesktopMenu->goToOrderAnotherTeam();
        $I->waitPageLoad();
    }

    /**
     * @throws \Exception
     */
    public function startWithGoodAccess(AcceptanceTester $I, OrderPricing $orderPricing, OrderCheckOut $orderCheckOut): void
    {
        $productName = 'Essential';
        $duration = 'Monthly';
//        $I->closeTracy();
        $orderPricing->changeDuration($duration);
        $orderPricing->goWithProduct($productName);
        $orderCheckOut->seeConfiguration($productName, $duration, '10', '1', '0');
        $orderCheckOut->addMembers(20);
        $orderCheckOut->addGateways(1);
        $orderCheckOut->removeMembers(5);
        $orderCheckOut->seeConfiguration($productName, $duration, '25', '2', '0');
        $orderCheckOut->fillBillingInfo('GoodAccess s.r.o.', 'Špitálské náměstí 3188/2b', 'Spain');
        $orderCheckOut->checkPrice('125', '39', null, '198.44', '34.44');
        $orderCheckOut->goBackToPricing();

        $productName = 'Advanced';
        $duration = 'Annually';
        $orderPricing->changeDuration($duration);
        $orderPricing->goWithProduct($productName);
        $orderCheckOut->seeConfiguration($productName, $duration, '10', '1', '1');
        $orderCheckOut->addMembers(20);
        $orderCheckOut->addBranches(1);
        $orderCheckOut->seeConfiguration($productName, $duration, '30', '1', '2');
        $orderCheckOut->fillBillingInfo('GoodAccess s.r.o.', 'Špitálské náměstí 3188/2b', 'United States');
        $orderCheckOut->checkPrice('2,880', '0', '348', '3,228');
        $orderCheckOut->fillBillingInfo('GoodAccess s.r.o.', 'Špitálské náměstí 3188/2b', 'Czech Republic');
        $I->wait(5);
        $orderCheckOut->checkPrice('2,880', '0', '348', '3,905.88', '677.88');
        $orderCheckOut->goBackToPricing();

        $productName = 'Premium';
        $duration = 'Monthly';
        $orderPricing->changeDuration($duration);
        $orderPricing->goWithProduct($productName);
        $orderCheckOut->seeConfiguration($productName, $duration, '20', '2', '5');
        $orderCheckOut->addMembers(10);
        $orderCheckOut->addBranches(1);
        $orderCheckOut->addGateways(1);
        $orderCheckOut->seeConfiguration($productName, $duration, '30', '3', '6');
        $orderCheckOut->fillBillingInfo('GoodAccess s.r.o.', 'Špitálské náměstí 3188/2b', 'United States');
        $orderCheckOut->checkPrice('360', '0', '29', '428');
    }
}
