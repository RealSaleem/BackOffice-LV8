<?php

namespace App\Http\Controllers\Plugins;

use Illuminate\Http\Request;
use App\Core\RequestExecutor;
use Auth;
use App\Models\SalesRep;
use App\Requests\Plugins\SalesRep\GetAllSalesRepRequest;
use App\Requests\Plugins\SalesRep\AddSalesRepRequest;
use App\Requests\Plugins\SalesRep\GetSalesRepRequest;
use App\Requests\Plugins\SalesRep\UpdateSalesRepRequest;
use App\Requests\Plugins\SalesRep\DeleteSalesRepRequest;
use App\Http\Controllers\Controller;


class SalesRepController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct(RequestExecutor $requestExecutor)
    {  
        parent::__construct();
        $this->RequestExecutor = $requestExecutor;
    }

    public function index(GetAllSalesRepRequest $request)
    {  
            $salesrep = $this->RequestExecutor->execute($request, Auth::user());
            return view('plugins.sales-rep.index', ['salesrep' => $salesrep->Payload]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
            $data = [
            'model'             => new SalesRep, 
            'is_edit'           => false,
            'route'             => route('sales-rep.store')
        ];

        return view('plugins.sales-rep.form', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AddSalesRepRequest $request)
    {
        $response = $this->RequestExecutor->execute($request);

        if($response->IsValid){
            return redirect()->route('sales-rep.index')->with('done',\Lang::get('Representative has been added successfully'));
        }else{
            return redirect()->back()->with('error', $response->Errors);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(GetSalesRepRequest $request, $id)
    {
        //
        $request->id = $id;
        $response = $this->RequestExecutor->execute($request);
        $data = [
            'model'             => $response->Payload, 
            'is_edit'           => true,
        ];
        return view('plugins.sales-rep.view', ['salesrep' => $response->Payload], $data); 
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       
        $request = new GetSalesRepRequest();
        $request->id = $id;
        $response = $this->RequestExecutor->execute($request);
        $data = [
            'model'             => $response->Payload, 
            'is_edit'           => true,
            'route'             => route('sales-rep.update', $id), 
            'id'                => $id
        ];

        return view('plugins.sales-rep.form', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSalesRepRequest $request, $id)
    {
        //
        $request->id = $id;
        $response = $this->RequestExecutor->execute($request);
        if($response->IsValid){
            return redirect()->route('sales-rep.index')->with('done', $response->Message);
        } else {
            return redirect()->back()->with('error',$response->Errors);  
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {  
        $delete = SalesRep::where(['id' => $id ])->delete();
        if($delete){
            return response()->json(true);
        }else{
            return response()->json(false);
        }
    }
}
