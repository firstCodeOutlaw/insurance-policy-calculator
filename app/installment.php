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
        $this->base_premium = ($premium/$installmentCount) / 100;
        $this->commission = ($commission/$installmentCount) / 100;
        $this->tax = ($tax/$installmentCount) / 100;
        $this->total = round($this->base_premium + $this->commission + $this->tax, 2);
    }

    /**
     * A few cents could get missing because of approximations. This method
     * ensures that the remaining cents are added to total. Note that this
     * method should only be called on the last iteration when calculating
     * installments in a loop. It should also be called before calling
     * $this->inCurrencyFormat() or any other method in this class to
     * avoid errors.
     *
     * @param float $grandTotal
     */
    public function addDifferenceToTotal($grandTotal)
    {
        $sumOfInstallmentTotals = $this->total * $this->installmentCount;
        $remainder = ($grandTotal - $sumOfInstallmentTotals);

        if ($remainder > 0) {
            // $this->total is already rounded to 2 decimal places. Round
            // $remainder to 2 d.p too to maintain same decimal places
            $this->total += round($remainder, 2);
        }
    }

    // Change properties to number format
    public function inCurrencyFormat()
    {
        $this->base_premium = number_format($this->base_premium, 2);
        $this->commission = number_format($this->commission, 2);
        $this->tax = number_format($this->tax, 2);
        $this->total = number_format($this->total, 2);

        return $this;
    }
}