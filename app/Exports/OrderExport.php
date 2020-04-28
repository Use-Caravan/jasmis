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
use App\Order;
use DB;

class OrderExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{    
    use Exportable;

    public function collection()
    {
        DB::statement(DB::raw('set @serial=0'));
        return Order::getReports()->addSelect([
            DB::raw('@serial := @serial+1 AS `serial_number`'),
        ])->get();                
    }
    

    /**
    * @var Invoice $invoice
    */
    public function map($order): array
    {           
        return  [
            'S.no' => $order->serial_number,
            'Order Number' => $order->order_number,
            'Vendor Name'  => $order->vendor_name,
            'Branch Name' => $order->branch_name,
            'Order Status'  => $order->approvedStatus($order->order_status),
            'Order Date' => $order->order_datetime
        ];        
    }

    public function headings(): array
    {
        return [
            'S.No',
            'Order Number',
            'Vendor Name',
            'Branch Name',
            'Order Status',
            'Order Date',
        ];
    }
}