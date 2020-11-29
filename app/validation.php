<?php

namespace Request;

class Validation
{
    const MINIMUM_CAR_VALUE = 100;
    const MAXIMUM_CAR_VALUE = 100000;
    const MINIMUM_NUMBER_OF_INSTALLMENTS = 1;
    const MAXIMUM_NUMBER_OF_INSTALLMENTS = 12;

    public function __construct($request)
    {
        $this->checkInstallments($request->number_of_installments);
        $this->checkCarValue($request->estimated_value);
        $this->checkTaxPercentage($request->tax_percentage);
        $this->checkSpecialHour($request->is_special_hour);
    }

    /**
     * Validate number of installments
     *
     * @param $value
     * @throws \Exception
     */
    public function checkInstallments($value)
    {
        $value = (int) $value;

        if ($value > self::MAXIMUM_NUMBER_OF_INSTALLMENTS) {
            throw new \Exception('Number of installments is more than maximum allowed');
        }

        if ($value < self::MINIMUM_NUMBER_OF_INSTALLMENTS) {
            throw new \Exception('Number of installments is less than minimum allowed');
        }
    }

    /**
     * Validate estimated car value
     *
     * @param $value
     * @throws \Exception
     */
    public function checkCarValue($value)
    {
        $value = (int) $value;

        if ($value < self::MINIMUM_CAR_VALUE) {
            throw new \Exception('Estimated car value is less than minimum allowed');
        }

        if ($value > self::MAXIMUM_CAR_VALUE) {
            throw new \Exception('Estimated car value is more than maximum allowed');
        }
    }

    /**
     * Validate tax percentage
     *
     * @param $value
     * @throws \Exception
     */
    public function checkTaxPercentage($value)
    {
        $value = (int) $value;

        if (! $value > 0) {
            throw new \Exception('Tax percentage is invalid');
        }
    }

    /**
     * Validate special hour
     *
     * @param $value
     * @throws \Exception
     */
    public function checkSpecialHour($value)
    {
        if ($value < 0 || $value > 1) {
            throw new \Exception('Special hour is invalid');
        }
    }
}