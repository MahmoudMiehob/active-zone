<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Transaction;
use App\Services\ClickPayIntegrationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransactionController extends Controller
{
    use ApiResponseTrait;

    public function getResult(Request $request, int $id)
    {
        $validator = Validator::make($request->all(), [
            'reservation_id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return $this->apiResponse(null, $validator->errors()->first(), 400);
        }

        $transaction = Transaction::findOrFail($id);
        $reservation = Reservation::findOrFail($request->reservation_id);

        if (is_null($transaction->tran_ref))
            return $this->apiResponse(['success' => false], 'Transaction has not been completed', 500);

        $tran_res = ClickPayIntegrationService::queryTransaction($transaction);

        if (!$tran_res['success'])
            return $this->apiResponse($tran_res, $tran_res['message'], 500);

        $tran_status = $tran_res['payment_result']['response_status'];
        $tran_type = $tran_res['tran_type'];
        $tran_ref = $tran_res['tran_ref'];
        $success = false;
        if ($tran_status == 'A') {
            $success = true;
            $message = 'Authorised';
            $paid = ($tran_type == 'Sale');
            $tran_ref = $paid ? $tran_ref : null;
            $reservation->update([
                'paid' => $paid,
                'tran_ref' => $tran_ref,
            ]);
        } elseif ($tran_status == 'H') {
            $message = 'Authorised but on hold for further anti-fraud review';
        } elseif ($tran_status == 'P') {
            $message = 'Pending';
        } else {
            $message = 'Transition voided or declined, or there is an error';
        }

        return $this->apiResponse([
            'success' => $success,
            'tran_status' => $tran_status,
        ], $message, 200);
    }
}
