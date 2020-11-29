<?php

namespace app\Interfaces;

Interface IInstallment
{
    public function inCurrencyFormat();
    public function addDifferenceToTotal($whereGrandTotalEquals);
}