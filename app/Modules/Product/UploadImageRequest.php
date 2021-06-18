<?php

namespace App\Modules\Product;
use App\Core\BaseRequest as BaseRequest;
use Illuminate\Http\Request;
use Storage;

class UploadImageRequest extends BaseRequest
{
    public $image;
}

class UploadImageRequestValidator 
{
    public function GetRules(){
        return [
            'image' => 'required|dimensions:min_width=400,min_height=400',
        ];
    }
}

class UploadImageRequestHandler 
{
    public function Serve($request)
    {
        if ($request->hasFile('image')) {
            return $request->image->store('images');
        }        
    }    
}