<?php
namespace Page\AcceptanceChrome\ControlPanel;

use Codeception\Util\Locator;
use PHPUnit\Framework\Assert;
use Tracy\Debugger;

class AccessControl
{
    public static string $URL = '/';

    private string $pageHeaderName          = '.m-subheader h3';
    private string $video                   = '.datatable-no-results .video';
    private string $activateAccessControl   = '.datatable-no-results .activate-access-control';
    private string $addAccessCardModal      = '#add-access-card-modal';

    private string $accessCard              = '.access-card';
    private string $accessCardName          = '.access-card h3';
    private string $accessCardItem          = '.access-card .item-avatars .m-card-user__pic';
    private string $editAccessCard          = 'edit-access-card';

    private string $addAccessCard           = '.row-tools a.btn';

    private string $editAccessCardModal     = '#edit-access-card-modal';
    private string $removeAccessCardButton  = '#edit-access-card-modal .remove-access-card';
    private string $membersTab              = '#edit-access-card-modal .members-tab';
    private string $systemsTab              = '#edit-access-card-modal .systems-tab';
    private string $branchesTab             = '#edit-access-card-modal .branches-tab';
    private string $membersDatatableRow     = '#team-members-access-control-datatable .m-datatable__row';
    private string $saveAccessCard          = '#edit-access-card-modal .save-access-control';

    private string $addAccessCardName       = '#frm-createAccessCircleForm-createAccessCircleForm input[name=Name]';
    private string $addAccessCardButton     = '#frm-createAccessCircleForm-createAccessCircleForm button[type=submit]';

    public static function route($param): string
    {
        return static::$URL.$param;
    }

    /**
     * @var \AcceptanceTester;
     */
    protected \AcceptanceTester $acceptanceTester;

    public function __construct(\AcceptanceTester $I)
    {
        $this->acceptanceTester = $I;
    }

    /**
     * @throws \Exception
     */
    public function activateAccessControl(): void
    {
        $I = $this->acceptanceTester;
        $I->waitForElementVisible($this->video);
        $I->seeElement($this->video);
        $I->click($this->activateAccessControl);
        $I->waitForElementVisible($this->pageHeaderName);
        $I->see('VIP', $this->accessCardName);
    }

    public function seeAccessCardItem($accessCardName, $accessCardItemName): void
    {
        $I = $this->acceptanceTester;
        $accessCard = Locator::contains($this->accessCard, $accessCardName);
        $I->seeElement(Locator::find($accessCard.'/descendant-or-self::*/*', ['data-original-title' => $accessCardItemName]));
    }

    /**
     * @throws \Exception
     */
    public function addAccessCard($name): void
    {
        $I = $this->acceptanceTester;

        $I->dontSeeElement($this->video);

        $I->click($this->addAccessCard);
        $I->waitForElementVisible($this->addAccessCardModal);
        $I->waitForElementVisible($this->addAccessCardName);

        $I->fillField($this->addAccessCardName, $name);
        $I->click($this->addAccessCardButton);
        $I->seeAndCloseToastSuccessNotification();

        $I->see($name, $this->accessCardName);
    }

    /**
     * @throws \Exception
     */
    public function removeAccessCard($accessCardName): void
    {
        $I = $this->acceptanceTester;

        $this->openEditAccessCard($accessCardName);
        $I->click($this->removeAccessCardButton);
        $I->seeAndConfirmSwal();
        $I->seeAndCloseToastSuccessNotification();
        $I->waitForElementVisible($this->pageHeaderName);
        $I->dontsee($accessCardName, $this->accessCardName);
    }

    /**
     * @throws \Exception
     */
    public function openEditAccessCard($accessCardName): void
    {
        $I = $this->acceptanceTester;

        $I->see($accessCardName, $this->accessCardName);
        $accessCard = Locator::contains($this->accessCard, $accessCardName);
        $I->click($accessCard."/descendant-or-self::*/*[@class and contains(concat(' ', normalize-space(@class), ' '), ' ".$this->editAccessCard." ')][contains(., 'Edit')]");
        $I->waitForElementVisible($this->editAccessCardModal);
    }

    /**
     * @throws \Exception
     */
    public function editAccessCardMembers($accessCardName, array $accessCardMembers): void
    {
        $I = $this->acceptanceTester;

        $this->openEditAccessCard($accessCardName);
        $I->click($this->membersTab);

        foreach ($accessCardMembers as $accessCardMember){
            $rowForEdit = Locator::contains($this->membersDatatableRow, $accessCardMember['name']);
            if($accessCardMember['add'] === true){
                $I->checkOption($rowForEdit."/descendant-or-self::*/*[@class and contains(concat(' ', normalize-space(@class), ' '), ' members-checkbox ')]");
            } else {
                $I->uncheckOption($rowForEdit."/descendant-or-self::*/*[@class and contains(concat(' ', normalize-space(@class), ' '), ' members-checkbox ')]");
            }
        }
        $I->click($this->saveAccessCard);
    }

}
