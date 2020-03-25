<?php

namespace Nksquare\Payu;

use Illuminate\Support\Facades\Config;

class PayuMoneyClient
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
        $this->endpoint = Config::get('payu.testing') ? 'https://test.payumoney.com' : 'https://www.payumoney.com';
    }

    /**
     * @param string $service
     * @param array $params
     * @return Nksquare\Payu\PayuMoneyResponse
     */ 
    public function service($service,$params)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTPHEADER,[
            'authorization: '.$this->account->getAuthHeader(),
            'cache-control: no-cache'
        ]);
        curl_setopt($ch,CURLOPT_URL,$this->endpoint.'/'.ltrim($service,'/'));
        curl_setopt($ch,CURLOPT_POST,1);
        $postFields = array_merge([
            'merchantKey' => $this->account->getKey(),
        ],$params);
        curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($postFields));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close ($ch);
        return new PayuMoneyResponse($response);
    }

    /**
     * @param string $txnid
     * @return array|null
     */ 
    public function getPayment($txnid)
    {
        $response = $this->service('payment/op/getPaymentResponse',[
            'merchantTransactionIds' => $txnid,
        ]);
        return $response->success() ? $response->result[0]['postBackParam'] : null;
    }
}