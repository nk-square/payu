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
        $hashString = $this->account->getKey().'|'.($data['txnid'] ?? $data['merchantTransactionId']).'|'.$data['amount'].'|'.($data['productinfo'] ?? $data['productInfo']).'|'.
                ($data['firstname'] ?? $data['customerName']).'|'.($data['email'] ?? $data['customerEmail']).'|'.($data['udf1'] ?? null).'|'.($data['udf2'] ?? null).'|'.
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
            $data['udf3'].'|'.$data['udf2'].'|'.$data['udf1'].'|'.($data['email'] ?? $data['customerEmail']).'|'.($data['firstname'] ?? $data['customerName']).'|'.
            ($data['productinfo'] ?? $data['productInfo']).'|'.$data['amount'].'|'.($data['txnid'] ?? $data['merchantTransactionId']).'|'.$this->account->getKey();

        if(isset($data['additionalCharges']) || isset($data['additional_charges']))
        {
            $hashString = ($data['additionalCharges'] ?? $data['additional_charges']).'|'.$hashString;
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