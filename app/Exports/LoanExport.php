<?php

namespace App\Exports;

use App\Models\Loan;
use App\Http\Resources;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Events\AfterSheet;

class LoanExport implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    /**
     * @return \Illuminate\Support\Collection
     */
    use Exportable;

    protected $loans;


    public function __construct($loans)
    {
        $this->loans=$loans;
    }
//
//    public function array(): array
//    {
//        // TODO: Implement array() method.
//        return $this->loans;
//    }

    public function collection()
    {
//        $loans=Loan::all();
        return collect($this->loans);
    }

    public function headings(): array
    {
        // TODO: Implement headings() method.
        return[
            '#',
            'First Name',
            'Middle Name',
            'Last Name',
            'Phone Number Mobile',
            'Phone Number Work',
            'Position',
            'Email',
            'National Id',
            'Loan Amount',
            'Amount Due',
            'Borrowed Time',
            'Borrowed Date',
            'Due Date',
            'Status',
            'Employer',
        ];
    }

    public function registerEvents(): array
    {
        // TODO: Implement registerEvents() method.
        return[
            AfterSheet::class => function(AfterSheet $event){
                $cellRange='A1:W1';
                $event->sheet->getDelegate()->getStyle($cellRange)->getFont()->setSize(12);
            }
        ];
    }


}
