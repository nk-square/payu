<?php

namespace Nksquare\Payu;

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
     * @param Nksquare\Payu\Account $account
     */
    public function __construct($account)
    {
        $this->account = $account;
        $this->endpoint = Config::get('payu.testing') ? 'https://test.payu.in/merchant/postservice.php' : 'https://info.payu.in/merchant/postservice.php';
    }

    /**
     * @param string $command
     * @param array $params
     * @return Nksquare\Payu\PayuBizResponse
     */
    public function service($command,array $params)
    {
        $ch = curl_init();
        curl_setopt($ch,CURLOPT_URL,$this->endpoint);
        curl_setopt($ch,CURLOPT_POST,1);
        $hash = hash('sha512',$this->account->getKey().'|'.$command.'|'.$params['var1'].'|'.$this->account->getSalt());
        $params = array_merge([
            'form' => 2,
            'key' => $this->account->getKey(),
            'command' => $command,
            'hash' => $hash,
        ],$params);
        curl_setopt($ch,CURLOPT_POSTFIELDS,http_build_query($params));
        curl_setopt($ch,CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close ($ch);
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