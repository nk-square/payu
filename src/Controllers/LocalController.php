<?php

namespace Nksquare\Payu\Controllers;

use App\Models\PaymentPayu;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Validator;

class LocalController extends Controller
{
	public function verify(Request $request)
	{
		$errors = Validator::make($request->all(),[
			'amount' => 'required|numeric',
			'hash' => 'required',
			'firstname' => 'required',
			'email' => 'required|email',
			'phone' => 'required',
			'productinfo' => 'required'
		])->errors();
		
		if($errors->any())
		{
			return view('payu::local.errors',[
				'errors' => $errors,
			]);
		}
		$request->session()->put('_payu',$request->all());
		return redirect()->route('payu.local.payment');
	}

	public function payment(Request $request)
	{
		$request->session()->get('_payu') or abort(401);
		$paymentPayu = [
			'txnid' => $request->session()->get('_payu.txnid'),
			'amount' => $request->session()->get('_payu.amount'),
			'net_amount_debit' => $request->session()->get('_payu.amount'),
			'firstname' => $request->session()->get('_payu.firstname'),
			'email' => $request->session()->get('_payu.email'),
			'phone' => $request->session()->get('_payu.phone'),
			'udf1' => $request->session()->get('_payu.udf1'),
			'udf2' => $request->session()->get('_payu.udf2'),
			'udf3' => $request->session()->get('_payu.udf3'),
			'udf4' => $request->session()->get('_payu.udf4'),
			'udf5' => $request->session()->get('_payu.udf5'),
			'productinfo' => $request->session()->get('_payu.productinfo'),
			'mihpayid' => mt_rand(),
			'unmappedstatus' => null,
			'hash' => null,
		];

		return view('payu::local.payment',[
			'surl' => $request->session()->get('_payu.surl'),
			'furl' => $request->session()->get('_payu.furl'),
			'paymentPayu' => $paymentPayu
		]);
	}
}