<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithHeadings;
use App\Models\Product;
use App\Models\ProductTag;
use App\Models\ProductVariant;
use Auth;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

class ProductsRecordExport implements WithMultipleSheets, WithChunkReading,ShouldQueue
{
    use Exportable, Queueable;

    protected $standard;
    protected $composite;
    protected $images;
    protected $stock;

    public function __construct($standard,$composite,$images,$stock)
    {
        $this->standard     = $standard;
        $this->composite   = $composite;
        $this->images       = $images;
        $this->stock       = $stock;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets[] = new StandardSheet($this->standard);
        $sheets[] = new CompositeSheet($this->composite);
        $sheets[] = new ImagesSheet($this->images);
        $sheets[] = new StockSheet($this->stock);

        return $sheets;
    }
    public function chunkSize(): int
    {
        return 200;
    }
}


class StandardSheet implements  WithTitle,WithHeadings,ShouldAutoSize, WithChunkReading,ShouldQueue
{
    private $standard;

    public function __construct($standard)
    {
        $this->standard = $standard;

    }
    public function headings(): array
    {
        return $this->standard;
    }

    /**
     * @return Builder
     */

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Standard';
    }
    public function chunkSize(): int
    {
        return 200;
    }

}
class CompositeSheet implements  WithTitle,WithHeadings,ShouldAutoSize, WithChunkReading,ShouldQueue
{
    private $composite;

    public function __construct($composite)
    {
        $this->composite = $composite;

    }
    public function headings(): array
    {
        return $this->composite;
    }

    /**
     * @return Builder
     */

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Composite';
    }
    public function chunkSize(): int
    {
        return 200;
    }


}
class ImagesSheet implements  WithTitle,WithHeadings,ShouldAutoSize, WithChunkReading,ShouldQueue
{
    private $images;

    public function __construct($images)
    {
        $this->images = $images;

    }
    public function headings(): array
    {
        return $this->images;
    }

    /**
     * @return Builder
     */

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Images';
    }
    public function chunkSize(): int
    {
        return 200;
    }

}
class StockSheet implements  WithTitle,WithHeadings,ShouldAutoSize, WithChunkReading,ShouldQueue
{
    private $stock;

    public function __construct($stock)
    {
        $this->stock = $stock;

    }
    public function headings(): array
    {
      return $this->stock;
    }

    /**
     * @return Builder
     */

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Stock';
    }

    public function chunkSize(): int
    {
        return 200;
    }
}
