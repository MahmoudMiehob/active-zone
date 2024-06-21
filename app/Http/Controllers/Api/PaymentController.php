<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Api\ApiResponseTrait;
use App\Models\Reservation;
use App\Models\Transaction;
use App\Services\ClickPayIntegrationService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    use ApiResponseTrait;


    public function processReservationPayment(Request $request, int $id)
    {
        $reservation = Reservation::findOrFail($id);
        $paymentProcess = new ClickPayIntegrationService(auth()->user(), $reservation, $request->ip());

        $data = $paymentProcess->pay();

        if (is_null($data))
            return $this->apiResponse(null, 'Could not create pay page', 500);

        return $this->apiResponse($data, 'Pay page created successfully', 200);
    }

    public function processReservationRefund(Request $request, int $id)
    {
        $reservation = Reservation::findOrFail($id);
        $paymentProcess = new ClickPayIntegrationService(auth()->user(), $reservation, $request->ip());

        $data = $paymentProcess->refund();

        if (is_null($data))
            return $this->apiResponse(null, 'Could not refund payment', 500);

        $message = $data['message'];
        unset($data['message']);
        return $this->apiResponse($data, $message, 200);
    }
}
