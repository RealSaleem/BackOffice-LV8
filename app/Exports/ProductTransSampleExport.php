<?php

namespace App\Exports;

use App\Exports\ProductLanguageSheet;
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

class ProductTransSampleExport implements WithMultipleSheets
{
    use Exportable;

    protected $lang;
    protected $products;
    protected $categories;
    protected $brands;

    public function __construct($lang,$products,$categories,$brands)
    {
        $this->lang         = $lang;
        $this->products     = $products;
        $this->categories   = $categories;
        $this->brands       = $brands;
    }

    /**
     * @return array
     */
    public function sheets(): array
    {
        // dd($this->lang,$this->categories,$this->brands,$this->products);
        $sheets[] = new ProductLanguageSheet($this->lang,$this->products);
        $sheets[] = new CategoryLanguageSheet($this->lang,$this->categories);
        $sheets[] = new BrandLanguageSheet($this->lang,$this->brands);

        return $sheets;
    }

}


class ProductLanguageSheet implements  WithTitle,WithHeadings,ShouldAutoSize
{
    private $lang;
    private $products;

    public function __construct($lang,$products)
    {
        $this->lang = $lang;
        $this->products = $products;

    }
    public function headings(): array
    {
         array_unshift($this->products, [
            'sku',
            'name',
            'Translated name',
            'description',
            'HasVariant',
            'Attribute 1',
            'Attribute 1 Value',
            'Attribute 2',
            'Attribute 2 Value',
            'Attribute 3',
            'Attribute 3 Value',
            'MetaTitle',
            'MetaKeywords',
            'MetaDescription',
        ]);


      return $this->products;
    }

    /**
     * @return Builder
     */

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Product ' . $this->lang;
    }
}
class CategoryLanguageSheet implements  WithTitle,WithHeadings,ShouldAutoSize
{
    private $lang;
    private $categories;

    public function __construct($lang,$categories)
    {
        $this->lang = $lang;
        $this->categories = $categories;

    }
    public function headings(): array
    {
         array_unshift($this->categories, [
            'name',
            'Translated name',
            'MetaTitle',
            'MetaKeywords',
            'MetaDescription',
        ]);


      return $this->categories;
    }

    /**
     * @return Builder
     */

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Category ' . $this->lang;
    }
}
class BrandLanguageSheet implements  WithTitle,WithHeadings,ShouldAutoSize
{
    private $lang;
    private $brand;

    public function __construct($lang,$brands)
    {
        $this->lang = $lang;
        $this->brands = $brands;

    }
    public function headings(): array
    {
         array_unshift($this->brands, [
            'name',
            'Translated name',
            'MetaTitle',
            'MetaKeywords',
            'MetaDescription',
        ]);


      return $this->brands;
    }

    /**
     * @return Builder
     */

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Brand ' . $this->lang;
    }
}
