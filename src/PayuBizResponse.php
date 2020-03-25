<?php

namespace Nksquare\Payu;

class PayuBizResponse extends AbstractResponse
{
    /**
     * @return bool
     */
    public function success()
    {
        return $this->status == 1;
    }
}