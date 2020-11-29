<?php

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/request.php';
require_once __DIR__ . '/policy.php';
require_once __DIR__ . '/installment.php';
require_once __DIR__ . '/Interfaces/IInsurance.php';

use Request\Request;
use app\Interfaces\IInsurance;

/**
 * Class Insurance
 *
 * Money values are stored in integer. Final result is then divided
 * by 100 to avoid errors with using float in PHP.
 *
 * @author Benjamin Ayangbola
 */
class Insurance extends Request implements IInsurance
{
    /**
     * Base price of policy which can be 11% or 13% depending on
     * date and time of when calculation was done
     *
     * @var int
     */
    private $basePrice;

    /**
     * A percentage of estimated car value. Percentage to use is
     * defined in app/config.php
     *
     * @var int
     */
    private $commission;

    /**
     * A user defined percentage of estimated car value
     *
     * @var int
     */
    private $tax;

    /**
     * Sum of base price, tax and commission
     *
     * @var int
     */
    private $totalPolicySum;

    /**
     * Array that holds one or more installment policies based
     * on the installment count specified by a user
     * @var array
     */
    private $installment = [];

    /**
     * Array that holds breakdown of policy. Policy contains car
     * value, base premium, base premium percentage, tax, tax
     * percentage, commission, commission percentage and
     * total cost
     *
     * @var array
     */
    public $policy;

    public function __construct()
    {
        parent::__construct();
        $this->setBasePrice();
        $this->setCommission();
        $this->setTax();
        $this->setTotalPolicySum();
        $this->setPolicy();
    }

    private function setBasePrice()
    {
        $this->basePrice = $this->fallsWithinSpecialHour()
            ? Config::SPECIAL_BASE_PRICE_PERCENTAGE * $this->getCarValue()
            : Config::NORMAL_BASE_PRICE_PERCENTAGE * $this->getCarValue();
    }

    public function getBasePrice()
    {
        return $this->basePrice/100;
    }

    private function setCommission()
    {
        $this->commission = Config::COMMISSION_PERCENTAGE * $this->getCarValue();
    }

    public function getCommission()
    {
        return $this->commission/100;
    }

    private function setTax()
    {
        $this->tax = $this->getTaxPercentage() * $this->getCarValue();
    }

    public function getTax()
    {
        return $this->tax/100;
    }

    private function setTotalPolicySum()
    {
        $this->totalPolicySum = ($this->basePrice + $this->commission + $this->tax);
    }

    public function getTotalPolicySum()
    {
        return $this->totalPolicySum/100;
    }

    private function setPolicy()
    {
        $this->policy = new Policy($this);
    }

    public function getPolicy()
    {
        return $this->policy;
    }

    public function getInstallment()
    {
        return $this->installment;
    }

    public function withInstallments()
    {
        if ($this->getInstallmentCount() > 1) {
            for ($i = 0; $i < $this->getInstallmentCount(); $i++) {
                $box = new Installment(
                    $this->getInstallmentCount(),
                    $this->basePrice,
                    $this->commission,
                    $this->tax
                );

                // Add remainder for last iteration in loop
                if ( $i === ($this->getInstallmentCount() - 1) ) {
                    $box->addDifferenceToTotal($this->totalPolicySum);
                }

                array_push($this->installment, $box->inCurrencyFormat());
            }
        }

        return $this;
    }
}