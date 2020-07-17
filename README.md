# Payu
A simple Laravel library for Payu India Payment Gateway

## Installation
Add the following lines to your composer.json
```
....
"repositories": [
    {
        "type": "git",
        "url": "https://github.com/nk-square/payu.git"
    }
],
.....
```
Run composer
```
composer require nksquare\payu
```
Publish config file
```
php artisan vendor:publish --provider="Nksquare\Payu\PayuServiceProvider" --tag="config"
```
Create migrations and model factory if you are going to store the payment records in the database
```
php artisan payu:table
```
Run migrations
```
php artisan migrate
```
Generate fake data using the following code
```php
use Nksquare\Payu\PayuPayment;
.....
factory(PayuPayment::class)->make();
```
Set up your key, salt, account type(money or biz) and auth header(required for money)
```
PAYU_KEY=your_key
PAYU_SALT=your_salt
PAYU_TYPE=money
PAYU_AUTH_HEADER=your_auth_header
```
## Usage
Set up your routes in routes/web.php
```php
Route::get('payment','PaymentController@payment');
Route::post('payment/success','PaymentController@success');
Route::post('payment/failure','PaymentController@failure');
```
Exclude your success and failure routes from csrf check. Open the app/Http/Middleware/VerifyCsrfToken.php and add the following urls.
```php
class VerifyCsrfToken extends Middleware
{
    /**
     * Indicates whether the XSRF-TOKEN cookie should be set on the response.
     *
     * @var bool
     */
    protected $addHttpCookie = true;

    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array
     */
    protected $except = [
        'payment/success',
        'payment/failure'
    ];
}
```
Set up your controller
```php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Nksquare\Payu\PayuPayment;
use Payu;

class PaymentController extends Controller
{
    public function payment()
    {
        $gateway = Payu::getGateway()->setSurl(url('payment/success'))->setFurl(url('payment/failure'));
        $gateway->setPayment([
            'amount' => 1,
            'email' => 'buyer@email.com',
            'phone' => '1234567890',
            'firstname' => 'John',
            'productinfo' => 'electronics',
            'lastname' => 'Doe' //optional
        ]);
        //if you want to save the payment to database
        $payuPayment = PayuPayment::savePayment($gateway->getPayment());
        return $gateway->getPaymentForm();
    }

    public function success(Request $request)
    {
        if(Payu::getGateway()->verifyReverseHash($request->all(),$request->hash))
        {
            //update database if you have saved the record earlier using PayuPayment::savePayment()
            $payuPayment = PayuPayment::getByTxnid($request->txnid);
            $payuPayment->completePayment($request->all());
            echo 'success';
        }
        else
        {
            echo 'invalid hash';
            exit(0);
        }
    }

    public function failure(Request $request)
    {
        if(Payu::getGateway()->verifyReverseHash($request->all(),$request->hash))
        {
            //update database if you have saved the record earlier using PayuPayment::savePayment()
            $payuPayment = PayuPayment::getByTxnid($request->txnid);
            $payuPayment->completePayment($request->all());
            echo 'error';
        }
        else
        {
            echo 'invalid hash';
            exit(0);
        }
    }
}
```
