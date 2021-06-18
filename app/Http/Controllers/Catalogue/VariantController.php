<?php

namespace App\Http\Controllers\Catalogue;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Core\RequestExecutor;
use Auth;
use App\Requests\Catalogue\Product\AddVarientsRequest;
use App\Requests\Catalogue\Product\EditVarientsRequest;
use App\Requests\Catalogue\Product\GetVariantsByIdRequest;
use App\Requests\Catalogue\Product\GetVariantsTransRequest;
use App\Models\ProductAttribute;
use App\Models\Product;
use Illuminate\Support\Facades\Input;

class VariantController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(RequestExecutor $requestExecutor)
    {
        parent::__construct();
        $this->RequestExecutor = $requestExecutor;
    }
    public function create(Request $request,$id)
    {
        //dd($id);
        $languages = Auth::user()->store->languages->toArray();
        $product = Product::find($id);
        return view('catalogue.variant.create',['languages' => $languages, 'id'=>$id , 'product' => $product ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function save(Request $request,$id)
    {
        $request = new AddVarientsRequest();
        $request->id = $id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }
    public function edit($id)
    {
        // dd($id);
        $user = Auth::user();
        $store = $user->store;
        $languages = $store->languages->toArray();
        $outlets = $store->outlets->toArray();
//        dd($outlets);

//        $languages = Auth::user()->store->languages->toArray();

        $request = new GetVariantsByIdRequest();
        $request->id = $id;
        $response = $this->RequestExecutor->execute($request);
        $variants = $response->Payload;

        // $attributes = ProductAttribute::with('values')->where('product_id',$id)->get()->toArray();
        $product     = Product::find($id);
        $data = [
            'languages'    => $languages,
            // 'variants'     => $variants,
            // 'attributes'   => $attributes,
            'name'         => $product->name,
            'product'         => $product,
            'outlets'         => $outlets,
        ];
        // dd($data);
        return view('catalogue.variant.edit',$data);
    }
     public function update(Request $request, $id)
    {
        // $languages = Auth::user()->store->languages->toArray();
        $request = new EditVarientsRequest();
        $request->id = $id;
        $response = $this->RequestExecutor->execute($request);
        // dd($response);
        return response()->json($response);
    }
    public function get_edit_data(Request $request)
    {
        $id = Input::get('id');
        $languages = Auth::user()->store->languages->toArray();

        $request = new GetVariantsByIdRequest();
        $request->id = $id;
        $response = $this->RequestExecutor->execute($request);
        $variants = $response->Payload;

        $trans_request = new GetVariantsTransRequest();
        $trans_request->id = $id;
        $trans_response = $this->RequestExecutor->execute($trans_request);
        $trans = $trans_response->Payload;
        $product     = Product::find($id);
        // dd($trans);
       if(sizeof($variants) > 0  ){
            $attr_value_arr1 = [];
            foreach ($variants[0]['attribute'][0]['attr1_values'] as $attr_val1) {
                array_push($attr_value_arr1, $attr_val1['value']);
            }
             $attr_value_arr2 = [];
            foreach ($variants[0]['attribute'][0]['attr2_values'] as $attr_val2) {
                array_push($attr_value_arr2, $attr_val2['value']);
            }
             $attr_value_arr3 = [];
            foreach ($variants[0]['attribute'][0]['attr3_values'] as $attr_val3) {
                array_push($attr_value_arr3, $attr_val3['value']);
            }


            $response->data['product'] = [
                'attr1_values'                => $attr_value_arr1,
                'attr2_values'                => $attr_value_arr2,
                'attr3_values'                => $attr_value_arr3,
                'attribute_1'                 => $product->attribute_1,
                'attribute_2'                 => $product->attribute_2,
                'attribute_3'                 => $product->attribute_3,

                'before_discount_price'       => '',
                'custom_sku'                  => false,
                'default_sku_name'            => '',
                'handle'                      => '',
                'has_variant'                 => true,
                'is_composite'                => false,
                'markup'                      => '',
                'prefix'                      => '',
                'retail_price'                => '',
                'sku'                         => '',
                'sku_by_name'                 => false,
                'sku_custom'                  => false,
                'sku_name'                    => false,
                'sku_number'                  => true,
                'variants'                    => $variants,
                'product'                     => $product,
            ];
       }

        // $arr = [];
        // foreach ($variants  as $value) {
        //     array_push($arr, $value['attribute']);
        // }
        $response->data['languages'] = $trans;

        // dd($response->data['languages'] );
        return response()->json($response);
    }
}
