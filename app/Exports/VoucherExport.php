<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\{
    Exportable,
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize,
    FromQuery
};
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Order;
use DB;

class VoucherExport implements FromView
{    
    use Exportable;

    public function view(): View
    {
        /* return ''; */
        return view('exports.corporate-voucher', [
            'invoices' => []
        ]);
    }
}