<?php

namespace App\Requests\Catalogue\Product;

use App\Core\BaseRequest as BaseRequest;
use App\Core\Response;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductCatgories;
use App\Models\LanguageTranslation;
use Auth;
use DB;
use App\Helpers\Language;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Store;
use App\Models\ProductVariant;
use App\Models\CompositeProduct;
use App\Models\ProductStock;
use App\Models\Outlet;
use App\Helpers\OrderType as OrderType;
use Session;
use App\Helpers\VariantStock;

class AddProductRequest extends BaseRequest
{

    public $id;
    public $category_id;
    public $supplier_id;
    public $related_id;
    public $branches;
    public $brand_id;
    public $active;
    public $name;
    public $handle;
    public $description;
    public $product_images;
    public $attribute_1;
    public $attribute_2;
    public $attribute_3;
    public $supplier_price;
    public $markup;
    public $retail_price;
    public $sku;
    public $supplier_code;
    public $sales_account_code;
    public $purchase_account_code;
    public $tags;
    public $has_variant;
    public $variants;
    public $is_featured;
    public $prefix;
    public $is_composite;
    public $composite_products;
    public $sku_string;
    public $created;
    public $barcode;
    public $dinein_display;
    public $meta_title;
    public $meta_keywords;
    public $meta_description;
    public $before_discount_price;
    public $discounted_price;
    public $is_featured_web;
    public $top_seller_web;
    public $web_display;
    public $sku_custom;
    public $sku_name;
    public $sku_number;
    public $default_sku_name;
    public $sku_type;
    public $allow_out_of_stock;
    public $is_combo;
    public $allow_subscription;
    public $apply_tax;
    public $inclusive;
    public $tax_per;
    public $unit;
    public $add_ons;
    public $addon_id;
    public $option_id;

}

class AddProductRequestValidator
{
    public function GetRules()
    {
        return [
            'name' => [
                'required',
                'string',
                'min:3',
                Rule::unique('products')->where(function ($query) {
                    return $query->where('store_id', Auth::user()->store_id);
                })->whereNull('deleted_at'),
            ],

            // 'description' => 'required|string',
            // 'active'      => 'boolean',

            // 'category_id' => 'required|array|min:1',
            // "category_id" => ["required","array","min:2","max:4"],

            // 'brand_id' => 'required_if:is_composite,0|numeric',
            // 'supplier_id' => 'required_if:is_composite,0|array|min:1',
            // 'retail_price' => 'required|numeric|min:1',

            //'markup' => 'required|numeric',
            // 'retail_price' => 'required_if:has_variant,true|numeric',
            // 'sku' => 'required',
            //'attribute_1' => 'required|string',
            //'attribute_2' => 'required_if:|string',
            //'attribute_3' => 'required_if:|string',
            // 'supplier_code' => 'string',
            // 'sales_account_code' => 'string',
            // 'purchase_account_code' => 'string',
            // 'has_variant' => 'boolean',
            // 'tags' => 'sometimes|array',
            // 'is_featured' => 'boolean',
            // 'is_composite' => 'boolean',
            // 'variants' => 'required_if:has_variant,true|array',
            // 'variants.sku' => 'required_if:has_variant,true|array',
            // 'variants.retail_price' => 'required_if:has_variant,true|array',
            // 'variants.before_discount_price' => 'required_if:has_variant,true|array',
            // 'composite_products' => 'required_if:is_composite,true|array',
        ];
    }

    // public function GetMessages(){
    //     return [
    //         'variants.sku.required_if'    => 'The Variant Sku field is required',
    //         'variants.retail_price.required_if'    => 'The Variant Retail Price field is required',
    //         'variants.before_discount_price.required_if'    => 'The Variant Discount Price field is required'
    //     ];
    // }
}

class AddProductRequestHandler
{
    public function __construct()
    {
        $this->store = Store::with('product_variants')->find(Auth::user()->store_id);
        $this->sku_name_counter = 0;
        $this->sku_number_counter = $this->store->current_sequence_number;
    }

    public function Serve($request)
    {

        try {

            DB::beginTransaction();
            $product = $this->mapProduct($request);
            $product->save();

            $this->addProductImages($product, $request);
            $this->addLanguageTranslations($product, $request);

            if ($request->is_combo == 1) {
                $this->attachCategoryIds($product, $request);

                $variant = $this->AddProductVariants($product, $request);
                if (isset($variant->name) != true) {
                    return new Response(false, null, null, ['SKU already exists']);
                }

                $this->AddProductstock($product, $request);

                if ($this->store->product_type == null || $this->store->product_type != 2) {
                    $this->removeProductstock($product, $request, $variant);
                }

                if (!isset($request->composite_products) || sizeof($request->composite_products) == 0) {
                    return new Response(false, null, null, ['Please add composite items']);
                }

                $this->AddComboProducts($product, $request);

                if ($this->store->product_type == 2) {
                    $this->updateCostPrice($request, $variant);
                }

                $this->updateSequenceNumber();

            } elseif ($request->is_combo == 0) {
                $this->attachCategoryIds($product, $request);
                $this->attachSupplierIds($product, $request);
                $this->attachRelatedIds($product, $request);
                $this->attachAddOnIds($product, $request);

                $variant = $this->AddProductVariants($product, $request);
                if (isset($variant->name) != true) {
                    return new Response(false, null, null, ['SKU already exists']);
                }
                $this->AddProductstock($product, $request);
                $this->updateSequenceNumber();
            } elseif ($request->is_combo == -1) {
                $err = $this->verifyVariant(isset($request->sku_custom) ? $request->sku_custom : $request->sku);

                if ($err == false) {
                    return new Response(false, null, null, ['SKU already exists']);
                }
                $this->attachRelatedIds($product, $request);
                $this->attachAddOnIds($product, $request);
                $this->attachCategoryIds($product, $request);
                $this->attachSupplierIds($product, $request);
                $this->updateSequenceNumber();
            }

            $vari = ProductVariant::where('product_id', $product->id)->get();
            foreach ($vari as $variu) {

                //Variant stock Update i.e sum of stock of all outlet in stock field in varant table
                VariantStock::updateStock($variu->id);
            }

            DB::commit();
            $product->Message = \Lang::get('toaster.product_added');
            return new Response(true, $product);

        } catch (Exception $ex) {

            DB::rollBack();
            return new Response(false, null, $ex->getMessage());
        }
    }

    private function updateCostPrice($request, $variant)
    {

        $ids = array_column($request->composite_products, 'id');

        $variants = ProductVariant::whereIn('id', $ids)->get();

        $outlets = Auth::user()->store->outlets;

        foreach ($outlets as $outlet) {
            $cost_price = 0;

            foreach ($variants as $variant_row) {
                $stock = ProductStock::where([
                    'variant_id' => $variant_row->id,
                    'outlet_id' => $outlet->id,
                    'type' => 1
                ])->orderBy('id', 'desc')->first();

                if (!is_null($stock)) {
                    $stock = $stock->toArray();
                    $cost_price += $stock['cost_price'];
                }
            }

            $variant_stock = ProductStock::where([
                'variant_id' => $variant->id,
                'outlet_id' => $outlet->id,
                'type' => 1
            ])->first();

            if (!is_null($variant_stock)) {
                $variant_stock->cost_price = $cost_price;
                $variant_stock->save();
            }
        }
    }

    private function mapProduct($request)
    {

        $product = new Product;
        $product->store_id = Auth::user()->store_id;
        $product->category_id = $request->category_id[0];

        $product->active = false;
        $product->name = $request->title_en;
        $product->handle = $request->title_en;
        $product->prefix = 'test';
        $product->attribute_1 = $request->title_en;
        $product->attribute_2 = $request->title_en;
        $product->attribute_3 = $request->title_en;

        $product->supplier_code = $request->supplier_code;
        $product->sales_account_code = $request->sales_account_code;
        $product->purchase_account_code = $request->purchase_account_code;
        $product->created = Auth::user()->id;
        $product->is_featured = $request->is_featured == 1;
        $product->dinein_display = $request->dinein_display == 1;
        $product->is_featured_web = $request->is_featured_web == 1;
        $product->top_seller_web = $request->top_seller_web == 1;
        $product->web_display = $request->web_display == 1;
        $product->allow_subscription = $request->allow_subscription == 1;

        $product->unit = isset($request->unit) ? $request->unit : 'number';

        if ($request->is_combo == 1) {
            $product->is_composite = true;
            $product->has_variant = false;
        } elseif ($request->is_combo == 0) {
            $product->is_composite = false;
            $product->has_variant = false;
        } elseif ($request->is_combo == -1) {
            $product->has_variant = true;
            $product->is_composite = false;
        }

        if ($request->is_combo != 1) {
            $product->brand_id = $request->brand_id[0];
            $product->supplier_id = $request->supplier_id[0];
        }
        if ($request->active) {
            $product->active = $request->active == 1;
        } else {
            $product->active = false;
        }

        if ($request->sku_type == 1) {
            if ($this->store->sku_generation == 0) {
                $product->sku_type = 'name';
            } else {
                $product->sku_type = 'number';
            }
        } elseif ($request->sku_type == 0) {
            $product->prefix = 'test';
            $product->sku_type = 'custom';
        }
        if ($request->apply_tax == 1) {
            $product->inc_or_exc = $request->inclusive;
            $product->tax_per = $request->tax_per;
        } else {
            $product->inc_or_exc = 0;
            $product->tax_per = 0;
        }
        return $product;
    }

    private function mapVariants($variants, $product_id, $request)
    {
        $mappedVariants = array();

        $index = 0;
        foreach ($variants as $v) {
            $variant = new ProductVariant;
            $variant->product_id = $product_id;
            $variant->allow_out_of_stock = (bool)$request->allow_out_of_stock == 1;
            //$variant->quantity                = $v['quantity'];
            //$variant->supplier_price          = $v['supplier_price'];
            //$variant->sku                     =  $this->sku_string."-".$v['sku'];
            $variant->sku = $this->getSku($request, $index);

            $variant->attribute_value_1 = isset($v['attribute_value_1']) ? $v['attribute_value_1'] : '';
            $variant->attribute_value_2 = isset($v['attribute_value_2']) ? $v['attribute_value_2'] : '';
            $variant->attribute_value_3 = isset($v['attribute_value_3']) ? $v['attribute_value_3'] : '';
            //$variant->markup                  = $v['markup'];
            $variant->retail_price = $v['retail_price'];

            $variant->barcode = 'data:image/png;base64,' . \DNS1D::getBarcodePNG($v['sku'], "C128", 1, 33);
            $variant->before_discount_price = $v['before_discount_price'];

            $mappedVariants[] = $variant;
            $index++;
        }

        return $mappedVariants;
    }

    private function mapComposite($composite_products, $product_id)
    {
        $mappedComposites = array();
        foreach ($composite_products as $cp) {
            $composite = new CompositeProduct;
            $composite->product_id = $product_id;
            $variant->allow_out_of_stock = $request->allow_out_of_stock == 1;
            $composite->product_variant_id = $cp['product_variant_id'];
            $composite->quantity = $cp['quantity'];
            $composite->save();
            $mappedComposites[] = $composite;
        }
        return $mappedComposites;
    }


    private function addSingleVairant($request, $product)
    {
        $index = 0;
        $variant = new ProductVariant;
        // dd($product->id);
        $variant->product_id = $product->id;
        $variant->name = $product->name;
        $variant->supplier_price = 0;
        $variant->markup = 0;
        $variant->sku = $this->getSku($request, $index);
        $variant->store_id = Auth::user()->store_id;
        $variant->attribute_value_1 = $product->name;
        //$variant->quantity               = $request->quantity;
        $variant->retail_price = isset($request->retail_price) ? $request->retail_price : 0;
        $variant->allow_out_of_stock = $request->allow_out_of_stock == 1;
        $variant->barcode = 'data:image/png;base64,' . \DNS1D::getBarcodePNG($variant->sku, "C128", 1, 33);
        $variant->before_discount_price = $request->before_discount_price;

        return $variant;
        //$variant->save();
    }

    private function verifyVariant($sku)
    {
        $variants = $this->store->product_variants->toArray();

        $skus = array_column($variants, 'sku');

        if (in_array($sku, $skus)) {
            return false;
        }

        return true;
    }

    private function getSku($request, $index)
    {
        if ($request->sku_custom) {
            if ($request->has_variant) {
                return $request->variants[$index]['sku'];
            } else {

                if ($request->sku_type == 0) {
                    $request->sku = $request->sku_custom;
                    return $request->sku;
                } else {
                    return $request->sku;
                }

            }

        } else {
            return $this->_getSku($request);
        }
    }

    private function _getSku($request)
    {
        if ($request->sku_name) {
            return $this->getSkuByName($request);
        } else {
            return $this->getSkuByNumber();
        }
    }

    private function getSkuByName($request)
    {
        $this->sku_name_counter = $this->sku_name_counter + 1;
        $sku = sprintf('%s-%d', $request->default_sku_name, $this->sku_name_counter);
        return $sku;
    }

    private function getSkuByNumber()
    {
        $sku = $this->sku_number_counter;
        $this->sku_number_counter++;
        return $sku;
    }

    private function updateSequenceNumber()
    {
        if ($this->store->current_sequence_number != $this->sku_number_counter) {
            $this->store->current_sequence_number = $this->sku_number_counter;
            $this->store->save();
        }
    }

    private function addLanguageTranslations($product, $request)
    {
        $languages = Auth::user()->store->languages->toArray();

        foreach ($languages as $language) {
            $key = $language['short_name'];
            $has_seo = 'has_seo_' . $key;
            $title = 'title_' . $key;
            $description = 'description_' . $key;
            $meta_title = 'meta_title_' . $key;
            $meta_keywords = 'meta_keywords_' . $key;
            $meta_description = 'meta_description_' . $key;

            if ($key == Language::Primary) {
                $validator = Validator::make($request->all(), [
                    $title => 'required|string',
                ]);

                if ($validator->fails()) {
                    return new Response(false, null, [], $validator);
                }
            }

            $translation = new LanguageTranslation;

            $translation->product_id = $product->id;
            $translation->title = $request->$title;
            $translation->description = $request->$description;
            $translation->meta_title = $request->$meta_title;
            $translation->meta_keywords = $request->$meta_keywords;
            $translation->meta_description = $request->$meta_description;
            $translation->language_key = $key;

            $translation->save();
        }
    }

    private function addProductImages($product, $request)
    {
        if (is_array($request->product_images) && sizeof($request->product_images) > 0) {
            foreach ($request->product_images as $image) {
                if (isset($image['size'])) {
                    $product_image = new ProductImage;
                    $product_image->product_id = $product->id;
                    $product_image->url = $image['path'];
                    $product_image->name = $image['name'];
                    $product_image->size = $image['size'];
                    $product_image->created = Auth::user()->id;
                    $product_image->updated = Auth::user()->id;
                    $product_image->save();
                }
            }
        }
    }

    private function attachAddOnIds($product, $request)
    {
        if (is_array($request->addon_id) && count($request->addon_id) > 0) {
            $product->product_add_on()->attach($request->addon_id);

        } else if (is_array($request->option_id) && count($request->option_id) > 0) {
            $product->product_add_on()->attach($request->option_id);
        }

    }

    private function attachRelatedIds($product, $request)
    {
        if (is_array($request->related_id) && sizeof($request->related_id) > 0) {
            $product->related()->attach($request->related_id);
        }
    }

    private function attachCategoryIds($product, $request)
    {
        if (is_array($request->category_id) && sizeof($request->category_id) > 0) {
            $product->categories()->attach($request->category_id);
        }
    }

    private function attachSupplierIds($product, $request)
    {
        if (is_array($request->supplier_id) && sizeof($request->supplier_id) > 0) {
            $product->product_supplires()->attach($request->supplier_id);
        }
    }

    private function AddProductVariants($product, $request)
    {
        $error = false;
        $duplicate_sku = null;

        if ($request->is_composite) {
            //add rows in composite product table
            $composite_rows = $this->mapComposite($request->composite_products, $product->id);

            //add one variant for composite product
            $variant = $this->addSingleVairant($request, $product);

            $resp = $this->verifyVariant($variant->sku);

            if ($resp) {
                $variant->save();
            }
        } else {

            if (!$request->has_variant) {
                //add one variant for simple  product
                $variant = $this->addSingleVairant($request, $product);

                $resp = $this->verifyVariant($variant->sku);

                if ($resp) {
                    $variant->save();
                } else {
                    $error = true;
                }
            }
        }

        if ($error) {
            return $error;
            // return new Response(false,null,null,[sprintf('Sku %s already exists',$duplicate_sku)]);
        } else {
            return $variant;
        }
    }

    private function AddProductstock($product, $request)
    {
        $variants = ProductVariant::where('product_id', $product->id)->get()->toArray();
        foreach ($request->branches as $outlet => $value) {
            $outlet_id = $outlet;

            $productStock = new ProductStock();
            $productStock->invoice_date = date('Y-m-d H:i:s');
            // $productStock->invoice_number    = $request->invoice_number;
            // $productStock->purchase_order    = $request->purchase_order;
            $productStock->sale_price = $request->retail_price;
            $qty = 0;
            if (isset($value['quantity']) && $value['quantity'] >= 0) {
                $qty = $value['quantity'];
            }

            /*
            if( isset($request->unit) && in_array($request->unit, ['kg','l','m']) ){

                if($product->unit == 'm'){
                    $qty = $value['quantity'] * 100;
                }else{
                    $qty = $value['quantity'] * 1000;
                }
            }
            */

            // $productStock->notes             = $request->notes;
            $productStock->quantity = $qty;

            $productStock->re_order_quantity = isset($value['re_order_quantity']) ? $value['re_order_quantity'] : 0;
            $productStock->re_order_point = isset($value['re_order_point']) ? $value['re_order_point'] : 0;
            $productStock->cost_price = isset($value['supply_price']) ? $value['supply_price'] : 0;

            $productStock->margin = $this->calculateMargin($productStock->cost_price, $productStock->sale_price);

            $productStock->product_id = $product->id;

            $productStock->variant_id = $variants[0]['id'];

            $productStock->created = $productStock->updated = Auth::user()->id;
            $productStock->created_at = $productStock->updated_at = date('Y-m-d H:i:s');
            $productStock->outlet_id = $outlet_id;
            if ($product->is_composite == 1) {
                $productStock->type = OrderType::CompositionIn;
            } else {
                $productStock->type = OrderType::In;
            }
            $productStock->type = OrderType::In;

            $productStock->is_default = 1;

            $productStock->is_remove = false;

            $productStock->save();
        }
    }

    private function removeProductstock($product, $request, $variant)
    {
        foreach ($request->composite_products as $composite) {

            $var = ProductVariant::find($composite['id']);

            foreach ($request->branches as $outlet => $value) {
                $outlet_id = $outlet;

                $productStock = new ProductStock();
                $productStock->invoice_date = date('Y-m-d H:i:s');
                // $productStock->invoice_number    = $request->invoice_number;
                // $productStock->purchase_order    = $request->purchase_order;
                $productStock->sale_price = $request->retail_price;
                $qty = 0;

                $stock_qty = ProductStock::where([
                    'variant_id' => $variant->id,
                    'outlet_id' => $outlet_id
                ])->sum('quantity');

                if ($stock_qty > 0 || $var->allow_out_of_stock == 1) {
                    if (isset($value['quantity']) && $value['quantity'] >= 0) {
                        $qty = $value['quantity'];
                    }

                    if ($qty > 0) {
                        $qty = $qty * $composite['quantity'];
                    } else {
                        $qty = $composite['quantity'];
                    }
                }

                // $productStock->notes             = $request->notes;
                $productStock->quantity = -$qty;
                $productStock->re_order_quantity = isset($value['re_order_quantity']) ? $value['re_order_quantity'] : 0;
                $productStock->re_order_point = isset($value['re_order_point']) ? $value['re_order_point'] : 0;
                $productStock->cost_price = isset($value['supply_price']) ? $value['supply_price'] : 0;

                $productStock->margin = $this->calculateMargin($productStock->cost_price, $productStock->sale_price);

                $productStock->product_id = $var->product_id;

                $productStock->variant_id = $composite['id'];

                $productStock->created = $productStock->updated = Auth::user()->id;
                $productStock->created_at = $productStock->updated_at = date('Y-m-d H:i:s');
                $productStock->outlet_id = $outlet_id;
                $productStock->type = OrderType::CompositionOut;

                $productStock->is_default = 1;
                $productStock->is_remove = 1;

                $productStock->save();
            }
        }
    }


    private function calculateMargin($cost_price, $sale_price)
    {
        if ($cost_price == 0 || $sale_price == 0) {
            return 0;
        }

        $profit = $sale_price - $cost_price;
        $margin = $profit / $sale_price;
        return $margin * 100;
    }

    private function AddComboProducts($product, $request)
    {
        foreach ($request->composite_products as $combosite) {

            $combo = new CompositeProduct();
            $combo->product_id = $product->id;
            $combo->product_variant_id = $combosite['id'];
            $combo->quantity = $combosite['quantity'];
            $combo->save();
        }
    }

}
