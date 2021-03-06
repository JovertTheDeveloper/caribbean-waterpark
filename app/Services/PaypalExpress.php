<?php

namespace App\Services;

use Carbon;
use Illuminate\Support\Facades\Storage;
use Srmklive\PayPal\Services\ExpressCheckout;

class PaypalExpress
{
    /**
    * @var ExpressCheckout Instance
    */
    private $provider;

    public function __construct()
    {
        $this->provider = new ExpressCheckout();
    }

    public function redirect($reservation)
    {
        $cart = $this->getCheckoutData($reservation);

        return $this->provider->setExpressCheckout($cart, false);
    }

    public function callback($reservation, $token, $payer_id)
    {
        $cart = $this->getCheckoutData($reservation);

        // Verify Express Checkout Token
        $response = $this->provider->getExpressCheckoutDetails($token);

        $status = 'error';

        if (isset($response['ACK'])) {
            if (in_array(strtoupper($response['ACK']), ['SUCCESS', 'SUCCESSWITHWARNING'])) {
                // Perform transaction on PayPal
                $payment_status = $this->provider->doExpressCheckoutPayment($cart, $token, $payer_id);
                $status = $payment_status['PAYMENTINFO_0_PAYMENTSTATUS'];
            }
        }

        return $status;
    }

    protected function getCheckoutData($reservation)
    {
        $items = collect([]);

        $reservation->items->each(function($item) use ($reservation, $items) {
            $days = Carbon::parse($reservation->checkin_date)
                        ->diffInDays(Carbon::parse($reservation->checkout_date)) + 1;

            $items->push([
                'name' => $item->item->name,
                'price' => $item->price_original * $days,
                'qty' => $item->quantity
            ]);
        });

        $item_totals = 0;

        foreach ($items as $item) {
            $item_totals += $item['price'] * $item['qty'];
        }

        return [
            'return_url' => route('front.reservation.paypal.callback', $reservation),
            'cancel_url' => route('front.reservation.cart.index'),
            'invoice_id' => uniqid(),
            'invoice_description' => 'Transaction #'.$reservation->id,
            'items' => $items->toArray(),
            'total' => $item_totals
        ];
    }
}