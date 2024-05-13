<?php

namespace Nksquare\Payu;

use Illuminate\Support\Facades\Config;

class LocalClient
{
    /**
     * @var Nksquare\Payu\Account
     */ 
    protected $account;

    /**
     * @var string
     */ 
    protected $endpoint;

    /**
     * @param Nksquare\Payu\Account $account
     */ 
    public function __construct($account)
    {
        $this->account = $account;
    }

    /**
     * @param string $txnid
     * @return array|null
     */ 
    public function getPayment($txnid)
    {
        $payuPayment = PayuPayment::find($txnid);
        return PayuPayment::find($txnid) ? $payuPayment->toArray() : null;
    }
}