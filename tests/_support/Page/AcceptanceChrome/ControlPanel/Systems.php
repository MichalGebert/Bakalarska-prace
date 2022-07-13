<?php
namespace Page\AcceptanceChrome\ControlPanel;

use Codeception\Util\Locator;
use PHPUnit\Framework\Assert;
use Tracy\Debugger;

class Systems
{
    public static string $URL = '/';

    private string $video                   = '.datatable-no-results .video';
    private string $addSystem               = '.m-portlet__head .m-portlet__nav a.btn';
    private string $searchInput             = '.m-portlet__head .m-portlet__nav .search-datatable';
    private string $secureSystemsLink       = '.m-portlet__head .m-portlet__nav .m-link';
    private string $addSystemModal          = '#add-system-modal';
    private string $editSystemModal         = '#edit-system-modal';

    private string $addSystemName           = '#frm-createSystemsForm-createSystemsForm input[name=name]';
    private string $addSystemTags           = '#frm-createSystemsForm-createSystemsForm input[name=tags]';
    private string $addSystemType           = '#frm-createSystemsForm-createSystemsForm select[name=type]';
    private string $addSystemUrl            = '#frm-createSystemsForm-createSystemsForm input[name=url]';
    private string $addSystemProtocol       = '#frm-createSystemsForm-createSystemsForm select[name=protocol]';
    private string $addSystemHost           = '#frm-createSystemsForm-createSystemsForm input[name=host]';
    private string $addSystemPort           = '#frm-createSystemsForm-createSystemsForm input[name=host]';
    private string $addSystemCustomUri      = '#frm-createSystemsForm-createSystemsForm input[name=customUri]';
    private string $addSystemButton         = '#frm-createSystemsForm-createSystemsForm button[type=submit]';
    private string $addSystemEditImgButton  = '#frm-createSystemsForm-createSystemsForm .edit-img-button';

    private string $editSystemNameButton    = '#frm-editSystemsForm-editSystemsForm .editable-text';
    private string $editSystemName          = '#frm-editSystemsForm-editSystemsForm input[name=name]';
    private string $editSystemButton        = '#frm-editSystemsForm-editSystemsForm button[type=submit]';

    private string $datatable               = '.m-datatable';
    private string $datatableRowName        = '.m-datatable .m-datatable__row .m-card-user__name';
    private string $datatableRowUrl         = '.m-datatable .m-datatable__row .m-card-user__email';
    private string $datatableTag            = '.m-datatable .m-badge--member-tag';
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
    public function addSystem($name, $type, $tag = null, $url = null, $host = null, $uri = null, $protocol = null, $port = null): void
    {
        $I = $this->acceptanceTester;

        $I->click($this->addSystem);
        $I->waitForElementVisible($this->addSystemModal);

        $I->fillField($this->addSystemName, $name);
        $I->selectOption($this->addSystemType, $type);
//        $I->fillField($this->addSystemTags, $tag);

        if($type === 'HTTP' || $type === 'HTTPS'){
            $I->fillField($this->addSystemUrl, $url);
        } else {
            $I->fillField($this->addSystemHost, $host);
            if($uri) $I->fillField($this->addSystemCustomUri, $uri);
            if($protocol) $I->selectOption($this->addSystemProtocol, $protocol);
            if($port) $I->fillField($this->addSystemPort, $port);
        }

        $I->click($this->addSystemEditImgButton);
        $randomImg = random_int(1,300);
        $I->waitForElement('#gallery .avatar:nth-child('.$randomImg.') img', 5);
        $I->scrollTo('#gallery .avatar:nth-child('.$randomImg.') img');
        $I->wait(1);
        $I->click('#gallery .avatar:nth-child('.$randomImg.') img');

        $I->click($this->addSystemButton);

        $I->seeElement(Locator::contains($this->datatableRowName, $name));
        $I->seeElement(Locator::find('div', ['style' => "background-image:url('/vendor/production/system-icons/SVG/".$randomImg.".svg');"]));
//        if($tag) $I->seeElement(Locator::contains($this->datatableTag, $tag));

        if($type === 'HTTP' || $type === 'HTTPS'){
            $I->seeElement(Locator::contains($this->datatableRowUrl, $url));
        }
    }

    /**
     * @throws \Exception
     */
    public function removeSystem($name): void
    {
        $I = $this->acceptanceTester;

        $rowForDelete = Locator::contains($this->datatableRowName, $name);
        $systemID = $I->grabAttributeFrom($rowForDelete, 'id');
        $I->click("//div[contains(concat(' ', @class, ' '), ' datatable-action ')][@id = '".$systemID."']/i[contains(concat(' ', @class, ' '), ' delete-system ')]");

        $I->seeAndConfirmSwal();
        $I->wait(1);
        $I->seeAndConfirmSwal();
    }

    /**
     * @throws \Exception
     */
    public function editSystem($name, $newName): void
    {
        $I = $this->acceptanceTester;

        $rowForEdit = Locator::contains($this->datatableRowName, $name);
        $systemID = $I->grabAttributeFrom($rowForEdit, 'id');
        $I->click("//div[contains(concat(' ', @class, ' '), ' datatable-action ')][@id = '".$systemID."']/i[contains(concat(' ', @class, ' '), ' edit-system ')]");

        $I->waitForElementVisible($this->editSystemModal);

        $rowForEdit = Locator::contains($this->editSystemNameButton, $name);
        $I->click($rowForEdit."/span[contains(concat(' ', @class, ' '), ' edit-section ')]");

        $I->fillField($this->editSystemName, $newName);
        $I->click($this->editSystemButton);

        $I->seeElement(Locator::contains($this->datatableRowName, $newName));
    }

}
