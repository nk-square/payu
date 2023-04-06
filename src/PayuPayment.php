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
     * @return PayuPayment $payment: instance of updated payupayment corresponding to the webhook data.
     */
    public static function processWebHookSuccess(array $payment)
    {
         if(array_key_exists("txnid",$payment)){
            $payuPayment = static::getByTxnid($payment['txnid']);
            $payuPayment->completePayment($payment);
            return $payuPayment;
        }
        if(array_key_exists("merchantTransactionId",$payment)){
            $payuPayment = static::getByTxnid($payment['merchantTransactionId']);
            $payuPayment->updateCompletedPayment($payment);
            return $payuPayment;
        }
        throw new Exception("Unidentified payload");
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
        $this->mode = $payment['mode'] ?? null;
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
        $this->error_message = $payment['error_Message'] ?? null;
        $this->field0 = $payment['field0'] ?? null;
        $this->field1 = $payment['field1'] ?? null;
        $this->field2 = $payment['field2'] ?? null;
        $this->field3 = $payment['field3'] ?? null;
        $this->field4 = $payment['field4'] ?? null;
        $this->field5 = $payment['field5'] ?? null;
        $this->field6 = $payment['field6'] ?? null;
        $this->field7 = $payment['field7'] ?? null;
        $this->field8 = $payment['field8'] ?? null;
        $this->field9 = $payment['field9'] ?? null;
        $this->me_code = $payment['meCode'] ?? null;
        $this->save();
    }

    /**
     * @param array $payment
     */
    public function updateCompletedPayment(array $payment)
    {
        $this->mihpayid = $payment['paymentId'];
        $this->status = $payment['status'];
        $this->mode = $payment['paymentMode'];
        $this->productinfo = $payment['productInfo'];
        $this->additional_charges = $payment['additionalCharges'] ?? ($payment['additional_charges'] ?? null);
        $this->bank_ref_num = $payment['bankRefNum'] ?? null;
        $this->error_message = $payment['error_Message'] ?? null;
        $this->net_amount_debit = ($payment['status'] == 'success')? ($payment['amount']+($payment['additionalCharges'] ?? ($payment['additional_charges']))):'0.00';
        $this->field0 = $payment['field0'] ?? null;
        $this->field1 = $payment['field1'] ?? null;
        $this->field2 = $payment['field2'] ?? null;
        $this->field3 = $payment['field3'] ?? null;
        $this->field4 = $payment['field4'] ?? null;
        $this->field5 = $payment['field5'] ?? null;
        $this->field6 = $payment['field6'] ?? null;
        $this->field7 = $payment['field7'] ?? null;
        $this->field8 = $payment['field8'] ?? null;
        $this->field9 = $payment['field9'] ?? null;
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