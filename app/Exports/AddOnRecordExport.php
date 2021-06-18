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


class AddOnRecordExport implements WithMultipleSheets
{
    use Exportable;

    protected $lang;


    public function __construct($lang)
    {
        $this->lang         = $lang;

    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets[] = new AddOnExport();

        return $sheets;
    }

}



class AddOnExport implements FromCollection, WithHeadings, ShouldAutoSize
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
            'id',
            'store_id',
            'name',
            'type',
            'language_key',
            'is_active',
            'created_at',
            'updated_at',
            'identifier',
            'max' ,
            'min' ,
            'code',

        ];
    }
    public function title(): string
    {
        return 'AddOn ' . $this->lang;
    }
}
