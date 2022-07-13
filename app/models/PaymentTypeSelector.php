<?php
namespace app\models;

use app\database\PaymentsType;
use app\models\PaymentsSystem;

class PaymentTypeSelector
{
    protected $payments;
    protected $buttons;

    public function __construct(string $productType, float $amount, string $lang, string $countryCode, string $userOs)
    {
        $this->payments = new PaymentsSystem();
        $this->payments->setProductType($productType);
        $this->payments->setAmount($amount);
        $this->payments->setLang($lang);
        $this->payments->setCountryCode($countryCode);
        $this->payments->setUserOs($userOs);
    }

    public function getButtons()
    {
        return $this->payments->getData();
    }

}
