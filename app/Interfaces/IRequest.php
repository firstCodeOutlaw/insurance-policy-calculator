<?php

namespace app\Interfaces;

Interface IRequest
{
    public function getCarValue();
    public function getTaxPercentage();
    public function getInstallmentCount();
    public function fallsWithinSpecialHour();
}