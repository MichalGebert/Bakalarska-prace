<?php


/**
 * Inherited Methods
 * @method void wantToTest($text)
 * @method void wantTo($text)
 * @method void execute($callable)
 * @method void expectTo($prediction)
 * @method void expect($prediction)
 * @method void amGoingTo($argumentation)
 * @method void am($role)
 * @method void lookForwardTo($achieveValue)
 * @method void comment($description)
 * @method void pause()
 *
 * @SuppressWarnings(PHPMD)
*/
class AcceptanceTester extends \Codeception\Actor
{
    use _generated\AcceptanceTesterActions;

    /**
     * Define custom actions here
     */

    private string $successNotification  = '.toast.toast-success';
    private string $errorNotification    = '.toast.toast-error';
    private string $closeNotification    = '.toast .toast-close-button';

    private string $swalModal            = '.swal2-container';
    private string $swalCancel           = '.swal2-container .swal2-cancel';
    private string $swalConfirm          = '.swal2-container .swal2-confirm';

    private string $closeTracyButton     = '#tracy-debug-bar .tracy-row:first-child li:last-child a';

    /**
     * @throws \Exception
     */
    public function seeAndCloseToastSuccessNotification($text = null): void
    {
        $I = $this;
        $I->waitForElementVisible($this->successNotification, 5);
        if($text){
            $I->see($text, $this->successNotification);
        }
        $I->click($this->closeNotification);
    }

    /**
     * @throws \Exception
     */
    public function seeAndCloseToastErrorNotification($text = null): void
    {
        $I = $this;
        if($text){
            $I->see($text, $this->errorNotification);
        } else {
            $I->waitForElementVisible($this->errorNotification, 5);
        }
        $I->click($this->closeNotification);
    }

    /**
     * @throws \Exception
     */
    public function seeAndConfirmSwal(): void
    {
        $I = $this;
        $I->waitForElementVisible($this->swalModal, 5);
        $I->click($this->swalConfirm);
    }

    /**
     * @throws \Exception
     */
    public function seeAndCancelSwal(): void
    {
        $I = $this;
        $I->waitForElementVisible($this->swalModal, 5);
        $I->click($this->swalCancel);
    }

    /**
     * @throws Exception
     */
    public function closeTracy(): void
    {
        $I = $this;
        $I->waitForElementVisible($this->closeTracyButton, 5);
        $I->click($this->closeTracyButton);
    }
}
