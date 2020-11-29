<?php

namespace app\Interfaces;

Interface IValidation
{
    public function checkInstallmentCount($count);
    public function checkCarValue($value);
    public function checkTaxPercentage($value);
    public function checkSpecialHour($value);
}