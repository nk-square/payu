<?php

namespace Nksquare\Payu;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Config;

class PayuBizClient
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
        $this->endpoint = Config::get('payu.testing') ? 'https://test.payu.in/merchant/postservice.php' : 'https://info.payu.in/merchant/postservice.php';
        $this->client = new Client([
            'base_uri' => $this->endpoint,
            'timeout'  => 2.0,
            'debug' => true
        ]);
        
    }
    /**
     * @param string $command
     * @param array $params
     * @return Nksquare\Payu\PayuBizResponse
     */
    public function service($command,array $params)
    {
        $hash = hash('sha512',$this->account->getKey().'|'.$command.'|'.$params['var1'].'|'.$this->account->getSalt());
        $options = [
            'headers' => [
                'authorization' => $this->account->getAuthHeader(),
                'cache-control' => 'no-cache',
            ],
            'form_params' => $params + [
                'form' => 2,
                'key' => $this->account->getKey(),
                'command' => $command,
                'hash' => $hash,
            ]
        ];
        $response = $this->client->post('',$options);
        $response = (string)$response->getBody();
        return new PayuBizResponse($response);
    }

    /**
     * @param string $txnid
     * @return array|null
     */
    public function getPayment($txnid)
    {
        $response = $this->service('verify_payment',[
            'var1' => $txnid,
        ]);
        return $response->success() ? $response->transaction_details[$txnid] : null;
    }
}