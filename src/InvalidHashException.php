<?php

namespace Nksquare\Payu;

use Exception;

class InvalidHashException extends Exception
{
    /**
     * @var string
     */
    protected $hash;

    /**
     * @param string $txnid
     */
    function __construct($hash)
    {
        parent::__construct('Invalid hash '.$hash);
        $this->hash = $hash;
    }

    /**
     * @return string
     */
    public function getHash()
    {
        return $this->hash;
    }
}