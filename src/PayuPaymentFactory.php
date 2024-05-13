<?php

namespace Nksquare\Payu;

use Nksquare\PayuPayment
use Carbon\Carbon;
use Faker\Generator as Faker;

class PayuPaymentFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = PaymentPayu::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
    	$amount = rand(100,1000);
    	$additionalCharges = mt_rand(1,20);
        return [
            'txnid' => uniqid(),
	        'key' => 'fakekey',
	        'amount' => $amount,
	        'email' => $this->faker->email,
	        'phone' => $this->faker->numerify('##########'),
	        'firstname' => $this->faker->firstName,
	        'mihpayid' => $this->faker->numerify('##############'),
	        'net_amount_debit' => $amount + $additionalCharges,
	        'status' => $this->faker->randomElement(['success','failure','pending']),
	        'unmappedstatus' => $this->faker->randomElement(['captured','bounced']),
	        'mode' => $this->faker->randomElement(['CC','NB','DB']),
	        'bank_ref_num' => $this->faker->numerify('##########'),
	        'bankcode' => $this->faker->randomElement(['VISA','MAST']),
	        'cardnum' => $this->faker->numerify('############'),
	        'issuing_bank' => $this->faker->randomElement(['AXIS','SBI']),
	        'card_type' => $this->faker->randomElement(['CC','DD']),
	        'additional_charges' => $additionalCharges,
			'udf1' => $this->faker->word(),
			'udf2' => $this->faker->word(),
			'udf3' => $this->faker->word(),
			'udf4' => $this->faker->word(),
			'udf5' => null,
			'udf6' => null,
			'udf7' => null,
			'udf8' => null,
			'udf9' => null,
			'udf10' => null,
			'field1' => null,
			'field2' => null,
			'field3' => null,
			'field4' => null,
			'field5' => null,
			'field6' => null,
			'field7' => null,
			'field8' => null,
			'field9' => null,
			'error' => $this->faker->word(),
			'error_message' => $this->faker->words(5,true),
			'addedon' => now()->toDateTimeString(),
        ];
    }

    public function success()
    {
    	return $this->state(function($attributes){
    		return [
    			'status' => 'success',
    			'error' => null,
    			'error_message' => null,
    			'unmappedstatus' => 'captured',
    			'verified_at' => now(),
    		];
    	});
    }

    public function failure()
    {
    	return $this->state(function($attributes){
    		return [
    			'status' => 'failure',
    			'net_amount_debit' => 0,
    			'additional_charges' => 0,
    			'unmappedstatus' => 'bounced',
    			'verified_at' => now(),
    		];
    	});
    }
}
