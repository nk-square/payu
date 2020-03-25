<?php

namespace Nksquare\Payu;

class Verifier
{
    /**
     * @var Nksquare\Payu\Account
     */
    protected $account;

    /**
     * @param Nksquare\Payu\Account $account
     */
    function __construct($account)
    {
        $this->account = $account;
    }

    /**
     * @param array $data
     * @param string $hash
     * @return bool
     */
    public function verifyHash($data,$hash)
    {
        return $hash == $this->generateHash($data);
    }

    /**
     * @param array $data
     * @return string
     */
    public function generateHash(array $data)
    {
        $hashString = $this->account->getKey().'|'.$data['txnid'].'|'.$data['amount'].'|'.$data['productinfo'].'|'.
                $data['firstname'].'|'.$data['email'].'|'.($data['udf1'] ?? null).'|'.($data['udf2'] ?? null).'|'.
                ($data['udf3'] ?? null).'|'.($data['udf4'] ?? null).'|'.($data['udf5'] ?? null).'|'.
                ($data['udf6'] ?? null).'|'.($data['udf7'] ?? null).'|'.($data['udf8'] ?? null).'|'.
                ($data['udf9'] ?? null).'|'.($data['udf10'] ?? null).'|'.$this->account->getSalt();
        
        return hash('sha512',$hashString);
    }

    /**
     * @param array $data
     * @return string
     */
    function generateReverseHash(array $data)
    {
        $hashString = $this->account->getSalt().'|'.$data['status'].'||||||'.$data['udf5'].'|'.$data['udf4'].'|'.
            $data['udf3'].'|'.$data['udf2'].'|'.$data['udf1'].'|'.$data['email'].'|'.$data['firstname'].'|'.
            $data['productinfo'].'|'.$data['amount'].'|'.$data['txnid'].'|'.$this->account->getKey();

        if(isset($data['additionalCharges']))
        {
            $hashString = $data['additionalCharges'].'|'.$hashString;
        }
        
        return hash('sha512',$hashString);
    }

    /**
     * @param array $data
     * @param string $hash
     * @return boolean
     */
    function verifyReverseHash($data,$hash)
    {
        return $hash == $this->generateReverseHash($data);
    }
}