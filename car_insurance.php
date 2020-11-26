<?php
/**
 * Class CarInsurance
 *
 * Calculations are in cents for accuracy. Figures are divided by 100 at the
 * last stage to convert figures back to Euro
 */
class CarInsurance
{
    const NORMAL_BASE_PRICE_PERCENTAGE = 11;
    const SPECIAL_BASE_PRICE_PERCENTAGE = 13;
    const COMMISSION_PERCENTAGE = 17;
    
    private $carValue;
    private $tax;
    private $taxPercentage;
    private $basePricePercentage;
    private $isSpecialHour;

    public function __construct($estimatedCarValue, $taxPercentage, $isSpecialHour)
    {
        $this->taxPercentage = $taxPercentage;
        $this->isSpecialHour = $isSpecialHour;
        $this->setCarValue($estimatedCarValue);
        $this->setTax($taxPercentage);
    }

    public function setCarValue($value)
    {
        $this->carValue = $value * 100;
    }

    public function setTax($value)
    {
        // Get tax percentage of car value
        $this->tax = ($value/100 * $this->carValue);
    }

    public function getTax()
    {
        return $this->tax;
    }

    public function getCommission()
    {
        return ((self::COMMISSION_PERCENTAGE/100) * $this->carValue);
    }

    public function getBasePrice()
    {
        if ($this->isSpecialHour) {
            $this->basePricePercentage = self::SPECIAL_BASE_PRICE_PERCENTAGE;
            return ((self::SPECIAL_BASE_PRICE_PERCENTAGE/100) * $this->carValue);
        }

        $this->basePricePercentage = self::NORMAL_BASE_PRICE_PERCENTAGE;
        return ((self::NORMAL_BASE_PRICE_PERCENTAGE/100) * $this->carValue);
    }

    public function getPaymentBreakdown($inCurrencyFormat = false, $forPolicy = false)
    {
        $basePrice = $this->getBasePrice();
        $data = [
            'car_value' => $inCurrencyFormat ? number_format($this->carValue/100, 2) : $this->carValue/100,
            'base_premium' => $inCurrencyFormat ? number_format($basePrice/100, 2) : $basePrice/100,
            'commission' => $inCurrencyFormat ? number_format($this->getCommission()/100, 2) : $this->getCommission()/100,
            'tax' => $inCurrencyFormat ? number_format($this->getTax()/100, 2) : $this->getTax()/100,
            'total' => $inCurrencyFormat
                ? number_format(($basePrice + $this->getCommission() + $this->getTax())/100, 2)
                : ($basePrice + $this->getCommission() + $this->getTax())/100
        ];

        if ($forPolicy) {
            // Add extra parameters
            $data['base_premium_percentage'] = $this->basePricePercentage . "%";
            $data['commission_percentage'] = self::COMMISSION_PERCENTAGE . "%";
            $data['tax_percentage'] = $this->taxPercentage . "%";
        }

        return $data;
    }
}