<?php

namespace app\database;

abstract class ActiveBase
{
    /**
     * @var int
     */
    public $id;
    /**
     * @var array
     */
    public $rule;
    
    /**
     * @var array
     */
    public static $db = [];

    /**
     * @return array[]
     */
    public static function getAll(): ?array
    {
        $db = static::$db;

        foreach ($db as $key => $value) {
            $db[$key]['id'] = $key;
        }
        return $db;
    }

    /**
     * @param array $condition
     * @return array|null
     */
    public static function getAllCondition(array $condition): ?array
    {
        $countConditions = count($condition);

        if ($countConditions <= 0) {
            return null;
        }

        $db = static::getAll();
        return self::filter($db, $condition);
    }

    public function getRule()
    {
        return $this->rule;
    }

    public function getConditionRule($class, $rules = null)
    {
        $rules = $rules ?? $this->getRule();
        $condition = [];

        if (!empty($rules)) {
            if (isset($rules["on"]) && is_array($rules["on"])) {
                foreach ($rules["on"] as $value) {
                    if (is_array($value)) {
                        foreach ($value as $key => $rule) {
                            if (property_exists($class, $key)) {
                                $condition[$key] = $rule;
                            } else if (in_array($key, ['min'])) {
                                $condition[$key] = $rule;
                            }
                        }
                    }

                }
            }
            if (isset($rules["except"]) && is_array($rules["except"])) {
                foreach ($rules["except"] as $value) {
                    if (is_array($value)) {
                        foreach ($value as $key => $rule) {
                            if (property_exists($class, $key)) {
                                $condition["except"][$key] = $rule;
                            } else if (in_array($key, ['min'])) {
                                $condition[$key] = $rule;
                            }
                        }
                    }
                }
            }
        }
        return $condition;
    }

    public static function getAllModels($condition = null)
    {
        if (!empty($condition)) {
            $data = static::getAllCondition($condition);
        } else {
            $data = static::getAll();
        }
        
        $modals = [];
        foreach ($data as $datum) {
            $model = new static();
            foreach ($datum as $key => $value) {
                if (property_exists($model, $key)) {
                    $model->$key = $value;
                }
            }
            $modals[] = $model;
        }

        return $modals;
    }

    public function __construct($attributes = [])
    {
        foreach ($attributes as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @param $data
     * @param $condition
     * @return array
     */
    public static function filter($data, $condition)
    {
        $newArray = [];

        foreach ($data as $key => $value) {
            $addValue = true;
            foreach ($condition as $keyItem => $item) {
                $elem = null;

                if (is_object($value) && isset($value->$keyItem)) {
                    $elem = $value->$keyItem;
                } else if (!is_object($value) && is_array($value) && isset($value[$keyItem])) {
                    $elem = $value[$keyItem];
                }

                if (!empty($elem) || !is_array($keyItem) && in_array($keyItem, ["except", "min"])) {

                    if (in_array($keyItem, ["except", "min"])) {

                        switch ($keyItem) {
                            case "except":
                                foreach ($item as $k1 => $v1) {
                                    if (is_array($v1)) {
                                        if (is_object($value)) {
                                            if (isset($value->$k1) && in_array($value->$k1, $v1)) {
                                                $addValue = false;
                                                break;
                                            }
                                        } else {
                                            if (isset($value[$k1]) && in_array($value[$k1], $v1)) {
                                                $addValue = false;
                                                break;
                                            }
                                        }
                                    } else {
                                        $addValue = false;
                                        break;
                                    }
                                }
                                break;
                            default:
                                break;
                        }
                    } else {
                        if (is_array($item)) {
                            if (!in_array($elem, $item)) {
                                $addValue = false;
                                break;
                            }
                        } elseif ($elem !== $item) {
                            $addValue = false;
                            break;
                        }
                    }

                }

            }
            if ($addValue) {
                $newArray[] = $value;
            }
        }
        return $newArray;
    }

    /**
     * @param int $id
     * @return array|null
     */
    public static function getOneByID(int $id): ?array
    {
        $db = static::getAll();

        $res = null;

        foreach ($db as $key => $value) {
            if ($key == $id) {
                $res = $value;
                break;
            }
        }
        return $res;
    }
    
}
