<?php

namespace Nksquare\Payu;

use Exception;

class PaymentNotFoundException extends Exception
{
    /**
     * @var string
     */
    protected $txnid;

    /**
     * @param string $txnid
     */
    function __construct($txnid)
    {
        parent::__construct('Payment not found for transaction id '.$txnid);
        $this->txnid = $txnid;
    }

    /**
     * @return string
     */
    public function getTxnid()
    {
        return $this->txnid;
    }
}