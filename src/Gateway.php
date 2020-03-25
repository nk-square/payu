<?php

namespace Nksquare\Payu;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Config;

class Gateway
{
    /**
     * @var string
     */
    protected $endpoint;

    /**
     * @var string
     */
    protected $surl;

    /**
     * @var string
     */
    protected $furl;

    /**
     * @var Nksquare\Payu\Account
     */
    protected $account;

    /**
     * @var array
     */
    protected $payment;

    /**
     * @var Nksquare\Payu\Verifier
     */
    protected $verifier;

    /**
     * @param Nksquare\Payu\Account $account
     */
    function __construct($account)
    {
        $this->account = $account;
        $this->verifier = new Verifier($this->account);
        $this->endpoint = Config::get('payu.testing') ? 'https://sandboxsecure.payu.in/_payment' : 'https://secure.payu.in/_payment';
    }

    /**
     * @return Nksquare\Payu\Account
     */
    public function getAccount()
    {
        return $this->account;
    }

    /**
     * @param array $payment
     * @return Nksquare\Payu\Gateway
     */
    public function setPayment($payment)
    {
        $this->payment = array_merge($payment,['key'=>$this->account->getKey()]);
        return $this;
    }

    /**
     * @param string $surl
     * @return Nksquare\Payu\Gateway
     */
    public function setSurl($surl)
    {
        $this->surl = $surl;
        return $this;
    }

    /**
     * @param array $furl
     * @return Nksquare\Payu\Gateway
     */
    public function setFurl($furl)
    {
        $this->furl = $furl;
        return $this;
    }

    /**
     * @return Nksquare\Payu\Verifier
     */
    public function getVerifier()
    {
        return $this->verifier;
    }

    /**
     * @return Illuminate\View\View $payment
     */
    public function getPaymentForm()
    {
        $fields = [
            'hash' => $this->verifier->generateHash($this->payment),
            'surl' => $this->surl,
            'furl' => $this->furl,
            'key' => $this->account->getKey(),
            'productinfo' => $this->payment['productinfo'],
            'txnid' =>  $this->payment['txnid'],
            'firstname' => $this->payment['firstname'],
            'amount' => $this->payment['amount'],
            'email' => $this->payment['email'],
            'phone' => $this->payment['phone'],
            'udf1' => $this->payment['udf1'] ?? null,
            'udf2' => $this->payment['udf2'] ?? null,
            'udf3' => $this->payment['udf3'] ?? null,
            'udf4' => $this->payment['udf4'] ?? null,
            'udf5' => $this->payment['udf5'] ?? null,
            'udf6' => $this->payment['udf6'] ?? null,
            'udf7' => $this->payment['udf7'] ?? null,
            'udf8' => $this->payment['udf8'] ?? null,
            'udf9' => $this->payment['udf9'] ?? null,
            'udf10' => $this->payment['udf10'] ?? null,
        ];

        if($this->account->getType()==Account::PAYU_MONEY)
        {
            $fields['service_provider'] = 'payu_paisa';
        }

        return View::make(Config::get('payu.payment_form'),[
            'endpoint' => $this->endpoint,
            'fields' => $fields
        ]);
    }

    /**
     * @return array
     */
    public function getPayment()
    {
        return $this->payment;
    }

    /**
     * @param array $input
     * @param string $hash
     * @return bool
     */
    public function verifyReverseHash($input,$hash)
    {
        return $this->verifier->verifyReverseHash($input,$hash);
    }
}