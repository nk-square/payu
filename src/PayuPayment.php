<?php

namespace Nksquare\Payu;

use Illuminate\Database\Eloquent\Model;

class PayuPayment extends Model
{
    /**
     * @var string
     */
    const SUCCESS = 'success';

    /**
     * @var string
     */
    const FAILURE = 'failure';

    /**
     * @var string
     */
    const PENDING = 'pending';

    /**
     * @var string
     */
    protected $primaryKey = 'txnid';

    /**
     * @var string
     */
    public $incrementing = false;

    /**
     * @return bool
     */
    public function success()
    {
        return $this->status == self::SUCCESS;
    }

    /**
     * @return bool
     */
    public function failure()
    {
        return $this->status == self::FAILURE;
    }

    /**
     * @return bool
     */
    public function pending()
    {
        return $this->status == self::PENDING;
    }

    public static function getByTxnid($txnid)
    {
        return static::find($txnid);
    }

    /**
     * @param array $payment
     */
    public static function savePayment($payment)
    {
        $payuPayment = new static();
        $payuPayment->key = $payment['key'];
        $payuPayment->txnid = $payment['txnid'];
        $payuPayment->amount = $payment['amount'];
        $payuPayment->email = $payment['email'];
        $payuPayment->phone = $payment['phone'];
        $payuPayment->firstname = $payment['firstname'];
        $payuPayment->lastname = $payment['lastname'] ?? null;
        $payuPayment->udf1 = $payment['udf1'] ?? null;
        $payuPayment->udf2 = $payment['udf2'] ?? null;
        $payuPayment->udf3 = $payment['udf3'] ?? null;
        $payuPayment->udf4 = $payment['udf4'] ?? null;
        $payuPayment->udf5 = $payment['udf5'] ?? null;
        $payuPayment->udf6 = $payment['udf6'] ?? null;
        $payuPayment->udf7 = $payment['udf7'] ?? null;
        $payuPayment->udf8 = $payment['udf8'] ?? null;
        $payuPayment->udf9 = $payment['udf9'] ?? null;
        $payuPayment->udf10 = $payment['udf10'] ?? null;
        $payuPayment->save();
        return $payuPayment;
    }

    /**
     * @param string $txnid
     * @param array $payment
     */
    public function completePayment(array $payment)
    {
        $this->mihpayid = $payment['mihpayid'];
        $this->status = $payment['status'];
        $this->unmappedstatus = $payment['unmappedstatus'];
        $this->mode = $payment['mode'];
        $this->bankcode = $payment['bankcode'];
        $this->productinfo = $payment['productinfo'];
        $this->additional_charges = $payment['additionalCharges'] ?? ($payment['additional_charges'] ?? null);
        $this->net_amount_debit = $payment['net_amount_debit'] ?? 0;
        $this->bank_ref_num = $payment['bank_ref_num'] ?? null;
        $this->cardnum = $payment['cardnum'] ?? null;
        $this->name_on_card = $payment['name_on_card'] ?? null;
        $this->issuing_bank = $payment['issuing_bank'] ?? null;
        $this->card_type = $payment['card_type'] ?? null;
        $this->error = $payment['error'] ?? null;
        $this->save();
    }

    /**
     * @return Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function payable()
    {
        return $this->morphTo();
    }
}