<?php
namespace App\Http\Controllers\Plugins;
use App\Core\RequestExecutor;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Requests\WebSettings\WebSettingsRequests;
use Auth;
class QRCodeController extends Controller
{
    public function __construct(RequestExecutor $requestExecutor)
    {
        parent::__construct();
        $this->RequestExecutor = $requestExecutor;
    }

    public function index()
    {
        $store      = Auth::user()->store;
        $theme      = $store->websettings->ecom_theme;
        $outlet_id  = $store->webstore->outlet_id;
        $link       = 'https://www.qrmenu.maisalghanim.co/menu?store_id='.$store->id.'&outlet_id='.$outlet_id;

        return view('Plugins.qrcode',['url' => $link,'theme'=>$theme ]);
    }   
}