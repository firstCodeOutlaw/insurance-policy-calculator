<?php

/**
 * Class Policy
 *
 * @property $car_value
 * @property $base_premium
 * @property $base_premium_percentage
 * @property $commission
 * @property $commission_percentage
 * @property $tax
 * @property $tax_percentage
 * @property $total
 */
class Policy
{
    public function __construct(Insurance $request)
    {
        $this->setProperties($request);
    }

    private function setProperties(Insurance $request)
    {
        // Cast some values to currency format
        $this->car_value = number_format($request->getCarValue(), 2);
        $this->base_premium = number_format($request->getBasePrice(), 2);
        $this->base_premium_percentage = $request->fallsWithinSpecialHour()
        ? Config::SPECIAL_BASE_PRICE_PERCENTAGE . '%'
        : Config::NORMAL_BASE_PRICE_PERCENTAGE . '%';
        $this->commission = number_format($request->getCommission(), 2);
        $this->commission_percentage = Config::COMMISSION_PERCENTAGE . '%';
        $this->tax = number_format($request->getTax(), 2);
        $this->tax_percentage = $request->getTaxPercentage() . '%';
        $this->total = number_format($request->getTotalPolicySum(), 2);
    }
}