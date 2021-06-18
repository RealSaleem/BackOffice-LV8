<?php

namespace App\Imports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use Maatwebsite\Excel\Concerns\WithConditionalSheets;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;



use App\Imports\AddOn;
use App\Imports\ItemImport;

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

class AddonsImport implements  WithMultipleSheets, SkipsUnknownSheets,WithHeadingRow, WithBatchInserts, WithChunkReading, WithEvents, ShouldQueue
{
    use Importable, RegistersEventListeners;
    private $lang;
    private $stock;

    public function __construct($lang,$stock)
    {
        $this->lang = $lang;
        $this->stock = $stock;

    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    use WithConditionalSheets;

    public function conditionalSheets(): array
    {
        //this will import multiple sheets like three for now, if you want to add more sheet you need to follow the following pattern
        //standard and composite needs the language key and stock add or update signal
        Session::forget('sheet_skip');
        return [
            'AddOn'  => new AddOn($this->lang, $this->stock),
            'Item'   => new ItemImport($this->lang, $this->stock),
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
    public function chunkSize(): int
    {
        return 1000;
    }
}
