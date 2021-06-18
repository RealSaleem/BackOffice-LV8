<?php

namespace App\Http\Controllers\Catalogue;

use App\Core\BaseRequest;
use App\Core\Response;
use App\Models\AddOn;
use App\Modules\Catalogue\Item\AddItemRequest;
use App\Requests\AddOn\DeleteItemRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Core\RequestExecutor;
use App\Models\AddOnItems;
use App\Modules\Brand\GetAllBrandRequest;
use App\Modules\Supplier\GetSupplierListRequest;
use App\Modules\Outlet\GetAllOutletRequest;
use App\Modules\Product\GetProductDetailRequest;
use App\Modules\Product\GetStoreSkuRequest;
use App\Models\Product;
use Auth;
use Redirect;

use App\Requests\Catalogue\Product\GetProductByNameRequest;
use App\Modules\Catalogue\Product\AddProductRequest;
use App\Requests\Catalogue\Product\GetProductByIdRequest;
use App\Modules\Catalogue\Product\GetCategoriesRequest;
use App\Modules\Catalogue\Product\GetSuppliersRequest;
use App\Modules\Catalogue\Product\GetRelatedRequest;
use App\Requests\Catalogue\Product\EditProductRequest;
use App\Requests\Catalogue\Product\GetEmptyProductRequest;
use App\Modules\Catalogue\Product\GetOutletByIdRequest;
use App\Modules\Catalogue\Product\GetEmptyOutletRequest;
use App\Modules\Catalogue\Product\GetEmptyComboRequest;
use App\Modules\Catalogue\Product\AddVarientsRequest;
use App\Modules\Catalogue\Product\GetVariantsByIdRequest;
use App\Models\CompositeProduct;
use App\Models\ProductVariant;
use App\Models\SalesTax;
use App\Models\ProductCategories;
use App\Models\Category;
use App\Models\Currency;
use App\Modules\Catalogue\AddOn\GetAllAddOnRequest;
use App\Modules\Catalogue\AddOn\GetEmptyAddOnRequest;
use App\Modules\Catalogue\Product\GetRelatedAddOnRequest;
use App\Requests\Catalogue\Category\GetAllCategoryRequest;

use App\Requests\Catalogue\AddOn\GetAllItemsRequest;


use App\Modules\Catalogue\Product\updateHas_VariantRaquest;
use App\Requests\Catalogue\Category\GetAllCategoryRequest as CategoryGetAllCategoryRequest;
use Lang;

class ItemController extends Controller
{
    public function __construct(RequestExecutor $requestExecutor)
    {
        parent::__construct();
        $this->RequestExecutor = $requestExecutor;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $itemsRequest = new GetAllItemsRequest();
        $itemsResponse = $this->RequestExecutor->execute($itemsRequest);
        $items = $itemsResponse->Payload;
        return view('catalogue.item.index', ['items' => $items]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $request = new GetStoreSkuRequest();
        $response = $this->RequestExecutor->execute($request);
        $sku = $response->Payload->current_sequence_number;

        // $productrequest = new GetProductDetailRequest();
        // $productrequest->id = 1;
        // $productresponse = $this->RequestExecutor->execute($productrequest);

        $outlet_request = new GetEmptyOutletRequest();
        $outlet_response = $this->RequestExecutor->execute($outlet_request);
        $stock = $outlet_response->Payload;

        $request = new GetEmptyProductRequest();
        $response = $this->RequestExecutor->execute($request);
        $product = $response->Payload;

        $store = Auth::user()->store;
        $sku_by_name = false;
        $show_unit = false;

        if ($store->sku_generation == 0) {
            $sku = '';
            $sku_by_name = true;
        }

        if ($store->product_type == 2) {
            $show_unit = true;
        }

        $currency = Currency::get();

        $suppliers_request = new GetSupplierListRequest();
        $Suppliersresponse = $this->RequestExecutor->execute($suppliers_request);
        $suppliers = $Suppliersresponse->Payload;

        $data = [
            'currency' => $currency,
            'suppliers' => $suppliers,
            'supplier_ids' => [],
            'outlets' => $stock,
            'product' => $product,
            'languages' => $store->languages->toArray(),
            'sku' => $sku,
            'title' => 'New Item',
            'save_title' => 'Save',
            'route' => route('item.store'),
            'is_edit' => false,
            'active' => 0,
            'sku_by_name' => $sku_by_name,
            'add_btn' => 'Add Item',
            'store_currency' => Auth::user()->store->default_currency,
            'show_unit' => $show_unit
        ];

        //    dd($data);
        return view('catalogue.item.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // dd($request);
        $languages = Auth::user()->store->languages->toArray();
        $product_request = new AddProductRequest();
        foreach ($languages as $language) {
            $has_seo = 'has_seo_' . $language['short_name'];
            $title = 'title_' . $language['short_name'];
            $description = 'description_' . $language['short_name'];
            $meta_title = 'meta_title_' . $language['short_name'];
            $meta_keywords = 'meta_keywords_' . $language['short_name'];
            $meta_description = 'meta_description_' . $language['short_name'];

            $product_request->{$has_seo} = $request->{$has_seo};
            $product_request->{$title} = $request->{$title};
            $product_request->{$description} = $request->{$description};
            $product_request->{$meta_title} = $request->{$meta_title};
            $product_request->{$meta_keywords} = $request->{$meta_keywords};
            $product_request->{$meta_description} = $request->{$meta_description};
        }

        $product_request->product_images = $request->images;
        $product_request->name = $request->title_en;
        $response = $this->RequestExecutor->execute($product_request);
        return response()->json($response);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //        $items = AddOnItems::find($id);
        $items = Product::with('product_variants')->find($id);


        // $categories_request = new GetAllCategoryRequest();
        // $Categoryresponse = $this->RequestExecutor->execute($categories_request);
        // $categories = $Categoryresponse->Payload;

        // $product_cat_request = new GetCategoriesRequest();
        // $product_cat_request->id = $id;
        // $cat_res = $this->RequestExecutor->execute($product_cat_request);
        // $product_cat = array_column($cat_res->Payload, 'category_id');

        $product_sup_request = new GetSuppliersRequest();
        $product_sup_request->id = $id;
        $sup_res = $this->RequestExecutor->execute($product_sup_request);
        $product_sup = array_column($sup_res->Payload, 'supplier_id');

        // $product_related_request = new GetRelatedRequest();
        // $product_related_request->id = $id;
        // $related_res = $this->RequestExecutor->execute($product_related_request);
        // $product_related = array_column($related_res->Payload, 'related_product_id');

        /* Get All Brands*/
        // $brands_request = new GetAllBrandRequest();
        // $Brandresponse = $this->RequestExecutor->execute($brands_request);
        // $brands = $Brandresponse->Payload;

        /*Get All Suppliers*/
        $suppliers_request = new GetSupplierListRequest();
        $Suppliersresponse = $this->RequestExecutor->execute($suppliers_request);
        $suppliers = $Suppliersresponse->Payload;

        $request = new GetStoreSkuRequest();
        $response = $this->RequestExecutor->execute($request);
        $sku = $response->Payload->current_sequence_number;

        // $productrequest = new GetProductDetailRequest();
        // $productrequest->id = 1;
        // $productresponse = $this->RequestExecutor->execute($productrequest);

        $get_product = new GetProductByIdRequest();
        $get_product->id = $id;
        $get_productresponse = $this->RequestExecutor->execute($get_product);
        $product = $get_productresponse->Payload;

        // $products = Product::where('store_id', Auth::user()->store_id)->get()->toArray();

        $outlet_request = new GetOutletByIdRequest();
        $outlet_request->id = $id;
        $outlet_response = $this->RequestExecutor->execute($outlet_request);
        $stock = $outlet_response->Payload;

        // $composite_products = CompositeProduct::with(['product_variant','product_variant.product'])->where('product_id',$id)->get();
        // $variants_count = ProductVariant::where([['product_id',$id],['store_id',Auth::user()->store_id]])->count();
        $store = Auth::user()->store;
        $outlets = [];

        if (sizeof($stock) > 0) {
            $outlets = $stock[0]['outlets'];

            /*
            if($product['unit_divider'] > 0){
                foreach ($stock[0]['outlets'] as $row) {
                    $row['quantity'] = $row['quantity'] / $product['unit_divider'];
                    array_push($outlets,$row);
                }
            }
            */

        } else {
            $variant_outlets = Auth::user()->store->outlets->toArray();

            foreach ($variant_outlets as $outlet) {
                $outlet['quantity'] = 0;
                $outlet['re_order_point'] = 0;
                $outlet['re_order_quantity'] = 0;
                $outlet['supply_price'] = 0;
                $outlet['margin'] = 0;

                array_push($outlets, $outlet);
            }
        }

        $sku_by_name = false;
        $show_unit = false;

        if ($store->sku_generation == 0) {
            $sku = '';
            $sku_by_name = true;
        }

        $salestax = SalesTax::where([['store_id', Auth::user()->store_id], ['status', 1]])->first();

        if ($salestax == null) {
            $salestax = new SalesTax;
        }

        if ($store->product_type == 2) {
            $show_unit = true;
        }

        $currency = Currency::get();

        // $add_on_request = new GetAllAddOnRequest();
        // $add_on_response = $this->RequestExecutor->execute($add_on_request);

        // $add_on_related_request = new GetRelatedAddOnRequest();
        // $add_on_related_request->id = $id;
        // $add_on_related_res = $this->RequestExecutor->execute($add_on_related_request);
        // $add_on_related = array_column($add_on_related_res->Payload, 'add_on_id');

        $request = new GetEmptyAddOnRequest();
        $oddonResponse = $this->RequestExecutor->execute($request);

        $data = [
            // 'add_ons'       => [],//$add_on_response->Payload,
            'items' => $items,
            'items_variant' => $items->product_variants->first(),
            'submitbtn' => 'Update',
            'add_on' => $oddonResponse->Payload,
            // 'add_ons_related'    =>[],// $add_on_related,
            'currency' => $currency,
            // 'categories'    => [],//$categories,
            // 'products'      => $products,
            // 'brands'        => [],//$brands,
            'suppliers' => $suppliers,
            'outlets' => $outlets,
            'product' => $product,
            // 'category_ids'  => [],//$product_cat,
            'supplier_ids' => $product_sup,
            // 'related_ids'   => [],//$product_related,
            'languages' => $store->languages->toArray(),
            'sku' => $product['sku'],
            'title' => $product['title_en'],
            'save_title' => 'Update',
            'route' => route('item.update', $id),
            // 'route'         => route('item.create'),
            'is_edit' => true,
            // 'active'       => $product['active'],
            // 'stock'        => $product['product_stock'],
            // 'composite_products' =>[],//$composite_products,
            'id' => $id,
            'sku_by_name' => $sku_by_name,
            // 'variants_count' => null,//$variants_count,
            'store_currency' => Auth::user()->store->default_currency,
            'salestax' => $salestax,
            'show_unit' => $show_unit,

        ];

        return view('catalogue.item.form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        // dd($request);
        $languages = Auth::user()->store->languages->toArray();

        $product_request = new EditProductRequest();
        $product_request->id = $id;

        foreach ($languages as $language) {
            $has_seo = 'has_seo_' . $language['short_name'];
            $title = 'title_' . $language['short_name'];
            $description = 'description_' . $language['short_name'];
            $meta_title = 'meta_title_' . $language['short_name'];
            $meta_keywords = 'meta_keywords_' . $language['short_name'];
            $meta_description = 'meta_description_' . $language['short_name'];

            $product_request->{$has_seo} = $request->{$has_seo};
            $product_request->{$title} = $request->{$title};
            $product_request->{$description} = $request->{$description};
            $product_request->{$meta_title} = $request->{$meta_title};
            $product_request->{$meta_keywords} = $request->{$meta_keywords};
            $product_request->{$meta_description} = $request->{$meta_description};
        }
        $product_request->name = $request->title_en;
        $product_request->has_variant = 0;
        $response = $this->RequestExecutor->execute($product_request);


        // dd($request);

        return response()->json($response);
    }

    public function deleteItem(Request $request)
    {
        $item_request = new DeleteItemRequest();
        $item_request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($item_request);

        return response()->json($response);
    }

    public function bulkItem(Request $request)
    {
        $Message = "";
        $status = "";
        $item_id = $request->item;
        $product = Product::where('id', $item_id)->where('store_id', Auth::user()->store_id)->first();
        $addOnItem = AddOnItems::where('name', $product->name)->first();
        if ($addOnItem->count()) {
            $Message = \Lang::get('toaster.item_addon_exist');
            $status = false;
        } else {
            $product->delete();
            $addOnItem->delete();
            $Message = \Lang::get('toaster.item_deleted');
            $status = true;
        }
        $data = [
            'status' => $status,
            'payload' => $product,
            'Message' => $Message
        ];
//        return new Response($status, $product,null,$Message);
        return response()->json($data);
    }


}
