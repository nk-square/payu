<?php

namespace Nksquare\Payu;

abstract class AbstractResponse
{
    /** 
     * @var array
     */
    protected $attributes;

    /**
     * @param string|array $response
     */
    function __construct($response)
    {
        $params = is_array($response) ? $response : json_decode($response,true);
        $this->initAttributes($params ?? []);
    }

    /**
     * @param string $key
     * @return mixed
     */
    public function __get($key)
    {
        return $this->attributes[$key] ?? null;
    }

    /**
     * @param string $key
     * @param string $value
     */
    public function __set($key,$value)
    {
        return $this->attributes[$key] = $value;
    }

    /**
     * @param array $params
     */
    protected function initAttributes($params)
    {
        foreach($params as $key => $value)
        {
            $this->attributes[$key] = $value;
        }
    }
}