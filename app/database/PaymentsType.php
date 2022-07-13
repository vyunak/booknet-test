<?php

namespace app\database;

class PaymentsType extends ActiveBase
{
    public $id;
    public $name;
    public $paymentSystem;
    public $commission;
    public $url;
    public $img;
    public $type;
    public $isEnable;
    public $rule;

    // const for type sort, by index in array
    public const SORT_ORDER = ['card', 'wallet', 'terminal'];

    public static $db = [
        [
            "name" => "Paypal",
            "paymentSystem" => 0,
            "commission" => 1,
            "url" => "/pay/paypal",
            "img" => "paypal.jpg",
            "type" => "card",
            "isEnable" => true,
            "rule" => [
                'on' => [],
                'except' => [
                    ['lang' => ['ru'], "min" => 30]
                ]
            ],
        ],
        [
            "name" => "Internal wallet",
            "paymentSystem" => 0,
            "commission" => 1,
            "url" => "/pay/wallet",
            "img" => "wallet.jpg",
            "type" => "wallet",
            "isEnable" => true,
            "rule" => [
                'on' => [],
                'except' => []
            ],
        ],
        [
            "name" => "Apple pay",
            "paymentSystem" => 0,
            "commission" => 1,
            "url" => "/pay/apple_pay",
            "img" => "apple_pay.jpg",
            "type" => "card",
            "isEnable" => true,
            "rule" => [
                'on' => [
                    ["userOs" => ["ios"]]
                ],
                'except' => []
            ],
        ],
        [
            "name" => "Оплата картой",
            "paymentSystem" => 0,
            "commission" => 1,
            "url" => "/pay/card",
            "img" => "card.jpg",
            "type" => "card",
            "isEnable" => true,
            "rule" => [
                'on' => [],
                'except' => []
            ],
        ],
        [
            "name" => "Оплата картой \"Приват банк\"",
            "paymentSystem" => 0,
            "commission" => 1,
            "url" => "/pay/card",
            "img" => "card.jpg",
            "type" => "card",
            "isEnable" => true,
            "rule" => [
                'on' => [
                    ["countryCode" => ["ua"]]
                ],
                'except' => []
            ],
        ],
    ];

    /**
     * @return array|null
     */
    public static function getAll(): ?array
    {
        $array = parent::getAll();
        uasort($array, static::class . "::sort");
        return $array;
    }

    public static function sort($a, $b)
    {
        $type = static::SORT_ORDER;

        $typeFlip = array_flip($type);

        if (isset($typeFlip[$a['type']]) && isset($typeFlip[$b['type']])) {
            if ($typeFlip[$a['type']] == $typeFlip[$b['type']]) {
                return 0;
            }

            return $typeFlip[$a['type']] > $typeFlip[$b['type']] ? 1 : -1;
        }
        return 1;
    }

}
