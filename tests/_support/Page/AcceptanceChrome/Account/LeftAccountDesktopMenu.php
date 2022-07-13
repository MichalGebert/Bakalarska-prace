<?php
namespace Page\AcceptanceChrome\Account;

use Codeception\Util\Locator;
use PHPUnit\Framework\Assert;

class LeftAccountDesktopMenu
{
    public static string $URL = '/';

    private string $accountName          = '.col-lg-3 #accName';
    private string $accountEmail         = '.col-lg-3 #accEmail';
    private string $accountInfo          = '.col-lg-3 #sh_userblock_myprofile';
    private string $billing              = '.col-lg-3 #sh_userblock_payments';
    private string $security             = '.col-lg-3 #sh_userblock_security';
    private string $helpdesk             = '.col-lg-3 #sh_userblock_helpdesk';
    private string $accountAvatar        = '.m-card-profile .m-card-profile__pic-wrapper img';

    private string $changeAccountImg     = '.m-card-profile .kt-avatar__upload';

    private string $backToGoodAccess      = '#m_header .back_to_general';

    private string $notification         = '.toast';
    private string $closeNotification    = '.toast .toast-close-button';


    public static function route($param)
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

    public function viewFullMenu(): void
    {
        $I = $this->acceptanceTester;
        $I->amOnSubdomain('account');
        $I->seeElement($this->accountInfo);
        $I->seeElement($this->billing);
        $I->seeElement($this->security);
        $I->seeElement($this->helpdesk);
    }

    public function goToAccountInfo(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->accountInfo);
        $I->waitPageLoad();
    }

    public function goToBilling(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->billing);
        $I->waitPageLoad();
    }

    public function goToSecurity(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->security);
        $I->waitPageLoad();
    }

    public function goToHelpdesk(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->helpdesk);
        $I->waitPageLoad();
    }

    public function checkNameAndEmail($name, $email): void
    {
        $I = $this->acceptanceTester;
        $I->see($name, $this->accountName);
        $I->see($email, $this->accountEmail);
    }

    public function checkAccountAvatar($avatarID): void
    {
        $I = $this->acceptanceTester;
        $avatarURL = $I->grabAttributeFrom($this->accountAvatar, 'src');
        Assert::assertSame($avatarURL, '/vendor/production/avatars/PNG/256x256/Icon with face-'.$avatarID.'.png');
    }

    /**
     * @throws \Exception
     */
    public function changeAccountAvatar(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->changeAccountImg);
        $I->waitForElementVisible('#imgs_modal');
        $randomImg = random_int(1,70);
        $I->click('.pics:nth-child('.$randomImg.') img');
        $I->wait(2);
        $I->see('Avatar changed successfully!', $this->notification);
        $I->click($this->closeNotification);
        $this->checkAccountAvatar($randomImg < 10 ? '0'.$randomImg : $randomImg);
    }

    public function backToGoodAccess(): void
    {
        $I = $this->acceptanceTester;
        $I->click($this->backToGoodAccess);
        $I->waitPageLoad();
    }

}
