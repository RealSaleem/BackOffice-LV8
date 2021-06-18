<?php

namespace App\Imports\Translations;

use App\ProductImport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use Maatwebsite\Excel\Concerns\WithConditionalSheets;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Imports\Translations\CategoryTrans;
use App\Imports\Translations\ProductTrans;
use App\Imports\Translations\BrandTrans;
use Session;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Redirect;

class ProductsTransImport implements WithMultipleSheets, SkipsUnknownSheets
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    use WithConditionalSheets;

    protected $lang;
    
    public function __construct($lang)
    {
        $this->lang = $lang;
    }

   public function conditionalSheets(): array
    {
        Session::forget('sheet_skip');
        return [
            'Product ' .$this->lang  => new ProductTrans($this->lang),
            'Category ' .$this->lang => new CategoryTrans($this->lang),
            'Brand ' .$this->lang    => new BrandTrans($this->lang),
        ];
    }
    public function onUnknownSheet($sheetName)
    {
        if(isset($sheetName)){
            $error = [];
            $me = 'Please verify sheets name. Upload the sheet having '.$sheetName;
            // array_push($error, $me);
            // return Redirect::back()->withErrors($error);
            Session::put('sheet_skip',$me);
            return;
        }
        // E.g. you can log that a sheet was not found.
        // info("Sheet {$sheetName} was skipped");
    }
}