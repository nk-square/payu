<?php

namespace Nksquare\Payu;

class TxnidGenerator
{
    public function generate()
    {
        return round(microtime(true)).mt_rand(1000,9999);
    }
}