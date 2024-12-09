<?php

namespace Nksquare\Payu;

use GuzzleHttp\Client;
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
     * @var GuzzleHttp\Client
     */ 
    protected $client;

    /**
     * @param Nksquare\Payu\Account $account
     */ 
    public function __construct($account)
    {
        $this->account = $account;
        $this->endpoint = Config::get('payu.testing') ? 'https://test.payumoney.com' : 'https://www.payumoney.com';
        $this->client = new Client([
            'base_uri' => $this->endpoint,
            'timeout'  => 2.0,
        ]);
    }

    /**
     * @param string $service
     * @param array $params
     * @return Nksquare\Payu\PayuMoneyResponse
     */ 
    public function service($service,$params)
    {
        $options = [
            'headers' => [
                'authorization' => $this->account->getAuthHeader(),
                'cache-control' => 'no-cache',
            ],
            'form_params' => $params + ['merchantKey' => $this->account->getKey()]
        ];
        $response = $this->client->post($service,$options);
        $response = (string)$response->getBody();
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