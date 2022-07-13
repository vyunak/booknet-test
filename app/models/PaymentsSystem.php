<?php
namespace app\models;

use app\database\ActiveBase;
use app\database\PaymentsType;
use app\database\PaymentsSystem as PS;
use app\database\ProductType;

class PaymentsSystem
{
    /**
     * @var string
     */
    public $productType;

    public $amount;
    public $lang;
    public $countryCode;
    public $userOs;
    public $data;

    public function setProductType($productType)
    {
        $this->productType = $productType;
    }

    public function setAmount($amount)
    {
        $this->amount = $amount;
    }

    public function setLang($lang)
    {
        $this->lang = $lang;
    }

    public function setCountryCode($countryCode)
    {
        $this->countryCode = $countryCode;
    }

    public function setUserOs($userOs)
    {
        $this->userOs = $userOs;
    }

    public function getData()
    {
        $productType = ProductType::getAllModels(['isEnable' => true, 'name' => $this->productType]);
        if (!empty($productType)) {

            $productType = $productType[array_key_first($productType)];
            $condition = $productType->getConditionRule(PaymentsType::class);
            $conditionSystem = $productType->getConditionRule(PaymentsSystem::class);
            $condition = array_merge_recursive($conditionSystem, $condition);

            $activeIds = PS::getActiveId();
            if (!empty($activeIds)) {
                $activePayments = PaymentsType::getAllModels(array_merge(['isEnable' => true, 'paymentSystem' => $activeIds], $condition));
                $this->filter($activePayments);
                return $this->generateButton();
            }
        } else {
            var_dump('ProductType not found');
            die;
        }
    }

    public function generateButton()
    {
        $buttons = [];

        if (!empty($this->data)) {
            foreach ($this->data as $datum) {
                $datum = $datum[array_key_first($datum)];

                $buttons[] = new Button([
                    'name' => $datum->name,
                    'commission' => $datum->commission,
                    'imageUrl' => $datum->img,
                    'payUrl' => $datum->url,
                ]);
            }
        }
        return $buttons;
    }

    public function filter($activePayments)
    {
        $newArray = [];

        foreach ($activePayments as $activePayment) {
            $conditionType = $activePayment->getConditionRule(PaymentsType::class);
            $conditionSystemType = $activePayment->getConditionRule(PaymentsSystem::class);
            $addValue = true;
            foreach ($conditionSystemType as $key => $value) {
                if (property_exists($this, $key)) {
                    if (is_array($value)) {
                        if (!in_array($this->$key, $value)) {
                            $addValue = false;
                        }
                    } else if ($this->$key !== $value) {
                        $addValue = false;
                    }

                } else if(in_array($key, ["min"])) {
                    if (is_array($value)) {
                        $value = max($value);
                    }
                    if ($this->amount < $value) {
                        $addValue = false;
                    }
                }
            }
            if ($addValue) {
                $conditionType = array_merge($conditionType, $conditionSystemType);
                $newArray[] = ActiveBase::filter([$activePayment], $conditionType);
            }
        }

        $this->data = array_filter($newArray);
        return $this;
    }

}
