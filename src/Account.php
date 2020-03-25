<?php

namespace Nksquare\Payu;

class Account
{
    /**
     * @var string
     */
    const PAYU_BIZ = 'biz';

    /**
     * @var string
     */
    const PAYU_MONEY = 'money';

    /**
     * @var string
     */
    protected $key;

    /**
     * @var string
     */
    protected $salt;

    /**
     * @var string
     */
    protected $type;

    /**
     * @var string
     */
    protected $authHeader;

    /**
     * @param string $key
     * @param string $salt
     * @param string $type
     * @param string $authHeader
     */
    function __construct($key,$salt,$type,$authHeader=null)
    {
        $this->key = $key;
        $this->salt = $salt;
        $this->type = $type;
        $this->authHeader = $authHeader;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return string
     */
    public function getSalt()
    {
        return $this->salt;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getAuthHeader()
    {
        return $this->authHeader;
    }
}