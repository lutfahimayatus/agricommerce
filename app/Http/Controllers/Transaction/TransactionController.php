<?php

namespace App\Http\Controllers\Transaction;

use App\Http\Controllers\Controller;
use App\Http\Requests\Transaction\ProofOfTransactionRequest;
use App\Http\Resources\Transaction\TransactionCollection;
use App\Http\Resources\Transaction\TransactionResource;
use App\Models\Transaction;
use Illuminate\Support\Facades\Storage;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = auth()->user()->transactions()->latest()->get();

        return new TransactionCollection($transactions);
    }

    public function submit(Transaction $transaction, ProofOfTransactionRequest $request)
    {
        abort_if($transaction->user_id !== auth()->user()->id, 403);
        abort_if($transaction->status !== 'NOT_PAID', 422);
        abort_if($transaction->proof_of_transaction !== null, 422, 'Bukti pembayaran telah diunggah');

        $path = Storage::putFile('public/proofs_of_transaction', $request->file('proof_of_transaction'));

        $transaction->proof_of_transaction = str_replace('public/', '', $path);
        $transaction->save();

        return new TransactionResource($transaction);
    }
}
