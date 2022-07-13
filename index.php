<?php

require_once('vendor/autoload.php');

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use app\models\PaymentTypeSelector;

$productType        = $_GET['productType'] ?? null;
$amount             = $_GET['amount'] ?? null;
$lang               = $_GET['lang'] ?? null;
$countryCode        = $_GET['countryCode'] ?? null;
$userOs             = $_GET['userOs'] ?? null;
$options            = $_GET['options'] ?? null;

$paymentTypeSelector = new PaymentTypeSelector($productType, $amount, $lang, $countryCode, $userOs);

$paymentButtons = $paymentTypeSelector->getButtons();

foreach ($paymentButtons as $btn) {
    echo "-------------------" . PHP_EOL;
    echo $btn->getName() . PHP_EOL;
    echo $btn->getCommission() . PHP_EOL;
    echo $btn->getImageUrl() . PHP_EOL;
    echo $btn->getPayUrl() . PHP_EOL;
}
