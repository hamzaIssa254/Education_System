<?php

namespace App\Http\Controllers;

use Srmklive\PayPal\Services\PayPal as PayPalClient;
use Illuminate\Http\Request;

class PayPalController extends Controller
{
    public function index()
    {
        return view('auth.paypal');
    }

    public function payment(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));


        $paypalToken = $provider->getAccessToken();
        dd($paypalToken);
if (!$paypalToken) {
    return redirect()->route('paypal')->with('error', 'Failed to get PayPal access token.');
}

$response = $provider->createOrder([
    "intent" => "CAPTURE",
    "application_context" => [
        "return_url" => route('paypal.payment.success'),
        "cancel_url" => route('paypal.payment.cancel'),
    ],
    "purchase_units" => [
        0 => [
            "amount" => [
                "currency_code" => "USD",
                "value" => "100.00"
            ]
        ]
    ]
]);

dd($response); // Debug the response here

if (isset($response['links'])) {
    foreach ($response['links'] as $links) {
        if ($links['rel'] == 'approve') {
            return redirect()->away($links['href']);
        }
    }
} else {
    return redirect()->route('paypal')->with('error', 'Approval link not found.');
}
    }

    public function paymentCancel()
    {
        return redirect()
            ->route('paypal')
            ->with('error', 'You have canceled the transaction.');
    }

    public function paymentSuccess(Request $request)
    {
        $provider = new PayPalClient;
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $response = $provider->capturePaymentOrder($request['token']);

        if (isset($response['status']) && $response['status'] == 'COMPLETED') {
            return redirect()
                ->route('paypal')
                ->with('success', 'Transaction complete.');
        } else {
            return redirect()
                ->route('paypal')
                ->with('error', $response['message'] ?? 'Something went wrong.');
        }
    }
}
