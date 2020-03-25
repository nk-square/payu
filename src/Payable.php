<?php

namespace Nksquare\Payu;

trait Payable {

    /**
     * @return Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function payuPayment()
    {
        return $this->morphMany(PayuPayment::class,'payable')->orderBy('created_at','desc');
    }

    /**
     * @param Nksquare\Payu\PayuPayment
     */
    public function savePayment(PayuPayment $payment)
    {
        $this->payuPayment()->save($payment);
    }
}