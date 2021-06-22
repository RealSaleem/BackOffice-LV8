<?php

namespace App\Http\Controllers\Api\Catalogue;

use App\Requests\Catalogue\Brands\ToggleBrandsRequest;
use App\Requests\Catalogue\Product\ToggleProductRequest;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Core\RequestExecutor;
use App\Requests\Catalogue\Product\GetAllProductRequest;
use App\Requests\Catalogue\Product\GetProductByNameRequest;
use App\Requests\Catalogue\Product\AddProductRequest;
use App\Requests\Catalogue\Product\EditProductRequest;
use App\Requests\Catalogue\Product\DeleteProductRequest;
use App\Requests\Catalogue\Product\BulkProductRequest;
use Auth;

class ProductController extends ApiController
{

    public function __construct(RequestExecutor $requestExecutor)
    {
        parent::__construct();
        $this->RequestExecutor = $requestExecutor;
        $this->middleware(['permission:list-product'])->only('getProducts');
        $this->middleware(['permission:add-product'])->only('addProduct');
        $this->middleware(['permission:edit-product'])->only('updateProduct');
        $this->middleware(['permission:delete-product'])->only('deleteproduct');

    }

    public function getProducts(GetAllProductRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        //dd($response);
        return response()->json($response->Payload);
    }

    public function search(Request $request)
    {
        $request = new GetProductByNameRequest();
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }

     public function deleteproduct(DeleteProductRequest $request){
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }
    public function toggleProduct(ToggleProductRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        $response->Message = \Lang::get('products.toggle_update_message');
        return response()->json($response);
    }

     public function bulkproduct(BulkProductRequest $request){
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }

    public function addProduct(Request $request)
    {
        $languages = Auth::user()->store->languages->toArray();

        $product_request = new AddProductRequest();

        foreach ($languages as $language) {
            $has_seo           = 'has_seo_' . $language['short_name'];
            $title             = 'title_' . $language['short_name'];
            $description       = 'description_' . $language['short_name'];
            $meta_title        = 'meta_title_' . $language['short_name'];
            $meta_keywords     = 'meta_keywords_' . $language['short_name'];
            $meta_description  = 'meta_description_' . $language['short_name'];

            $product_request->{$has_seo}           = $request->{$has_seo};
            $product_request->{$title}             = $request->{$title};
            $product_request->{$description}       = $request->{$description};
            $product_request->{$meta_title}        = $request->{$meta_title};
            $product_request->{$meta_keywords}     = $request->{$meta_keywords};
            $product_request->{$meta_description}  = $request->{$meta_description};
        }

        $product_request->product_images = $request->images;
        $response = $this->RequestExecutor->execute($product_request);
        return response()->json($response);
    }

    public function updateProduct(Request $request)
    {

        $languages = Auth::user()->store->languages->toArray();

        $product_request = new EditProductRequest();

        foreach ($languages as $language)
        {
            $has_seo = 'has_seo_'.$language['short_name'];
            $title   = 'title_'.$language['short_name'];
            $description = 'description_'.$language['short_name'];
            $meta_title = 'meta_title_'.$language['short_name'];
            $meta_keywords = 'meta_keywords_'.$language['short_name'];
            $meta_description = 'meta_description_'.$language['short_name'];

            $product_request->{$has_seo} = $request->{$has_seo};
            $product_request->{$title} = $request->{$title};
            $product_request->{$description} = $request->{$description};
            $product_request->{$meta_title} = $request->{$meta_title};
            $product_request->{$meta_keywords} = $request->{$meta_keywords};
            $product_request->{$meta_description} = $request->{$meta_description};
        }

        $response = $this->RequestExecutor->execute($product_request);
        $response->Message = \Lang::get('product.toggle_message');
        return response()->json($response);
    }
}
