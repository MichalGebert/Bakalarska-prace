<?php
namespace Page\AcceptanceChrome\Account;

use Codeception\Util\Locator;

class Security
{
    public static string $URL = '/';

    private string $TwoFACheckBox = 'input[name=allow2FA]';

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

    public function checkEnabledTwoFA(): void
    {
        $I = $this->acceptanceTester;
        $I->seeCheckboxIsChecked($this->TwoFACheckBox);
    }

    public function checkNotEnabledTwoFA(): void
    {
        $I = $this->acceptanceTester;
        $I->dontSeeCheckboxIsChecked($this->TwoFACheckBox);
    }

}
