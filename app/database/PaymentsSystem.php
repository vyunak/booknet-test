<?php

namespace app\database;

use app\database\PaymentsSystem as PS;

class PaymentsSystem extends ActiveBase
{
    public $id;
    public $name;
    public $isEnable;
    public $rule;

    public static $db = [

        [
            "name" => "Interkassa",
            "isEnable" => true,
            "rule" => [
                'on' => [],
                'except' => []
            ],
        ],

        [
            "name" => "CardPay",
            "isEnable" => true,
            "rule" => [
                'on' => [],
                'except' => []
            ],
        ],

    ];

    public static function getActiveId()
    {
        $data = static::getAllCondition(['isEnable' => true]);
        $ids = [];
        foreach ($data as $datum) {
            $ids[] = $datum['id'];
        }

        return $ids;
    }

}
