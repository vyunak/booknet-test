<?php

namespace app\database;

class ProductType extends ActiveBase
{
    public $id;
    public $name;
    public $isEnable;
    public $rule;

    /**
     * @var array[]
     */
    public static $db = [
        [
            "name" => "book",
            "isEnable" => true,
            "rule" => [
                "on" => [],
                "except" => [],
            ]
        ],
        [
            "name" => "reward",
            "isEnable" => true,
            "rule" => [
                "on" => [],
                "except" => [
                    ["type" => ["wallet"], "countryCode" => ["ru"], "min" => 10] // only internal wallet
                ],
            ]
        ],
        [
            "name" => "walletRefill",
            "isEnable" => true,
            "rule" => [
                "on" => [],
                "except" => [
                    ["type" => ["wallet"]] // only external wallet
                ],
            ]
        ],
    ];

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getRule()
    {
        return $this->rule;
    }

    /**
     * @return mixed
     */
    public function getIsEnable()
    {
        return $this->isEnable;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

}
