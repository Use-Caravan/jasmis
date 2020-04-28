<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\{
    Exportable,
    FromCollection,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
};
use App\NewsletterSubscriber;
use DB;

class NewsletterExport implements FromCollection, WithMapping, WithHeadings, ShouldAutoSize
{    
    use Exportable;

    public function collection()
    {
        DB::statement(DB::raw('set @serial=0'));
        return NewsletterSubscriber::select([
            DB::raw('@serial := @serial+1 AS `serial_number`'),
            'newsletter_subscriber.*',            
        ])->get();
    }

    /**
    * @var Invoice $invoice
    */
    public function map($subscriber): array
    {        
        return  [
            'S.no' => $subscriber->serial_number,
            'Name'  => $subscriber->name,
            'Email'  => $subscriber->email,
            'Status'  => ($subscriber->status == ITEM_ACTIVE) ? 'Active' : 'Inactive',
            'Subscribed Date' => $subscriber->created_at
        ];        
    }

    public function headings(): array
    {
        return [
            'S.No',
            'Name',
            'Email',
            'Status',
            'Subscribed Date',
        ];
    }
}