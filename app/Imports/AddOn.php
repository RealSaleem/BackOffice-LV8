<?php

namespace App\Imports;

// use App\Models\AddOn;

// use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use App\Core\Response;
use App\Models\AddOn as ModelsAddOn;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Contracts\Queue\ShouldQueue;
use Auth;
use DB;
use Session;

class AddOn implements ToCollection, WithHeadingRow, ShouldQueue
{

    private $lang;
    private $stock;

    public function __construct($lang, $stock)
    {
        $this->lang = $lang;
        $this->stock = $stock;
    }
    public function collection(Collection $rows)
    {
        // dd($rows);
        $products = [];
        foreach ($rows as $index => $row) {
            $data = [];
            foreach ($row as $key => $value) {
                if (strlen($key) > 0) {
                    $data[$key] = $value;
                }
            }
            array_push($products, $data);
        }
        $res = $this->Serve($products);
        // season use for pasing the response to the import controller
        Session::put('Addon_Response', $res);
        return $res;
    }
    public function Serve($request)
    {
        try {

            DB::beginTransaction();

            if (!empty($request)) {
                // dd($request[0]);
                // dd($request);
                $row  = $request[0];
                if (
                    isset($row['stock'])
                    || isset($row['reorderquantity'])
                    || isset($row['reorderpoint'])
                    || isset($row['supplyprice'])
                ) {
                    return new Response(false, null, null, ['You are using outdated version of excel' => []]);
                }
                $errors = [];

                $addon_sheet_errors = $this->validateAddonsSheet($request);
                if (sizeof($addon_sheet_errors)) {
                    $errors['Addons Sheet'] = $addon_sheet_errors;
                }

                if (sizeof($errors) > 0) {
                    return new Response(false, null, null, $errors);
                }

                $res = $this->insertAddonSheet($request, $this->stock);
            }

            DB::commit();

            $Message = 'Standard sheet has been Uploaded successfully';
            return new Response(true, null, null, null, $Message);
        } catch (Exception $ex) {
            DB::rollBack();
            return new Response(false, null, null, $ex->getMessage());
        }
    }

    private function validateAddonsSheet($sheet)
    {

        if (!is_null($sheet) && sizeof($sheet) > 0) {
            $error_rows = [];
            // dd($sheet[0]['addontype']);

            $index = 0;

            foreach ($sheet as $key => $new_row) {
                if (is_array($new_row)) {
                    $new_row = array_filter(
                        $new_row,
                        function ($cell) {
                            return !is_null($cell);
                        }
                    );
                    if (count($new_row) == 0) {
                        unset($sheet[$key]);
                    }
                }
            }
            unset($new_row);
            foreach ($sheet as $row) {
                if (count($sheet) > 1) {
                    $rows = array_slice($sheet, 0, $index);
                } else {
                    $rows = [];
                }
                // dd( $rows);

                $validator = Validator::make(
                    $row,
                    [
                        'addonname'              => 'required|min:3  |max:490',
                        'addontype'              => 'required|string |nullable',
                        'active'                 => 'required|string |nullable',
                        'identifier'             => 'required|numeric|nullable',
                        'maximum'                => 'required|numeric|nullable',
                        'minimum'                => 'required|numeric|nullable',
                        'code'                   => 'required|numeric|nullable',


                    ]
                    // ,
                    // [
                    //     'not_in'        => ':attribute ' . $row['sku'] . ' already exist in sheet',
                    //     'required_if'   => ':attribute is required Has Variant is "YES"'
                    // ]
                );


                if ($validator->fails()) {
                    $errors = $validator->errors();

                    $error_row = [];

                    foreach ($errors->all() as $message) {
                        array_push($error_row, $message);
                    }

                    $key = 'Line ' . ($index + 2);

                    $error_rows[$key] = $error_row;
                }

                $index++;
            }

            return $error_rows;
        }

        return [];
    }

    private function insertAddonSheet($sheet, $stock_alert)
    {
        $pro_data = $sheet;

        // dd($pro_data);

        if (!is_null($pro_data) && sizeof($pro_data) > 0) {
            foreach ($pro_data as $key => $pro) {


                // if (!empty($pro['hasvariant']) && strtolower($pro['hasvariant'])) {
                //     $data = $this->ImportAddons($pro);
                //     $data['product']->save();

                //     $this->addLanguageTranslations($data['product'], $pro);
                //     $this->attachCategoryIds($data['category_ids'], $data['product']);
                //     $this->attachSupplierIds($data['supplier_ids'], $data['product']);
                //     $languages = Auth::user()->store->languages->where('short_name', $this->lang)->toArray();
                //     $this->addTranslations($data['product'], $languages, $pro);

                //     $variant = $this->mapVariants($data['product'], $pro);
                //     // $this->AddProductstock($data['product'],$pro, $variant, $stock_alert);

                //     $this->editAttrValue($data['product'], $languages);

                //     //Variant stock Update i.e sum of stock of all outlet in stock field in varant table
                //     // VariantStock::updateStock($variant->id);

                // } else {
                $data = $this->ImportAddons($pro);
                // dd($data);
                $data['addons']->save();

                // $this->addLanguageTranslations($data['product'], $pro);
                // $this->attachCategoryIds($data['category_ids'], $data['product']);
                // $this->attachSupplierIds($data['supplier_ids'], $data['product']);

                // $variant = $this->addSingleVairant($data['product'], $pro);
                // $this->AddProductstock($data['product'],$pro, $variant, $stock_alert);

                //Variant stock Update i.e sum of stock of all outlet in stock field in varant table
                // VariantStock::updateStock($variant->id);
                // }
            }
        }
    }

    public function editAttrValue($product, $translations)
    {
        $prod  = Product::with(['product_attributes', 'product_attributes.values', 'product_verients'])->find($product->id);

        foreach ($prod->product_verients as $vari) {
            $att_v  = ProductVariantValue::where([['variant_id', $vari->id], ['language_key', $this->lang]])->delete();
        }
        $translations = Auth::user()->store->languages->where('short_name', $this->lang)->toArray();
        $rows = [];

        if (is_array($translations) && sizeof($translations) > 0) {

            $attr = 1;
            $attribute_1_default = [];
            $attribute_2_default = [];
            $attribute_3_default = [];
            $is_default_set = false;

            foreach ($translations as $key => $trans) {
                $trans['attribute_1']               = '';
                $trans['attribute_2']               = '';
                $trans['attribute_3']               = '';
                $trans['attr1_values']              = [];
                $trans['attr2_values']              = [];
                $trans['attr3_values']              = [];

                $attributes = ProductAttribute::with('values')->where([
                    ['product_id', $product->id],
                    ['language_key', $trans['short_name']]
                ])->orderBy('id')->get()->toArray();

                if (!$is_default_set) {
                    $attribute_1_default = isset($attributes[0]['values']) ? array_column($attributes[0]['values'], 'name') : [];
                    $attribute_2_default = isset($attributes[1]['values']) ? array_column($attributes[1]['values'], 'name') : [];
                    $attribute_3_default = isset($attributes[2]['values']) ? array_column($attributes[2]['values'], 'name') : [];
                    $is_default_set = true;
                }

                $index = 1;
                $name = $trans['short_name'];
                $trans = [];
                $trans['short_name'] = $name;
                foreach ($attributes as $attribute) {
                    $trans['attribute_' . $attr]           = $attribute['name'];
                    $attribute_values = $attribute['values'];
                    $values = array_column($attribute_values, 'id', 'name');
                    $group = [];
                    if ($index == 1) {
                        $trans['attr1_values'] = $values;
                    } else if ($index == 2) {
                        $trans['attr2_values'] = $values;
                    } else {
                        $trans['attr3_values'] = $values;
                    }
                    $index++;
                    $attr++;
                }
                $attr = 1;
                array_push($rows, $trans);
            }
        }


        foreach ($rows as $key => $row) {
            foreach ($product->product_verients as $var) {
                if (isset($row['attr1_values']) && $row['attr1_values'] != null) {
                    foreach ($row['attr1_values'] as $keyval => $item) {
                        $Varient_value = new ProductVariantValue;
                        $Varient_value->language_key                    = $row['short_name'];
                        $Varient_value->variant_id                      = $var->id;
                        $Varient_value->product_attribute_value_id      = $item;
                        $Varient_value->save();
                    }
                }
                if (isset($row['attr2_values']) && $row['attr2_values'] != null) {
                    foreach ($row['attr2_values'] as $keyval => $item) {
                        $Varient_value2 = new ProductVariantValue;
                        $Varient_value2->language_key                    = $row['short_name'];
                        $Varient_value2->variant_id                      = $var->id;
                        $Varient_value2->product_attribute_value_id      = $item;
                        $Varient_value2->save();
                    }
                }
                if (isset($row['attr3_values']) && $row['attr3_values'] != null) {
                    foreach ($row['attr3_values'] as $keyval => $item) {
                        $Varient_value3 = new ProductVariantValue;
                        $Varient_value3->language_key                    = $row['short_name'];
                        $Varient_value3->variant_id                      = $var->id;
                        $Varient_value3->product_attribute_value_id      = $item;
                        $Varient_value3->save();
                    }
                }
            }
        }
    }
    private function getBrands($pro)
    {
        if (isset($pro['brand'])) {
            $brand = Brand::where([['name', $pro['brand']], ['store_id', Auth::user()->store_id]])->first();


            if ($brand == null) {
                $brands = new Brand;
                $brands->name        = $pro['brand'];
                $brands->store_id    = Auth::user()->store_id;
                $brands->created    = Auth::user()->store_id;
                $brands->save();

                $language_translation = new LanguageTranslation;

                $language_translation->brand_id           = $brands->id;
                $language_translation->title              = $brands->name;
                $language_translation->language_key       = $this->lang;
                $language_translation->save();
                return $brands;
            } else {
                $lang_brand = LanguageTranslation::where([['brand_id', $brand->id], ['language_key', $this->lang]])->get();
                if ($lang_brand->count() == 0) {
                    $language_translation = new LanguageTranslation;

                    $language_translation->brand_id           = $brand->id;
                    $language_translation->title              = $brand->name;
                    $language_translation->language_key       = $this->lang;
                    $language_translation->save();
                }
                return $brand;
            }
        }
    }
    private function getCategories($pro)
    {
        if (isset($pro['category'])) {
            $categories_arr = explode('|', $pro['category']);
            $witPcat_ids = [];
            foreach ($categories_arr as $value) {
                $cat_list_arr = explode('->', $value);
                $parent_id = 0;
                $cat_ids = [];
                foreach ($cat_list_arr as $cat_) {
                    $cat = trim($cat_);
                    $category = Category::where([['name', $cat], ['store_id', Auth::user()->store_id]])->first();
                    if ($category != null) {
                        $lang_cat = LanguageTranslation::where([['category_id', $category->id], ['language_key', $this->lang]])->get();
                        if ($lang_cat->count() == 0) {
                            $language_translation = new LanguageTranslation;

                            $language_translation->category_id           = $category->id;
                            $language_translation->title              = $category->name;
                            $language_translation->language_key       = $this->lang;
                            $language_translation->save();
                        }
                        array_push($cat_ids,  $category->id);
                    } else {
                        $new_cat = new Category;
                        $new_cat->name        = $cat;
                        $new_cat->parent_id   = $parent_id;
                        $new_cat->sort_order   = 1;
                        $new_cat->pos_display   = 1;
                        $new_cat->web_display   = 1;
                        $new_cat->dinein_display   = 1;
                        $new_cat->store_id    = Auth::user()->store_id;
                        $new_cat->created    = Auth::user()->store_id;
                        $new_cat->save();
                        $parent_id             = $new_cat->id;
                        array_push($cat_ids,  $new_cat->id);

                        $language_translation = new LanguageTranslation;
                        $language_translation->category_id         = $new_cat->id;
                        $language_translation->title              = $new_cat->name;
                        $language_translation->language_key       = $this->lang;

                        $language_translation->save();
                    }
                }
                array_push($witPcat_ids,  $cat_ids);
            }
            return $witPcat_ids;
        }
    }
    private function getSuppliers($pro)
    {
        if (isset($pro['supplier'])) {
            $sup_arr = explode('|', $pro['supplier']);
            $sup_ids = [];
            foreach ($sup_arr as  $sup_) {
                $sup = trim($sup_);
                $supplier = Supplier::where([['name', $sup], ['store_id', Auth::user()->store_id]])->first();

                if ($supplier != null) {
                    array_push($sup_ids,  $supplier->id);
                } else {
                    $new_sup = new Supplier;
                    $new_sup->name        = $sup;
                    $new_sup->store_id    = Auth::user()->store_id;
                    $new_sup->created    = Auth::user()->store_id;
                    $new_sup->save();
                    array_push($sup_ids,  $new_sup->id);
                }
            }
            return $sup_ids;
        }
    }
    private function addLanguageTranslations($product, $request)
    {
        $languages = Auth::user()->store->languages->where('short_name', $this->lang)->toArray();
        foreach ($languages as $language) {
            $has_seo          = 'has_seo_' . $language['short_name'];
            $title            = 'title_' . $language['short_name'];
            $description      = 'description_' . $language['short_name'];
            $meta_title       = 'meta_title_' . $language['short_name'];
            $meta_keywords    = 'meta_keywords_' . $language['short_name'];
            $meta_description = 'meta_description_' . $language['short_name'];

            $language_translation = new LanguageTranslation;

            $language_translation->product_id         = $product->id;
            $language_translation->title              = $product->name;
            $language_translation->description        = $request['description'];
            $language_translation->meta_title         = $request['metatitle'];
            $language_translation->meta_keywords      = $request['metakeywords'];
            $language_translation->meta_description   = $request['metadescription'];
            $language_translation->language_key       = $language['short_name'];

            $language_translation->save();
        }
        return $language_translation;
    }
    private function ImportAddons($addon)
    {

        // dd($pro);


        $addons = null;

        if (strtolower($addon['hasitem']) == 'yes') {
            // $prod = Product::where(['identifier' => $pro['identifier'], 'store_id' => Auth::user()->store_id])->first();
            $addons = ModelsAddOn::where(['identifier' => $addon['identifier'], 'store_id' => Auth::user()->store_id])->first();
            // dd($prod);
            if ($addons == null) {
                $addons = ModelsAddOn::whereHas('items', function ($q) use ($addon) {
                    $q->where(['is_item' => '1']);
                })->where(['store_id' => Auth::user()->store_id])->first();
            }
        }
        // dd($prod);
        if ($addons == null) {
            $addons = new ModelsAddOn();
            $addons->store_id                   = Auth::user()->store_id;
            $addons->name                       = $addon['addonname'];
            $addons->type                       = $addon['addontype'];
            if ($addon['active'] == 'Yes')
            {
                $addons->is_active                  = 1;
            }else if ($addon['active'] == 'no')
            {
                $addons->is_active                  = null;
            }
            $addons->identifier                  = $addon['identifier'];
            $addons->max                         = $addon['maximum'];
            $addons->min                         = $addon['minimum'];
            $addons->code                        = $addon['code'];
            $addons->language_key                = $addon['languageKey'];

            $obj = [
                'addons' => $addons,
            ];
            // dd($obj);
            return $obj;
        } else {
            // $addons->updated                    = Auth::user()->id;
            $addons->store_id                   = Auth::user()->store_id;
            $addons->name                       = $addon['addonname'];
            $addons->type                       = $addon['addontype'];
            $addons->language_key                = $addon['languagekey'];
            if ($addon['active'] == 'Yes')
            {
                $addons->is_active                  = 1;
            }else if ($addon['active'] == 'no')
            {
                $addons->is_active                  = null;
            }
            $addons->identifier                  = $addon['identifier'];
            $addons->max                         = $addon['maximum'];
            $addons->min                         = $addon['minimum'];
            $addons->code                        = $addon['code'];
            // dd($addon,$addons);






            // dd($addons,$addon);

            $obj = [
                'addons' => $addons,

            ];
            // dd($obj);
            return $obj;
        }
    }
    private function mapVariants($product, $request)
    {
        $get_variant = ProductVariant::where([['sku', $request['sku']], ['store_id', Auth::user()->store_id]])->first();
        if ($get_variant == null) {
            $variant_pro = new ProductVariant;
            // dd($product);
            $variant_pro->product_id               = $product->id;
            $variant_pro->name                     = $product->name;
            $variant_pro->sku                      =  $request['sku'];
            // $variant_pro->sku                      =  $this->getSku($request);
            $variant_pro->attribute_value_1         = isset($request['attribute_1_value']) ? $request['attribute_1_value'] : '';
            $variant_pro->attribute_value_2         = isset($request['attribute_2_value']) ? $request['attribute_2_value'] : '';
            $variant_pro->attribute_value_3         = isset($request['attribute_3_value']) ? $request['attribute_3_value'] : '';

            $variant_pro->store_id                  = Auth::user()->store_id;
            //$variant_pro->markup                 = $request->markup;
            $variant_pro->is_active               = 1;
            $variant_pro->retail_price             = $request['retailprice'];

            if ($request['allowoutofstock'] == 'YES') {
                $variant_pro->allow_out_of_stock          = 1;
            } else {
                $variant_pro->allow_out_of_stock           = 0;
            }
            $variant_pro->barcode                  = 'data:image/png;base64,' . \DNS1D::getBarcodePNG($variant_pro->sku, "C128", 1, 33);
            $variant_pro->before_discount_price    = $request['compareprice'];
            // dd($variant_pro);
            $variant_pro->save();
            return $variant_pro;
        } else {
            // dd($get_variant);

            // dd($product);
            $get_variant->product_id               = $product->id;
            $get_variant->name                     = $product->name;
            // $get_variant->sku                      =  $request['sku'];
            // $get_variant->sku                      =  $this->getSku($request);
            $get_variant->attribute_value_1         = isset($request['attribute_1_value']) ? $request['attribute_1_value'] : '';
            $get_variant->attribute_value_2         = isset($request['attribute_2_value']) ? $request['attribute_2_value'] : '';
            $get_variant->attribute_value_3         = isset($request['attribute_3_value']) ? $request['attribute_3_value'] : '';

            $get_variant->store_id                  = Auth::user()->store_id;
            //$get_variant->markup                 = $request->markup;
            $get_variant->is_active               = 1;
            $get_variant->retail_price             = $request['retailprice'];

            if ($request['allowoutofstock'] == 'YES') {
                $get_variant->allow_out_of_stock          = 1;
            } else {
                $get_variant->allow_out_of_stock           = 0;
            }

            $get_variant->barcode                  = 'data:image/png;base64,' . \DNS1D::getBarcodePNG($request['sku'], "C128", 1, 33);
            $get_variant->before_discount_price    = $request['compareprice'];
            // dd($get_variant);
            $get_variant->save();
            return $get_variant;
        }
    }
    public function addTranslations($product, $translations, $pro)
    {
        $attri = ProductAttribute::with('values')->where([['product_id', $product->id], ['language_key', $this->lang]])->get();
        foreach ($attri as $attrb) {
            ProductAttributeValue::where('product_attribute_id', $attrb->id)->delete();
        }
        ProductAttribute::with('values')->where([['product_id', $product->id], ['language_key', $this->lang]])->delete();

        $trans = $translations[0];
        if (isset($pro['attribute_1'])) {
            $attribute = new ProductAttribute;
            $attribute->language_key = $trans['short_name'];
            $attribute->name = $pro['attribute_1'];
            $attribute->product_id = $product->id;
            $attribute->save();

            $product->attribute_1 = $pro['attribute_1'];

            $value = new ProductAttributeValue;
            $value->product_attribute_id = $attribute->id;
            $value->name = $pro['attribute_1_value'];
            $value->save();
        }

        if (isset($pro['attribute_2'])) {
            $attribute2 = new ProductAttribute;
            $attribute2->language_key = $trans['short_name'];
            $attribute2->name = $pro['attribute_2'];
            $attribute2->product_id = $product->id;
            $attribute2->save();

            $product->attribute_2 = $pro['attribute_2'];

            $value2 = new ProductAttributeValue;
            $value2->product_attribute_id = $attribute2->id;
            $value2->name = $pro['attribute_2_value'];
            $value2->save();
        }

        if (isset($pro['attribute_3'])) {
            $attribute3 = new ProductAttribute;
            $attribute3->language_key = $trans['short_name'];
            $attribute3->name = $pro['attribute_3'];
            $attribute3->product_id = $product->id;
            $attribute3->save();

            $product->attribute_3 = $pro['attribute_3'];

            $value3 = new ProductAttributeValue;
            $value3->product_attribute_id = $attribute3->id;
            $value3->name = $pro['attribute_3_value'];
            $value3->save();
        }
        $product->save();
    }
    private function attachCategoryIds($category_ids, $product)
    {
        if (is_array($category_ids) && sizeof($category_ids) > 0) {
            $product->categories()->detach();
            foreach ($category_ids as $key) {
                $product->categories()->attach($key);
            }
        }
    }
    private function attachSupplierIds($supplier_ids, $product)
    {
        if (is_array($supplier_ids) && sizeof($supplier_ids) > 0) {
            $product->product_supplires()->detach();
            foreach ($supplier_ids as $supplier_id) {
                $product->product_supplires()->attach($supplier_id);
            }
        }
    }
    private function  AddProductstock($product, $request, $variant, $stock_alert)
    {

        $outlets = Auth::user()->store->outlets->toArray();
        foreach ($outlets as $value) {
            $outlet_id = $value['id'];

            $stock = ProductStock::where([['outlet_id', $outlet_id], ['variant_id', $variant->id], ['product_id', $product->id]])->first();
            if ($stock === null) {
                $product_Stock                    = new ProductStock();
                $product_Stock->invoice_date      = date('Y-m-d H:i:s');
                $product_Stock->cost_price        = $request['supplyprice'];
                $product_Stock->sale_price        = $request['retailprice'];
                $product_Stock->quantity          = $request['stock'] == null ? 0 : $request['stock'];
                $product_Stock->re_order_quantity = $request['reorderquantity'];
                $product_Stock->re_order_point    = $request['reorderpoint'];
                $product_Stock->product_id        = $product->id;
                $product_Stock->variant_id        = $variant->id;
                $product_Stock->created           = $product_Stock->updated = Auth::user()->id;
                $product_Stock->created_at        = $product_Stock->updated_at = date('Y-m-d H:i:s');
                $product_Stock->outlet_id         = $outlet_id;
                $product_Stock->type              = OrderType::In;
                $product_Stock->is_default        = 1;
                $product_Stock->is_remove = false;
                $product_Stock->save();
            } else {
                $stock->cost_price        = $request['supplyprice'];
                $stock->sale_price        = $request['retailprice'];
                if ($stock_alert == 'add') {
                    $added_stock = $stock->quantity + $request['stock'];
                    $stock->quantity          = $added_stock;
                } else {
                    $stock->quantity          = $request['stock'];
                }
                $stock->re_order_quantity = $request['reorderquantity'];
                $stock->re_order_point    = $request['reorderpoint'];
                $stock->updated           = Auth::user()->id;
                $stock->updated_at        = date('Y-m-d H:i:s');
                $stock->type              = OrderType::In;
                $stock->is_default        = 1;
                $stock->is_remove = false;
                $stock->save();
            }
        }
    }
    private function addSingleVairant($product, $request)
    {

        if (isset($request)) {
            $prod = Product::where([['name', $request['name']], ['store_id', Auth::user()->store_id]])->first();
            if ($prod == null) {
                $variant = new ProductVariant;
                $variant->product_id               = $product->id;
                $variant->name                     = $product->name;
                $variant->sku                      =  $request['sku'];
                // $variant->sku                      =  $this->getSku($request);
                $variant->store_id                  = Auth::user()->store_id;
                $variant->created               = Auth::user()->id;
                $variant->attribute_value_1   = $product->name;
                // $variant->attribute_value_2   = $request->name;
                //$variant->markup                 = $request->markup;
                $variant->is_active               = 1;
                $variant->retail_price             = $request['retailprice'];

                if ($request['allowoutofstock'] == 'YES') {
                    $variant->allow_out_of_stock          = 1;
                } else {
                    $variant->allow_out_of_stock           = 0;
                }

                $variant->barcode                  = 'data:image/png;base64,' . \DNS1D::getBarcodePNG($variant->sku, "C128", 1, 33);
                $variant->before_discount_price    = $request['compareprice'];
                // dd($variant);
                $variant->save();
                return $variant;
            } else {
                $variant_edit = ProductVariant::where([['sku', $request['sku']], ['product_id', $prod->id], ['store_id', Auth::user()->store_id]])->first();
                if ($variant_edit == null) {
                    $variant = new ProductVariant;
                    $variant->product_id                = $product->id;
                    $variant->name                      = $product->name;
                    $variant->sku                       = $request['sku'];
                    // $variant->sku                      =  $this->getSku($request);
                    $variant->store_id                  = Auth::user()->store_id;
                    $variant->created                   = Auth::user()->id;
                    $variant->attribute_value_1         = $product->name;
                    // $variant->attribute_value_2   = $request->name;
                    //$variant->markup                 = $request->markup;
                    $variant->is_active                 = 1;
                    $variant->retail_price             = $request['retailprice'];
                    if ($request['allowoutofstock'] == 'YES') {
                        $variant->allow_out_of_stock          = 1;
                    } else {
                        $variant->allow_out_of_stock           = 0;
                    }
                    $variant->barcode                  = 'data:image/png;base64,' . \DNS1D::getBarcodePNG($variant->sku, "C128", 1, 33);
                    $variant->before_discount_price    = $request['compareprice'];
                    // dd($variant);
                    $variant->save();
                    return $variant;
                } else {

                    $variant_edit->product_id                = $product->id;
                    $variant_edit->name                      = $product->name;
                    // $variant_edit->sku                      =  $request['sku'];
                    // $variant_edit->sku                      =  $this->getSku($request);
                    // $variant_edit->store_id                  = Auth::user()->store_id;
                    $variant_edit->updated                      = Auth::user()->id;
                    $variant_edit->attribute_value_1         = $product->name;
                    // $variant_edit->attribute_value_2      = $request->name;
                    //$variant_edit->markup                 = $request->markup;
                    $variant_edit->is_active                 = 1;
                    $variant_edit->retail_price             = $request['retailprice'];
                    if ($request['allowoutofstock'] == 'YES') {
                        $variant_edit->allow_out_of_stock          = 1;
                    } else {
                        $variant_edit->allow_out_of_stock           = 0;
                    }
                    $variant_edit->barcode                  = 'data:image/png;base64,' . \DNS1D::getBarcodePNG($request['sku'], "C128", 1, 33);
                    $variant_edit->before_discount_price    = $request['compareprice'];
                    // dd($variant_edit);
                    $variant_edit->save();
                    return $variant_edit;
                }
            }
        }
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

    private function getSku($request, $index = null)
    {
        if ($request->sku_custom) {
            if ($request->has_variant) {
                return $request->variants[$index]['sku'];
            } else {

                if ($request->sku_type == 0) {
                    $request->sku  = $request->sku_custom;
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
        if ($this->store->sku_generation == 0) {
            return $this->getSkuByName($request);
        } else {
            return $this->getSkuByNumber();
        }
    }

    private function getSkuByName($request)
    {
        $this->sku_name_counter = $this->sku_name_counter + 1;
        return sprintf('%s-%d', $request->default_sku_name, $this->sku_name_counter);
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
}
