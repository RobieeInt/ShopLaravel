<?php

namespace App\Http\Controllers\API;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Helpers\ResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\TransactionDetail;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function all (Request $request) {

        $id = $request->input('id');
        $limit = $request->input('limit', 5);
        $status = $request->input('status');

        if($id) {
            $transaction  = Transaction::with(['items.product'])->find($id);

            if($transaction){
                return ResponseFormatter::success(
                    $transaction,
                    ['Transaksi berhasil ditampilkan']
                );
            } else {
                return ResponseFormatter::error(
                    null,
                    ['Transaksi tidak ditemukan'],
                    404
                );
            }
        }

        $transactions = Transaction::with(['items.product'])->where('users_id', Auth::user()->id);

        if($status) {
            $transactions = $transactions->where('status', $status);
        }

        return ResponseFormatter::success(
            $transactions->paginate($limit),
            ['Transaksi berhasil ditampilkan']
        );

    }

    public function checkout (Request $request) {

        $request->validate([
            'address' => 'required',
            'items' => 'required|array', /* array */
            'items.*.id' => 'required|exists:products,id', /* ini untuk validasi bahwa id product yang diinputkan ada di database */
            'payment_method' => 'required',
            'payment_status' => 'required|in:pending,success,failed',
            'total_price' => 'required',
            'shipping_price' => 'required',
        ]);

        $transaction = Transaction::create([
            'users_id' => Auth::user()->id,
            'address' => $request->input('address'),
            'payment_method' => $request->input('payment_method'),
            'payment_status' => 'pending',
            'payment_code' => '',
            'total_price' => $request->input('total_price'),
            'shipping_price' => $request->input('shipping_price'),
            'shipping_status' => 'pending',
            'shipping_code' => '',
            'shipping_type' => $request->input('shipping_type'),
            'shipping_service' => $request->input('shipping_service'),
        ]);

        foreach($request->input('items') as $item) {
            TransactionDetail::create([
                'user_id' => Auth::user()->id, /* ini untuk mengambil id user yang login */
                'transaction_id' => $transaction->id, /* ini untuk mengambil id transaksi yang baru dibuat */
                'product_id' => $item['id'], /* ini untuk mengambil id product yang diinputkan */
                'qty' => $item['qty'], /* ini untuk mengambil quantity yang diinputkan */
            ]);
        }

        if ($transaction) {
            return ResponseFormatter::success(
                $transaction->load('items.product'),
                ['Transaksi berhasil dibuat']
            );
        } else {
            return ResponseFormatter::error(
                null,
                ['Transaksi gagal dibuat'],
                500
            );
        }
    }
}
