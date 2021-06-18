<?php

namespace App\Http\Controllers\Api\Catalogue;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\ApiController;
use App\Core\RequestExecutor;
use App\Requests\Catalogue\Category\GetAllCategoryRequest;
use App\Requests\Catalogue\Category\ToggleCategoryRequest;
use App\Requests\Catalogue\Category\DeleteCategoryRequest;
use App\Requests\Catalogue\Category\BulkCategoryRequest;
use App\Requests\Catalogue\Category\AddCategoryRequest;
use App\Requests\Catalogue\Category\EditCategoryRequest;
use Auth;

class CategoryController extends ApiController
{
    public function __construct(RequestExecutor $requestExecutor)
    {
        parent::__construct();
        $this->RequestExecutor = $requestExecutor;
    }

    public function getCategories(GetAllCategoryRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response->Payload);
    }

    public function toggleCategory(ToggleCategoryRequest $request)
    {
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }

    public function deleteCategory(DeleteCategoryRequest $request){
        $request->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }

    public function bulkCategory(BulkCategoryRequest $request){
        $request->store_id = Auth::user()->store_id;
        $request->user = Auth::user();
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }

    public function addCategory(Request $request)
    {
        $languages = Auth::user()->store->languages->toArray();

        $category_request = new AddCategoryRequest();

        foreach ($languages as $language)
        {
            $has_seo = 'has_seo_'.$language['short_name'];
            $title   = 'title_'.$language['short_name'];
            $meta_title = 'meta_title_'.$language['short_name'];
            $meta_keywords = 'meta_keywords_'.$language['short_name'];
            $meta_description = 'meta_description_'.$language['short_name'];

            $category_request->{$has_seo} = $request->{$has_seo};
            $category_request->{$title} = $request->{$title};
            $category_request->{$meta_title} = $request->{$meta_title};
            $category_request->{$meta_keywords} = $request->{$meta_keywords};
            $category_request->{$meta_description} = $request->{$meta_description};
        }

        $category_request->category_images = $request->images;

        $response = $this->RequestExecutor->execute($category_request);
        return response()->json($response);
    }

    public function updateCategory(Request $request){

        $languages = Auth::user()->store->languages->toArray();

        $EditCategory = new EditCategoryRequest();

        foreach ($languages as $language)
        {
            $has_seo = 'has_seo_'.$language['short_name'];
            $title   = 'title_'.$language['short_name'];
            $meta_title = 'meta_title_'.$language['short_name'];
            $meta_keywords = 'meta_keywords_'.$language['short_name'];
            $meta_description = 'meta_description_'.$language['short_name'];

//            dd($params);
            $EditCategory->{$has_seo} = isset($request[$has_seo]) ? $request[$has_seo] : 0;
            $EditCategory->{$title} = $request[$title];
            $EditCategory->{$meta_title} = $request[$meta_title];
            $EditCategory->{$meta_keywords} = $request[$meta_keywords];
            $EditCategory->{$meta_description} = $request[$meta_description];
        }
        $EditCategory->category_images = $request->images;

        $EditCategory->store_id = Auth::user()->store_id;
        $response = $this->RequestExecutor->execute($EditCategory);
        return response()->json($response);
    }
}
