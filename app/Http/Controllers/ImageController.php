<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Core\RequestExecutor;

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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function upload(Request $request){
        if($request->hasFile('image') || $request->hasFile('images')) {
            // $path = $request->image->store('image');
            // $response = ['path' =>$path];

            $file = $request->file('image');
            $path = $request->image->store('image');

            $base_url = str_replace("public", "", \URL::to('/'));

            $response = [
                'name' => $file->getClientOriginalName(),
                'path' => $base_url.'storage/app/public'.'/'.$path,//url(sprintf('storage/%s',$path)),
                'size' => $file->getClientSize(),
            ];

            // $response = [
            //     'name'  => $file->getClientOriginalName(),
            //     'path' => url(sprintf(env('IMAGE_URL').'/'.$path)),
            //     'size' => $file->getClientSize(),
            // ];

            return response()->json($response);
        }
    }
}
