<?php

namespace Request;

require_once __DIR__ . '/Interfaces/IRequest.php';

use app\Interfaces\IRequest;

/**
 * Class Request
 *
 * @package Request
 * @author Benjamin Ayangbola
 */
class Request implements IRequest
{
    /**
     * Estimated Value of car
     *
     * @var int
     */
    private $carValue;

    /**
     * Percentage to be calculated as tax payable on
     * estimated value of car
     *
     * @var int
     */
    private $taxPercentage;

    /**
     * Number of times a user wants to settle insurance
     * insurance charges which is a minimum of 1 and a
     * maximum of 12
     *
     * @var int
     */
    private $installmentCount;

    /**
     * Determines whether base price of policy should be 11% 0r 13%
     *
     * @var bool
     */
    private $isSpecialHour;

    public function __construct()
    {
        $input = json_decode(file_get_contents('php://input'));
        $this->setCarValue($input->estimated_value);
        $this->setTaxPercentage($input->tax_percentage);
        $this->setInstallmentCount($input->number_of_installments);
        $this->setSpecialHour($input->is_special_hour);
    }

    protected function setCarValue($value)
    {
        $this->carValue = (int) $value;
    }

    public function getCarValue()
    {
        return $this->carValue;
    }

    protected function setTaxPercentage($value)
    {
        $this->taxPercentage = (int) $value;
    }

    public function getTaxPercentage()
    {
        return $this->taxPercentage;
    }

    protected function setInstallmentCount($value)
    {
        $this->installmentCount = (int) $value;
    }

    public function getInstallmentCount()
    {
        return $this->installmentCount;
    }

    protected function setSpecialHour($value)
    {
        $this->isSpecialHour = ((int) $value) > 0;
    }

    public function fallsWithinSpecialHour()
    {
        return $this->isSpecialHour;
    }
}