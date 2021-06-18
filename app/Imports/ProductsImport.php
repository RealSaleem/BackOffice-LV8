<?php

namespace App\Imports;

use App\ProductImport;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use Maatwebsite\Excel\Concerns\WithConditionalSheets;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Imports\Composite;
use App\Imports\Standard;
use App\Imports\Images;
use App\Imports\Stock;
use Maatwebsite\Excel\Concerns\SkipsUnknownSheets;
use Session;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Bus\Queueable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\WithEvents;
class ProductsImport implements WithMultipleSheets, SkipsUnknownSheets,WithHeadingRow, WithBatchInserts, WithEvents//, WithChunkReading,  ShouldQueue
{
    use Importable, RegistersEventListeners;
    private $lang;
    private $stock;
    private $user;

    public function __construct($lang,$stock,$user)
    {
        $this->lang = $lang;
        $this->stock = $stock;
        $this->user = $user;

    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    use WithConditionalSheets;

    public function conditionalSheets(): array
    {
        //thic will import multiple sheets like three for now, if you want to add more sheet you need to follow the following pattern
        //standard and composite needs the language key and stock add or update signal
        Session::forget('sheet_skip');
         ini_set('max_execution_time', 300);
        
        return [
            'Standard'  => new Standard($this->lang, $this->stock,$this->user),
            'Composite' => new Composite($this->lang, $this->stock,$this->user),
            'Images'    => new Images($this->user),
            'Stock'     => new Stock($this->stock,$this->user),
        ];
    }
    public function onUnknownSheet($sheetName)
    {
        if(isset($sheetName)){
            $error = [];
            $me = 'Please verify sheets name. Upload the sheet having name '.$sheetName;
            Session::put('sheet_skip',$me);
            return;
        }
    }
    public function batchSize(): int
    {
        return 1000;
    }

    /**
     * @return int
     */
    // public function chunkSize(): int
    // {
    //     return 1000;
    // }



}
