<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On');
require_once('car_insurance.php');
require_once('validation.php');

$request = json_decode(file_get_contents('php://input'));

// Get request parameters
$numberOfInstallments = (int) $request->number_of_installments;
$estimatedCarValue = (int) $request->estimated_value;
$taxPercentage = (int) $request->tax_percentage;
$specialHour = (int) $request->is_special_hour;

// Validate request
RequestValidation::installments($numberOfInstallments);
RequestValidation::carValue($estimatedCarValue);
RequestValidation::taxPercentage($taxPercentage);
RequestValidation::specialHour($specialHour);

// Get policy calculation
$result = [];
$policy = new CarInsurance($estimatedCarValue, $taxPercentage, $specialHour);
$breakdown = $policy->getPaymentBreakdown(false, true);
$result['policy'] = $breakdown;

if ($numberOfInstallments > 1) {
    $result['installments'] = [];
    // Split estimated car value into number of installments chosen by user
    $carValuePerInstallment = $estimatedCarValue / $numberOfInstallments;
    // Calculate insurance per installment
    for ($i = 0; $i < $numberOfInstallments; $i++) {
        $insurance = new CarInsurance($carValuePerInstallment, $taxPercentage, $specialHour);
        $insurance = $insurance->getPaymentBreakdown();
        unset($insurance['car_value']);
        array_push($result['installments'], $insurance);
    }

    // Check if total installment equals grand total
    $grandTotal = ($result['policy']['total']) * 100;
    $sumOfInstallmentTotals = ($result['installments'][0]['total'] * $numberOfInstallments) * 100;
    $remainder = $grandTotal - $sumOfInstallmentTotals;

    // Add remainder to last installment
    $lastInstallmentTotal = $result['installments'][($numberOfInstallments - 1)]['total'];
    $result['installments'][($numberOfInstallments - 1)]['total'] = $lastInstallmentTotal + ($remainder/100);

    // Format installments in number format
    for ($i = 0; $i < $numberOfInstallments; $i++) { 
        $result['installments'][$i]['base_premium'] = number_format($result['installments'][$i]['base_premium'], 2);
        $result['installments'][$i]['commission'] = number_format($result['installments'][$i]['commission'], 2);
        $result['installments'][$i]['tax'] = number_format($result['installments'][$i]['tax'], 2);
        $result['installments'][$i]['total'] = number_format($result['installments'][$i]['total'], 2);
    }
}

// Format policy in number format
$result['policy']['car_value'] = number_format($result['policy']['car_value'], 2);
$result['policy']['base_premium'] = number_format($result['policy']['base_premium'], 2);
$result['policy']['commission'] = number_format($result['policy']['commission'], 2);
$result['policy']['tax'] = number_format($result['policy']['tax'], 2);
$result['policy']['total'] = number_format($result['policy']['total'], 2);

echo json_encode($result);