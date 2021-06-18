<?php

namespace App\Exports;

use App\Models\AddOn;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Illuminate\Contracts\Queue\ShouldQueue;


class AddOnSampleExport implements FromCollection, ShouldAutoSize, ShouldQueue, WithHeadings
{

     /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $addons = AddOn::where('store_id' , Auth::user()->store_id)
        ->orderBy('id','asc')
        ->limit(0)
        ->get();

        return $addons;
    }

    public function headings(): array
    {
        return [
            'name',
            'type',
            'language_key',
            'is_active',
            'max' ,
            'min' ,
            'code',
        ];
    }




}
