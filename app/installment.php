<?php

require_once __DIR__ . '/Interfaces/IInstallment.php';

/**
 * Class Installment
 *
 * @property $installmentCount
 * @property $base_premium
 * @property $commission
 * @property $tax
 * @property $total
 */
class Installment implements \app\Interfaces\IInstallment
{
    public function __construct($installmentCount, $premium, $commission, $tax)
    {
        $this->setProperties($installmentCount, $premium, $commission, $tax);
    }

    private function setProperties($installmentCount, $premium, $commission, $tax)
    {
        $this->installmentCount = $installmentCount;
        // Use integer for calculation, then divide final result by 100
        $this->base_premium = (int) ($premium/$installmentCount);
        $this->commission = (int) ($commission/$installmentCount);
        $this->tax = (int) ($tax/$installmentCount);
        $this->total = ($this->base_premium + $this->commission + $this->tax);
    }

    /**
     * A few cents could get missing because of approximations. This method
     * ensures that the remaining cents are added to total. Note that this
     * method should only be called on the last iteration when calculating
     * installments in a loop.
     *
     * @param float $grandTotal
     */
    public function addDifferenceToTotal($grandTotal)
    {
        $sumOfInstallmentTotals = $this->total * $this->installmentCount;
        $remainder = ($grandTotal - $sumOfInstallmentTotals);
        $this->total += $remainder;
    }

    // Change properties to number format
    public function inCurrencyFormat()
    {
        $this->base_premium = number_format($this->base_premium/100, 2);
        $this->commission = number_format($this->commission/100, 2);
        $this->tax = number_format($this->tax/100, 2);
        $this->total = number_format($this->total/100, 2);

        return $this;
    }
}