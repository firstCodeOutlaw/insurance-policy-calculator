<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once __DIR__ . '/insurance.php';

$request = new Insurance();
$response = $request->getInstallmentCount() > 0
    ? $request->withInstallments()
    : $request;

echo json_encode([
    'policy' => $request->getPolicy(),
    'installments' => $request->getInstallment()
]);