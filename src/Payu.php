<?php

namespace Nksquare\Payu;

use Illuminate\Support\Facades\Config;
use InvalidArgumentException;

class Payu
{ 
    /**
     * @param array $credentials
     * @return Nksquare\Payu\Account
     */
    protected function createAccountFromCredentails($credentials)
    {
        return new Account(
            $credentials['key'],
            $credentials['salt'],
            $credentials['type'],
            $credentials['type']==Account::PAYU_MONEY ? $credentials['auth_header'] : null
        );
    }

    /**
     * @return Nksquare\Payu\Gateway
     */
    public function getGateway($account=null)
    {
        $account = $this->resolve($account ?? $this->getDefaultAccount());
        return new Gateway($account);
    }

    /**
     * @return mixed
     */
    public function getClient($account=null)
    {
        $account = $this->resolve($account ?? $this->getDefaultAccount());
        switch ($account->getType()) {
            case Account::PAYU_MONEY:
                return new PayuMoneyClient($account);
            case Account::PAYU_BIZ:
                return new PayuBizClient($account);
        }
    }
    
    /**
     * @return string
     */
    public function getDefaultAccount()
    {
        return Config::get('payu.default');
    }

    /**
     * @param string $account
     * @return Nksquare\Payu\Account
     * @throws InvalidArgumentException
     */
    public function resolve($account)
    {
        switch (Config::get('payu.testing')) {
            case 'biz':
                $config = Config::get('payu.sandbox.biz');
                return $this->createAccountFromCredentails([
                    'key' => $config['key'],
                    'salt' => $config['salt'],
                    'type' => Account::PAYU_BIZ
                ]);
            case 'money':
                $config = Config::get('payu.sandbox.money');
                return $this->createAccountFromCredentails([
                    'key' => $config['key'],
                    'salt' => $config['salt'],
                    'type' => Account::PAYU_MONEY,
                    'auth_header' => $config['auth_header'],
                ]);
        }

        $config = Config::get("payu.accounts.$account");

        if($config==null)
        {
            throw new InvalidArgumentException("Payu account $account is not defined");
        }
        return $this->createAccountFromCredentails($config);
    } 

    /**
     * @param string $account
     */
    public function shouldUse($account)
    {
        Config::set('payu.default',$account);
    }
}