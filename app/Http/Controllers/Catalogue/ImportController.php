<?php

namespace App\Http\Controllers\Catalogue;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Core\RequestExecutor;
use Auth;
use Lang;
use App\Requests\Import\ImportProductRequest;
use Redirect;
use App\Helpers\ZipArchiveExtended;
use App\Imports\ProductsImport;
use App\Imports\Translations\ProductsTransImport;
use Session;
use App\Requests\Catalogue\Product\GetProductForExportRequest;

use App\Exports\ProductTransSampleExport;
use App\Exports\ProductsRecordExport;
use App\Exports\AddOnRecordExport;
use App\Exports\AddOnSampleExport;

use Maatwebsite\Excel\Facades\Excel;

use App\Models\Brand;
use App\Models\AddOn;
use App\Models\Category;
use App\Models\ProductVariant;
use App\Core\Response;
use App\Imports\AddonsImport;
use App\Jobs\ExportAddons;
use App\Requests\Catalogue\AddOn\GetAddOnForExportRequest;

class ImportController extends Controller
{
    public function __construct(RequestExecutor $requestExecutor)
    {
        // parent::__construct();
        $this->RequestExecutor = $requestExecutor;
        $this->middleware(['permission:import-import catalogue'])->only('index1','import','export');

    }

    public function index()
    {
        return view('catalogue.import.index');
    }

    public function index1()
    {
        return view('catalogue.import.addon-index');
    }



    public function import(Request $request)
    {
        $file = $request->file('imported-file');
        if ($file->getClientOriginalExtension() == 'xlsx') {
            $path = $request->file('imported-file')->getRealPath();
            try {

                $user = Auth::user();
                $import = new ProductsImport($request->language, $request->stock, $user);
                $import->onlySheets('Standard', 'Composite', 'Images', 'Stock');
                $data = Excel::import($import, $file);
                // ($import)->queue($file);
            } catch (\Exception $e) {
                dd($e);
            }

            $standard_response   = Session::get('Standard_Response');
            $composite_response  = Session::get('Composite_Response');
            $images_response     = Session::get('Images_Response');
            $stock_response     = Session::get('Stock_Response');
            $sheet_skip          = Session::get('sheet_skip');
            // dd($standard_response,$composite_response,$images_response,$stock_response,$sheet_skip);
            if (isset($sheet_skip) && $sheet_skip != null) {
                return back()->with('Exception', $sheet_skip);
            }

            if (isset($standard_response->IsValid) && $standard_response->IsValid == false) {
                if ($standard_response->Exception !== null) {
                    return redirect()->back()->with('Exception', $standard_response->Exception);
                } else {
                    return redirect()->back()->with('errors', $standard_response->Errors);
                }
            } else if (isset($composite_response->IsValid) && $composite_response->IsValid == false) {
                if ($composite_response->Exception !== null) {
                    return redirect()->back()->with('Exception', $composite_response->Exception);
                } else {
                    return redirect()->back()->with('errors', $composite_response->Errors);
                }
            } else if (isset($images_response->IsValid) && $images_response->IsValid == false) {
                if ($images_response->Exception !== null) {
                    return redirect()->back()->with('Exception', $images_response->Exception);
                } else {
                    return redirect()->back()->with('errors', $images_response->Errors);
                }
            } else if (isset($stock_response->IsValid) && $stock_response->IsValid == false) {
                if ($stock_response->Exception !== null) {
                    return redirect()->back()->with('Exception', $stock_response->Exception);
                } else {
                    return redirect()->back()->with('errors', $stock_response->Errors);
                }
            } else {
                return redirect()->back()->with('success', 'Excel has been uploaded successfully. Please upload product images.');
            }
        }
        return redirect()->back();
    }
    public function importTranslations(Request $request)
    {
        $file = $request->file('imported-file');
        if ($file->getClientOriginalExtension() == 'xlsx') {
            $path = $request->file('imported-file')->getRealPath();

            $import = new ProductsTransImport($request->language);
            $import->onlySheets('Product ' . $request->language, 'Category ' . $request->language, 'Brand ' . $request->language);
            $data = Excel::import($import, $file);

            $ProductTrans_Response   = Session::get('ProductTrans_Response');
            $CategoryTrans_Response  = Session::get('CategoryTrans_Response');
            $BrandTrans_Response     = Session::get('BrandTrans_Response');
            $sheet_skip = Session::get('sheet_skip');

            if (isset($sheet_skip) && $sheet_skip != null) {
                return back()->with('Exception', $sheet_skip);
            }

            if (isset($ProductTrans_Response->IsValid) && $ProductTrans_Response->IsValid == false) {
                if ($ProductTrans_Response->Exception !== null) {
                    return back()->with('Exception', $ProductTrans_Response->Exception);
                } else {
                    // Session::put('errors',$ProductTrans_Response->Errors['Product '.$request->language]);
                    return back()->with('errors', $ProductTrans_Response->Errors['Product ' . $request->language]);
                }
            } else if (isset($CategoryTrans_Response->IsValid) && $CategoryTrans_Response->IsValid == false) {
                if ($CategoryTrans_Response->Exception !== null) {
                    return back()->with('Exception', $CategoryTrans_Response->Exception);
                } else {
                    return back()->with('errors', $CategoryTrans_Response->Errors['Category ' . $request->language]);
                }
            } else if (isset($BrandTrans_Response->IsValid) && $BrandTrans_Response->IsValid == false) {
                if ($BrandTrans_Response->Exception !== null) {
                    return back()->with('Exception', $BrandTrans_Response->Exception);
                } else {
                    return back()->with('errors', $BrandTrans_Response->Errors['Brand ' . $request->language]);
                }
            } else {
                return back()->with('success', ucfirst($request->language_dis) . ' translation has been uploaded successfully');
            }
        }
        return redirect()->back();
    }

    public function images(Request $request)
    {
        $file = $request->file('imported-file');

        if ($file->getClientOriginalExtension() == 'zip') {
            $path = $request->file('imported-file')->getRealPath();

            $zip = new ZipArchiveExtended();

            $res = $zip->open($path);
            if ($res) {
                $zip->extractTo($_SERVER['DOCUMENT_ROOT'] . '/stores/public/storage/images/');
                $zip->close();
                return Redirect::back()->with('success', 'Images has been uploaded successfully. Please visit product list. ');
            } else {
                return Redirect::back()->with('errors', 'Fail to upload images');
            }
        }
        return back();
    }

    public function export(Request $request)
    {
        $response = new Response(true, null);

        $is_export = $request->has('export');
        $user = Auth::user();
        if ($is_export == false) {

            $request = new GetProductForExportRequest();
            $request->user = $user;
            $response = $this->RequestExecutor->execute($request);
        }

        if ($request->lang != null) {
            $res = $this->export_translated_data($request, $response, $user);

            return Excel::download($res, $user->store->name . ' ' . ucfirst($request->lang) . ' Translations.xlsx');
        }

        $res = $this->export_data($request, $response, $user);


        if ($is_export) {

            return Excel::download($res,  $user->store->name . ' Sample File.xlsx');
        }

        return Excel::download($res, $user->store->name . ' Products.xlsx');
    }

    public function importAddons(Request $request)
    {

        $file = $request->file('imported-file');
        if ($file->getClientOriginalExtension() == 'xlsx') {
            $path = $request->file('imported-file')->getRealPath();

            // $import = new ProductsImport($request->language, $request->stock);
            $import = new AddonsImport($request->language, $request->stock);
            $import->onlySheets('AddOn', 'Item');
            ($import)->queue($file);

            $addon_response   = Session::get('Addon_Response');
            $item_response    = Session::get('Item_Response');
            $sheet_skip       = Session::get('sheet_skip');

            if (isset($sheet_skip) && $sheet_skip != null) {
                return back()->with('Exception', $sheet_skip);
            }

            if (isset($addon_response->IsValid) && $addon_response->IsValid == false) {
                if ($addon_response->Exception !== null) {
                    return redirect()->back()->with('Exception', $addon_response->Exception);
                } else {
                    return redirect()->back()->with('errors', $addon_response->Errors);
                }
            } else if (isset($item_response->IsValid) && $item_response->IsValid == false) {
                if ($item_response->Exception !== null) {
                    return redirect()->back()->with('Exception', $item_response->Exception);
                } else {
                    return redirect()->back()->with('errors', $item_response->Errors);
                }
            } else {
                return redirect()->back()->with('success', 'Addon Excel Sheet has been uploaded successfully.');
            }
        }
        return redirect()->back();
    }

    public function exportAddons(Request $request)
    {
        // dd($request->language);
        $export = $request;
        $request = new GetAddOnForExportRequest();
        $response = $this->RequestExecutor->execute($request);


        if ($export->has('export')) {
            $addon_array = array();

            foreach ($response->Payload as $row) {
                array_push($addon_array, [
                    'id'              => $row->id,
                    'store_id'        => $row->store_id,
                    'name'            => $row->name,
                    'type'            => $row->type,
                    'language_key'    => $row->language_key,
                    'is_active'       => $row->is_active,
                    'created_at'      => $row->created_at,
                    'updated_at'      => $row->updated_at,
                    'identifier'      => $row->identifier,
                    'max'             => $row->max,
                    'min'             => $row->min,
                    'code'            => $row->code,

                ]);
            }

            return Excel::download(new AddOnSampleExport($request->lang, $addon_array), Auth::user()->store->name . '-AddonSample.xlsx');
        }

        return Excel::download(new AddOnRecordExport($request->lang), Auth::user()->store->name . '-AddonRecord.xlsx');
    }

    public function export_translated_data($request, $response, $user)
    {
        $product_array = array();
        $cat_array = array();
        $brand_array = array();

        foreach ($response->Payload as $row) {
            array_push($product_array, [
                'sku'                  => $row['sku'],
                'name'                 => $row['product']['name'],
            ]);
        }

        $categories = Category::where([['store_id', $user->store_id], ['is_deleted', 0]])->pluck('name')->toArray();
        foreach ($categories as $category) {
            array_push($cat_array, [
                'name'                 => $category,
            ]);
        }
        $brands = Brand::where([['store_id', $user->store_id], ['is_deleted', 0]])->pluck('name')->toArray();

        foreach ($brands as $brand) {
            array_push($brand_array, [
                'name'                 => $brand,
            ]);
        }
        return new ProductTransSampleExport($request->lang, $product_array, $cat_array, $brand_array);
    }
    public function export_data($request, $response, $user)
    {
        $standard_product_array  = [[
            "Name",
            "Description",
            "SKU",
            "Category",
            "Supplier",
            "Brand",
            "Active",
            "POS",
            "Web",
            "Top",
            "Featured",
            "Catalogue",
            "RetailPrice",
            "ComparePrice",
            "AllowOutOfStock",

            "HasVariant",
            "Attribute 1",
            "Attribute 1 Value",
            "Attribute 2",
            "Attribute 2 Value",
            "Attribute 3",
            "Attribute 3 Value",

            "MetaTitle",
            "MetaKeywords",
            "MetaDescription",
        ]];

        $composite_product_array = [[
            "CompositeName",
            "SKU",
            "VariantSKU",
            "Description",
            "Category",
            "QuantityOfVariants",
            "Active",
            "POS",
            "Web",
            "Top",
            "Featured",
            "Catalogue",
            "RetailPrice",
            "ComparePrice",
            "AllowOutOfStock",

            "MetaTitle",
            "MetaKeywords",
            "MetaDescription",
        ]];

        $images_array = [[
            'SKU',
            'URL 1',
            'URL 2',
            'URL 3',
            'URL 4',
            'URL 5',
        ]];

        $outlet_array = [
            'SKU',
        ];
        $stock_array = [];

        $outlets = $user->store->outlets->toArray();
        foreach ($outlets as $outlet_key => $outlet) {
            $out_name = str_replace(' ', '', $outlet['name']) . '-' . $outlet['id'];
            array_push(
                $outlet_array,
                $out_name . "-Stock",
                $out_name . "-SupplyPrice",
                $out_name . "-ReOrderPoint",
                $out_name . "-ReOrderQuantity"
            );
        }
        array_push($stock_array, $outlet_array);

        if ($response->Payload != null) {
            foreach ($response->Payload as $key => $variant) {
                $new_stock_array = [];
                for ($i = 1; $i <= count($outlets); $i++) {
                    foreach ($variant['product_stock'] as $key => $var_stock) {
                        $outlet_name = str_replace(' ', '', $var_stock['outlet']['name']) . '-' . $var_stock['outlet']['id'];

                        $new_stock_array['SKU']                               = $variant['sku'];
                        $new_stock_array[$outlet_name . '-Stock']             = $var_stock['quantity'];
                        $new_stock_array[$outlet_name . '-SupplyPrice']       = $var_stock['cost_price'];
                        $new_stock_array[$outlet_name . '-ReOrderPoint']      = $var_stock['re_order_point'];
                        $new_stock_array[$outlet_name . '-ReOrderQuantity']   = $var_stock['re_order_quantity'];
                    }
                }
                array_push($stock_array, $new_stock_array);

                $brand              = $variant['product']['brand'];
                $product            = $variant['product'];

                if ($product['is_composite'] == 0) {

                    array_push($standard_product_array, [
                        "Name"              => $product['name'],
                        "Description"       => strip_tags($product['description']),
                        "SKU"               => $variant['sku'],
                        "Category"          => $this->getCategories($product), //"Mobiles -> Trending-> New in store | Mobiles -> Trending | Trending",
                        "Supplier"          => $this->getSuppliers($product),
                        "Brand"             => $brand     != null ? $brand['name']    : null,

                        "Active"            => $product['active']                 == 1 ? "YES" : 'No',
                        "POS"               => $product['web_display']            == 1 ? "YES" : 'No',
                        "Web"               => $product['is_featured_web']        == 1 ? "YES" : 'No',
                        "Top"               => $product['top_seller_web']         == 1 ? "YES" : 'No',
                        "Featured"          => $product['is_featured']            == 1 ? "YES" : 'No',
                        "Catalogue"         => $product['dinein_display']         == 1 ? "YES" : 'No',

                        "RetailPrice"       => $variant['retail_price'],
                        "ComparePrice"      => $variant['before_discount_price'],
                        "AllowOutOfStock"   => $variant['allow_out_of_stock']     == 1 ? 'YES' : 'No',


                        "HasVariant"        => $product['has_variant'] == 1 ? "YES" : 'No',
                        "Attribute 1"       => $product['has_variant'] == 1 ? $product['attribute_1']  : null,
                        "Attribute 1 Value" => $product['has_variant'] == 1 ? $variant['attribute_value_1']     : null,
                        "Attribute 2"       => $product['has_variant'] == 1 ? $product['attribute_3']  : null,
                        "Attribute 2 Value" => $product['has_variant'] == 1 ? $variant['attribute_value_2']     : null,
                        "Attribute 3"       => $product['has_variant'] == 1 ? $product['attribute_3']  : null,
                        "Attribute 3 Value" => $product['has_variant'] == 1 ? $variant['attribute_value_3']     : null,

                        "MetaTitle"         => $product['meta_title'],
                        "MetaKeywords"      => $product['meta_keywords'],
                        "MetaDescription"   => $product['meta_description'],
                    ]);
                }

                if ($variant['product']['is_composite'] == 1) {
                    foreach ($product['composite_products'] as $comp_key => $composite_product) {
                        array_push($composite_product_array, [
                            "CompositeName"      => $product['name'],
                            "SKU"                => $variant['sku'],
                            "VariantSKU"         => $composite_product['product_variant']['sku'],
                            "Description"        => strip_tags($product['description']),
                            "Category"           => $this->getCategories($product), //"Mobiles -> Trending-> New in store | Mobiles -> Trending | Trending",
                            "QuantityOfVariants" => $composite_product['quantity'],

                            "Active"            => $product['active']                 == 1 ? "YES" : 'No',
                            "POS"               => $product['web_display']            == 1 ? "YES" : 'No',
                            "Web"               => $product['is_featured_web']        == 1 ? "YES" : 'No',
                            "Top"               => $product['top_seller_web']         == 1 ? "YES" : 'No',
                            "Featured"          => $product['is_featured']            == 1 ? "YES" : 'No',
                            "Catalogue"         => $product['dinein_display']         == 1 ? "YES" : 'No',

                            "RetailPrice"       => $variant['retail_price'],
                            "ComparePrice"      => $variant['before_discount_price'],
                            "AllowOutOfStock"   => $variant['allow_out_of_stock']     == 1 ? 'YES' : 'No',

                            "MetaTitle"         => $product['meta_title'],
                            "MetaKeywords"      => $product['meta_keywords'],
                            "MetaDescription"   => $product['meta_description'],
                        ]);
                    }
                }

                $image_count = count($variant['product']['product_images']);
                if ($image_count > 0) {
                    $img_arr = [
                        'SKU' => $variant['sku'],
                        'URL 1' => null,
                        'URL 2' => null,
                        'URL 3' => null,
                        'URL 4' => null,
                        'URL 5' => null,
                    ];
                    for ($i = 1; $i <= $image_count; $i++) {
                        foreach ($variant['product']['product_images'] as $image_key => $image) {
                            if (isset(explode('storage/images/', $image['url'])[1])) {
                                $img_arr['URL ' . $i] = explode('storage/images/', $image['url'])[1];
                            }
                        }
                    }
                    array_push($images_array, $img_arr);
                }
            }
        }
        // dd($standard_product_array,  $composite_product_array, $images_array,$stock_array);
        return new ProductsRecordExport($standard_product_array, $composite_product_array, $images_array, $stock_array);
    }
    public function getCategories($product)
    {
        $cat = $product['product_categories'];
        $catsIds = array_column($product['product_categories'], 'id');
        $cat_name = [];
        $cat_name_str = null;
        foreach ($cat as $key => $product_category) {
            $category = $product_category['category'];

            $cat_name_str = $category['name'];
            if ($category['parent_id'] > 0 && in_array($category['parent_id'], $catsIds)) {

                $parent = $product_category['category']['parent'];
                $cat_name_str = $parent['name'] . ' -> ' . $cat_name_str;

                if ($parent['parent_id'] > 0 && in_array($parent['parent_id'], $catsIds)) {
                    $p_parent = $product_category['category']['parent']['parent'];
                    $cat_name_str = $p_parent['name'] . ' -> ' . $cat_name_str;
                }
            }
            array_push($cat_name, $cat_name_str);
        }
        $categories = implode(' | ', array_reverse(array_unique($cat_name)));

        // return categories in this formate
        //parent -> child -> child | another category -> child | another category; and so on
        // Mobiles -> Trending -> New in store | Mobiles -> Trending | Mobiles

        return $categories;
    }
    public function getSuppliers($product)
    {
        $sup_name = [];
        $sup_name_str = null;

        foreach ($product['product_suppliers'] as $key => $p_supplier) {
            if ($p_supplier['supplier'] != null) {
                $sup_name_str = $p_supplier['supplier']['name'];
                array_push($sup_name, $sup_name_str);
            }
        }
        $suppliers_str = implode(' | ', array_reverse(array_unique($sup_name)));
        // return suppliers_str in this formate
        //supplier1 | supplier2 | supplier3; and so on
        return $suppliers_str;
    }
}
