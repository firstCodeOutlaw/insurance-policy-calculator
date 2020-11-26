<?php

class RequestValidation
{
    const MINIMUM_CAR_VALUE = 100;
    const MAXIMUM_CAR_VALUE = 100000;
    const MINIMUM_NUMBER_OF_INSTALLMENTS = 1;
    const MAXIMUM_NUMBER_OF_INSTALLMENTS = 12;

    // Validate number of installment
    public static function installments($value)
    {
        if ($value > self::MAXIMUM_NUMBER_OF_INSTALLMENTS || $value < self::MINIMUM_NUMBER_OF_INSTALLMENTS) {
            throw new \Exception('Invalid number of payments');
        }

        return;
    }

    // Validate car value
    public static function carValue($value)
    {
        if ($value < self::MINIMUM_CAR_VALUE || $value > self::MAXIMUM_CAR_VALUE) {
            throw new \Exception('Estimated car value is invalid');
        }

        return;
    }

    // Validate tax percentage
    public static function taxPercentage($value)
    {
        if (! $value > 0) {
            throw new \Exception('Invalid tax percentage');
        }
        
        return;
    }

    // Validate special hour
    public static function specialHour($value)
    {
        if ($value < 0 || $value > 1) {
            throw new \Exception('Special hour is invalid');
        }
        return;
    }
}