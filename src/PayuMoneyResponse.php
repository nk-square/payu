<?php

namespace Nksquare\Payu;

class PayuMoneyResponse extends AbstractResponse
{
    /**
     * @return bool
     */
    public function success()
    {
        return $this->status == 0;
    }
}