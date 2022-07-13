<?php
namespace Page\AcceptanceChrome\ControlPanel;

use Codeception\Util\Locator;
use PHPUnit\Framework\Assert;
use Tracy\Debugger;

class Members
{
    public static string $URL = '/';

    private string $addDeviceModal              = '#add-device-modal';
    private string $editMemberModal             = '#edit-member-modal';

    private string $addDeviceUsername           = '#frm-addDeviceForm-addDeviceForm input[name=Username]';
    private string $addDevicePassword           = '#frm-addDeviceForm-addDeviceForm input[name=Password]';
    private string $addDeviceConfirmPassword    = '#frm-addDeviceForm-addDeviceForm input[name=Confirm_password]';
    private string $addDeviceButton             = '#frm-addDeviceForm-addDeviceForm button[type=submit]';

    private string $editMemberNameButton    = '#frm-editTeamMemberForm-editTeamMemberForm .editable-text';
    private string $editMemberName          = '#frm-editTeamMemberForm-editTeamMemberForm input[name=name]';
    private string $editMemberButton        = '#frm-editTeamMemberForm-editTeamMemberForm button[type=submit]';

    private string $datatable               = '.m-datatable';
    private string $datatableRowName        = '.m-datatable .m-datatable__row .m-card-user__name';
    private string $datatableRowEmail       = '.m-datatable .m-datatable__row .m-card-user__email';
    private string $datatableAvatar         = '.m-card-user__pic';
    private string $datatableAction         = '.datatable-action';

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
    public function addDevice($username, $password): void
    {
        $I = $this->acceptanceTester;

        $I->click(Locator::contains('a.btn', 'Add Device'));

        $I->waitForElementVisible($this->addDeviceModal);

        $I->fillField($this->addDeviceUsername, $username);
        $I->fillField($this->addDevicePassword, $password);
        $I->fillField($this->addDeviceConfirmPassword, $password);

        $I->click($this->addDeviceButton);

        $I->seeElement(Locator::contains($this->datatableRowName, $username));
        $I->seeElement(Locator::contains($this->datatableRowEmail, $username));
    }

    /**
     * @throws \Exception
     */
    public function removeMember($name): void
    {
        $I = $this->acceptanceTester;

        $rowForDelete = Locator::contains($this->datatableRowName, $name);
        $systemID = $I->grabAttributeFrom($rowForDelete, 'id');
        $I->click("//div[contains(concat(' ', @class, ' '), ' datatable-action ')][@id = '".$systemID."']/i[contains(concat(' ', @class, ' '), ' delete-member ')]");

        $I->seeAndConfirmSwal();
        $I->wait(1);
        $I->seeAndConfirmSwal();

        $I->waitForElementVisible($this->datatable, 5);
    }

    /**
     * @throws \Exception
     */
    public function editMember($name, $newName): void
    {
        $I = $this->acceptanceTester;

        $rowForEdit = Locator::contains($this->datatableRowName, $name);
        $systemID = $I->grabAttributeFrom($rowForEdit, 'id');
        $I->click("//div[contains(concat(' ', @class, ' '), ' datatable-action ')][@id = '".$systemID."']/i[contains(concat(' ', @class, ' '), ' edit-member ')]");

        $I->waitForElementVisible($this->editMemberModal);

        $rowForEdit = Locator::contains($this->editMemberNameButton, $name);
        $I->click($rowForEdit."/span[contains(concat(' ', @class, ' '), ' edit-section ')]");

        $I->fillField($this->editMemberName, $newName);
        $I->click($this->editMemberButton);

        $I->seeElement(Locator::contains($this->datatableRowName, $newName));
    }

}
