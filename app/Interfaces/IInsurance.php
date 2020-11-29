<?php

namespace app\Interfaces;

Interface IInsurance
{
    public function getBasePrice();
    public function getCommission();
    public function getTax();
    public function getTotalPolicySum();
    public function getPolicy();
    public function getInstallment();
    public function withInstallments();
}