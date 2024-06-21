<?php

namespace App\Services;

use App\Models\Reservation;
use App\Models\Transaction;
use Clickpaysa\Laravel_package\paypage;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Http;

class ClickPayIntegrationService
{
    protected Authenticatable $customer;
    protected Reservation $reservation;
    protected string $ip;
    protected string $language;

    public function __construct(Authenticatable $user, Reservation $reservation, $ip, $language = 'ar')
    {
        $this->ip = $ip;
        $this->customer = $user;
        $this->reservation = $reservation;
        $this->language = $language;
    }

    public static function queryTransaction($transaction)
    {
        $url = 'https://secure.clickpay.com.sa/payment/query';
        $headers = [
            'authorization' => config('clickpay.server_key'),
        ];

        $body = [
            'profile_id' => config('clickpay.profile_id'),
            'tran_ref' => $transaction->tran_ref,
        ];

        $response = Http::withHeaders($headers)->post($url, $body);
        $result = json_decode($response->body(), true);

        $success = true;
//        dd($transaction->tran_ref);
        if (isset($result['code']))
            $success = false;
        $result['success'] = $success;
        return $result;
    }

    private function fillTransaction(Transaction $transaction, $pay)
    {
        $transaction->update([
            'tran_ref' => $pay['tran_ref'],
            'tran_type' => $pay['tran_type'],
            'reservation_id' => $this->reservation->id,
            'user_id' => auth()->user()->id,
            'cart_amount' => $pay['cart_amount'],
        ]);
    }

    public function pay()
    {
        $transaction = Transaction::create();

        $payPageCreationUrl = "https://secure.clickpay.com.sa/payment/request";
        $customer = $this->customer;
        $headers = [
            'authorization' => config('clickpay.server_key'),
        ];

        $body = [
            "profile_id" => config('clickpay.profile_id'),
            "tran_type" => "sale",
            "tran_class" => "ecom",
            "cart_id" => 'reservation' . $this->reservation->id,
            "cart_currency" => config('clickpay.currency'),
            "cart_amount" => $this->reservation->total_price,
            "cart_description" => "Payment of reservation #{$this->reservation->id}",
            "paypage_lang" => config('clickpay.language'),
            "customer_details" => [
                "name" => $customer->name,
                "email" => $customer->email,
                "phone" => $customer->postal_code . $customer->phone,
                // "street1" => "address street",
                // "city" => "dubai",
                // "state" => "du",
                // "country" => "SA",
                // "zip" => "12345",
                "ip" => $this->ip,
            ],
            "callback" => url('/transaction/setresult/' . $transaction->id),
//            "return" => "https://webhook.site/15ed4460-a50a-4489-aede-a9d971caf1b2/return",
        ];

        $response = Http::withHeaders($headers)->post($payPageCreationUrl, $body);

        $result = json_decode($response->body(), true);

        if (isset($result['code']))
            return null;

        $this->fillTransaction($transaction, $result);

        return [
            'transaction_id' => $transaction->id,
            'redirect_url' => $result['redirect_url'],
        ];

    }

    public function refund()
    {
        if (!$this->reservation->paid)
            return null;

        $transaction = Transaction::create();

        $url = "https://secure.clickpay.com.sa/payment/request";
        $headers = [
            'authorization' => config('clickpay.server_key'),
        ];

        $customer = $this->customer;
        $body = [
            "profile_id" => config('clickpay.profile_id'),
            "tran_type" => "refund",
            'tran_ref' => $this->reservation->tran_ref,
            "tran_class" => "ecom",
            "cart_id" => 'reservation' . $this->reservation->id,
            "cart_currency" => config('clickpay.currency'),
            "cart_amount" => $this->reservation->total_price,
            "cart_description" => "Refund for payment of reservation #{$this->reservation->id}",
            "paypage_lang" => config('clickpay.language'),
//            "customer_details" => [
//                "name" => $customer->name,
//                "email" => $customer->email,
//                "phone" => $customer->postal_code . $customer->phone,
//                // "street1" => "address street",
//                // "city" => "dubai",
//                // "state" => "du",
//                // "country" => "SA",
//                // "zip" => "12345",
//                "ip" => $this->ip,
//            ],
            "callback" => url('/transaction/setresult/' . $transaction->id),
//            "return" => "https://webhook.site/15ed4460-a50a-4489-aede-a9d971caf1b2/return",
        ];

        $response = Http::withHeaders($headers)->post($url, $body);

        $result = json_decode($response->body(), true);

        if (isset($result['code']))
            return null;

        $tran_status = $result['payment_result']['response_status'];
        $success = false;
        if ($tran_status == 'A') {
            $success = true;
            $message = 'Authorised';
            $this->reservation->update([
                'paid' => false,
                'tran_ref' => null,
            ]);
        } elseif ($tran_status == 'H') {
            $message = 'Authorised but on hold for further anti-fraud review';
        } elseif ($tran_status == 'P') {
            $message = 'Pending';
        } else {
            $message = 'Transition voided or declined, or there is an error';
        }
        return [
            'success' => $success,
            'tran_status' => $tran_status,
            'message' => $message,
        ];
    }

}
