<?php

namespace App\Exports;

use App\Models\Transaction;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class TransactionExport implements FromView, WithColumnWidths
{
    public function view(): View
    {

        $transactions = Transaction::where('user_id',Auth::id())->get();

        // if ($transactions->isEmpty()) {
        //     abort(404, 'No transactions found');
        // }

        return view('templates.basic.user.seller.transaction_export', [
            'transactions' => $transactions
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 20,
            'B' => 20,
            'C' => 20,
            'D' => 25,
            'E' => 20,
            'F' => 20,
        ];
    }
}
