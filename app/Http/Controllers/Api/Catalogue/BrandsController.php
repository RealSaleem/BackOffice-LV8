<?php

namespace App\Http\Controllers\Api\Catalogue;

use http\Client\Response;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Core\RequestExecutor;
use App\Requests\Catalogue\Brands\GetAllBrandsRequest;
use App\Requests\Catalogue\Brands\ToggleBrandsRequest;
use App\Requests\Catalogue\Brands\DeleteBrandsRequest;
use App\Requests\Catalogue\Brands\BulkBrandsRequest;
use App\Requests\Catalogue\Brands\AddBrandsRequest;
use App\Requests\Catalogue\Brands\EditBrandsRequest;
use Auth;


class BrandsController extends ApiController
{

    public function __construct(RequestExecutor $requestExecutor)
    {
        parent::__construct();
        $this->RequestExecutor = $requestExecutor;
    }

    public function getbrands(GetAllBrandsRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response->Payload);
    }

    public function toggleBrands(ToggleBrandsRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }

    public function deleteBrands(DeleteBrandsRequest $request)
    {
        $request->store_id  = Auth::user()->store_id;
        $request->id        = $request->brands_id;
        $response           = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }

    public function bulkBrands(BulkBrandsRequest $request)
    {
        $request->store_id  = Auth::user()->store_id;
        $request->user  = Auth::user();
        $response           = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }
     public function addBrands(Request $request)
    {
        $languages = Auth::user()->store->languages->toArray();

        $brand_request = new AddBrandsRequest();

        foreach ($languages as $language)
        {
            $has_seo = 'has_seo_'.$language['short_name'];
            $title   = 'title_'.$language['short_name'];
            $meta_title = 'meta_title_'.$language['short_name'];
            $meta_keywords = 'meta_keywords_'.$language['short_name'];
            $meta_description = 'meta_description_'.$language['short_name'];

            $brand_request->{$has_seo} = $request->{$has_seo};
            $brand_request->{$title} = $request->{$title};
            $brand_request->{$meta_title} = $request->{$meta_title};
            $brand_request->{$meta_keywords} = $request->{$meta_keywords};
            $brand_request->{$meta_description} = $request->{$meta_description};
        }

        $brand_request->brand_images = $request->images;
        $response = $this->RequestExecutor->execute($brand_request);
        return response()->json($response);
    }

    public function updateBrands(Request $request){
        $languages = Auth::user()->store->languages->toArray();

        $brand_request = new EditBrandsRequest();
        foreach ($languages as $language)
        {
            $has_seo = 'has_seo_'.$language['short_name'];
            $title   = 'title_'.$language['short_name'];
            $meta_title = 'meta_title_'.$language['short_name'];
            $meta_keywords = 'meta_keywords_'.$language['short_name'];
            $meta_description = 'meta_description_'.$language['short_name'];

            $brand_request->{$has_seo} = $request->{$has_seo};
            $brand_request->{$title} = $request->{$title};
            $brand_request->{$meta_title} = $request->{$meta_title};
            $brand_request->{$meta_keywords} = $request->{$meta_keywords};
            $brand_request->{$meta_description} = $request->{$meta_description};
        }

        $brand_request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($brand_request);
        return response()->json($response);
    }
}





