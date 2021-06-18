<?php

namespace App\Http\Controllers\Catalogue;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use  App\Requests\catalogue\StockControl\GetAllStockControlRequest;
use Auth;

class StockControlController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }




    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        return view('catalogue.stockcontrol.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    { 
        $languages = Auth::user()->store->languages->toArray();
    
        $request = new GetAllStockControlRequest();
        $response = $this->RequestExecutor->execute($request); 
        $stockcontrol = $response->Payload;
    
        $request = new GetEmptyStockControlRequest();
        $response = $this->RequestExecutor->execute($request); 
        $stockcontrol = $response->Payload;

        $data = [

            'stockcontrol' => $stockcontrol,
            'languages' => $languages,
            'stockcontrol' => $stockcontrol,
            'title' => 'stock control',
            'save_title' => 'Save',
            'route' => route('stockcontrol.store'),
            'is_edit'   => false

        ];  
        
              
        return view('catalogue.stockcontrol.form',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $languages = Auth::user()->store->languages->toArray();

        $stockcontrol_request = new AddStockControlRequest();

        foreach ($languages as $language) 
        {
            $has_seo = 'has_seo_'.$language['short_name'];
            $title   = 'title_'.$language['short_name'];
            $meta_title = 'meta_title_'.$language['short_name'];
            $meta_keywords = 'meta_keywords_'.$language['short_name'];
            $meta_description = 'meta_description_'.$language['short_name'];

            $stockcontrol_request->{$has_seo} = $request->{$has_seo};
            $stockcontrol_request->{$title} = $request->{$title};
            $stockcontrol_request->{$meta_title} = $request->{$meta_title};
            $stockcontrol_request->{$meta_keywords} = $request->{$meta_keywords};
            $stockcontrol_request->{$meta_description} = $request->{$meta_description};
        }

        $stockcontrol_request->stockcontrol_images = $request->images;
        $response = $this->RequestExecutor->execute($stockcontrol_request); 

        $response->Message = \Lang::get('toaster.stockcontrol_added'); 
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $request = new GetAllStockControlRequest();
        $response = $this->RequestExecutor->execute($request); 

        $crequest = new GetStockControlByIdRequest();
        $crequest->id = $id;
        $cresponse = $this->RequestExecutor->execute($crequest); 
        $cbrands = $cresponse->Payload;
        
        $data = [
            'stockcontrol'  => $response->Payload,
            'languages'   => Auth::user()->store->languages->toArray(),
            'stockcontrol'    => $stockcontrol,
            'title'       => $stockcontrol['title_en'],
            'save_title'  => 'Update',
            'route'       => route('stockcontrol.update',['id' => $id]),
            'is_edit'     => true
        ];
        // dd($data);
              
        return view('catalogue.stockcontrol.form',$data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $languages = Auth::user()->store->languages->toArray();

        $stockcontrol_request = new EditStockControlRequest();

        $stockcontrol_request->id = $id;

        foreach ($languages as $language) 
        {
            $has_seo = 'has_seo_'.$language['short_name'];
            $title   = 'title_'.$language['short_name'];
            $meta_title = 'meta_title_'.$language['short_name'];
            $meta_keywords = 'meta_keywords_'.$language['short_name'];
            $meta_description = 'meta_description_'.$language['short_name'];

            $stockcontrol_request->{$has_seo} = $request->{$has_seo};
            $stockcontrol_request->{$title} = $request->{$title};
            $stockcontrol_request->{$meta_title} = $request->{$meta_title};
            $stockcontrol_request->{$meta_keywords} = $request->{$meta_keywords};
            $stockcontrol_request->{$meta_description} = $request->{$meta_description};
        }

        $stockcontrol_request->stockcontrol_images = $request->images;

        $response = $this->RequestExecutor->execute($stockcontrol_request);
        $response->Message = \Lang::get('toaster.StockControl_updated');

        return response()->json($response);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
   
    public function destroy(Request $request, $id)
    {   
         $request = new DeleteStockControlRequest();
         $request->id = $id;
         $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }
}
