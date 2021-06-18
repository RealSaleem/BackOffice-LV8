<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Core\RequestExecutor;
use App\Helpers\ImageUploader;

class ImageController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(RequestExecutor $requestExecutor){
        parent::__construct();
        $this->RequestExecutor = $requestExecutor;
    }

    public function uploadStoreImage(Request $request){

        $response = ImageUploader::uploadGeneral($request);
        return response()->json($response);
    }

    public function uploadUserImage(Request $request){

        $response = ImageUploader::uploadUserImage($request);
        return response()->json($response);
    }

    public function uploadBrandImage(Request $request){

        $response = ImageUploader::uploadBrandImage($request);
        return response()->json($response);
    }

    public function uploadCategoryImage(Request $request){

        $response = ImageUploader::uploadCategoryImage($request);
        return response()->json($response);
    }

    public function uploadProductImage(Request $request){

        $response = ImageUploader::uploadProductImage($request);
        return response()->json($response);
    }
    public function uploadOutletImage(Request $request){

        $response = ImageUploader::uploadOutletImage($request);
        return response()->json($response);
    }
}
