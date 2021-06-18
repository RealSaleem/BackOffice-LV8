<?php

namespace App\Http\Controllers\Plugins;

use App\Core\RequestExecutor;
use App\Models\UsersStore;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Requests\Plugins\GetAllPluginsRequest;
use App\Models\Plugin;
use App\Models\StorePlugin;
use App\Models\Loyalty;
use Auth;

class PluginsController extends Controller
{

    public function __construct(RequestExecutor $requestExecutor)
    {
        // parent::__construct();
        $this->RequestExecutor = $requestExecutor;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $store_id = Auth::user()->store_id;
        $plugins = StorePlugin::where('store_id', $store_id)->get();
        return view('plugins.index', ['plugins' => $plugins ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $languages = Auth::user()->store->languages->toArray();

        $request = new GetAllPluginsRequest();
        $response = $this->RequestExecutor->execute($request);
        $plugins = $response->Payload;

        $request = new GetEmptyPluginsRequest();
        $response = $this->RequestExecutor->execute($request);
        $plugins = $response->Payload;

        $data = [

            'plugins' => $plugins,
            'languages' => $languages,
            'plugins' => $plugins,
            'title' => 'New Plugins',
            'save_title' => 'Save',
            'route' => route('plugins.store'),
            'is_edit'   => false

        ];


        return view('plugins.form', $data);
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

        $plugins_request = new AddPluginsRequest();

        foreach ($languages as $language) {
            $has_seo = 'has_seo_' . $language['short_name'];
            $title   = 'title_' . $language['short_name'];
            $meta_title = 'meta_title_' . $language['short_name'];
            $meta_keywords = 'meta_keywords_' . $language['short_name'];
            $meta_description = 'meta_description_' . $language['short_name'];

            $plugins_request->{$has_seo} = $request->{$has_seo};
            $plugins_request->{$title} = $request->{$title};
            $plugins_request->{$meta_title} = $request->{$meta_title};
            $plugins_request->{$meta_keywords} = $request->{$meta_keywords};
            $plugins_request->{$meta_description} = $request->{$meta_description};
        }

        $plugins_request->plugins_images = $request->images;
        $response = $this->RequestExecutor->execute($plugins_request);

        $response->Message = \Lang::get('toaster.plugins_added');
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
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $request = new GetAllPluginsRequest();
        $response = $this->RequestExecutor->execute($request);

        $crequest = new GetPluginsByIdRequest();
        $crequest->id = $id;
        $cresponse = $this->RequestExecutor->execute($crequest);
        $plugins = $cresponse->Payload;

        $data = [
            'plugins'  => $response->Payload,
            'languages'   => Auth::user()->store->languages->toArray(),
            'plugins'    => $plugins,
            'title'       => $plugins['title_en'],
            'save_title'  => 'Update',
            'route'       => route('plugins.update', ['id' => $id]),
            'is_edit'     => true
        ];
        // dd($data);

        return view('plugins.form', $data);
    }

    public function Loyalty()
    {
        $loyalty = Loyalty::where('store_id', Auth::user()->store_id)->first();

        if ($loyalty === null) {
            $loyalty = new Loyalty();
            $loyalty->store_id = Auth::user()->store_id;
            $loyalty->save();
        }

        return view('plugins.Loyalty', ['plugin' => $loyalty]);
    }

    public function view()
    {
        $loyalty = Loyalty::where('store_id', Auth::user()->store_id)->first();
        return view('plugins.view', ['loyalty' => $loyalty]);
    }

    // Discount
    public function discount()
    {

        $store_plugins = StorePlugin::with('plugin')->where('store_id', Auth::user()->store_id)->get();
        $store_plugin = null;

        if (sizeof($store_plugins) > 0) {
            $discount_plugin = [];
            foreach ($store_plugins as $plugin) {
                if ($plugin->plugin->slug == 'discount') {
                    $store_plugin = $plugin;
                    $discount_plugin['api_url'] = $plugin->api_url;
                    $discount_plugin['api_password'] = $plugin->api_password;
                    $discount_plugin['api_secret'] = $plugin->api_secret;
                    $discount_plugin['api_token'] = $plugin->api_token;
                }
            }

            if (sizeof($discount_plugin) > 0) {

                if (!\Session()->has('discount_token')) {

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $discount_plugin['api_url'] . "oauth/token",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_FAILONERROR, true,
                        CURLOPT_POSTFIELDS => array(
                            'grant_type' => 'password',
                            'client_id' => '2',
                            'client_secret' => $discount_plugin['api_secret'],
                            'username' => Auth::user()->email,
                            'password' => $discount_plugin['api_password']
                        ),
                    ));

                    $response = json_decode(curl_exec($curl));

                    if (isset($response->error)) {
                        if (is_string($response->error) && $response->error == "invalid_grant") {
                            abort(403, 'Discount API invalid grant, please provide valid credentials.');
                        }
                    }

                    curl_close($curl);

                    if(isset($response->access_token) && isset($discount_plugin['api_url'])) {

                        if(isset($store_plugin)){
                            $store_plugin->api_token = $response->access_token;
                            $store_plugin->save();
                        }


                        \Session()->put('discount_token', $response->access_token);
                        \Session()->put('discount_api_url', $discount_plugin['api_url']);
                    } else {
                        abort(403, 'Discount API is not working, please check if API service is on.');
                    }
                }

                return view('plugins.discount');
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }
    }

    // Reviews
    public function review()
    {

        $store_plugins = StorePlugin::with('plugin')->where('store_id', Auth::user()->store_id)->get();

        if (sizeof($store_plugins) > 0) {
            $review_plugin = [];
            foreach ($store_plugins as $plugin) {
                if ($plugin->plugin->slug == 'rating-reviews') {
                    $review_plugin['api_url'] = $plugin->api_url;
                    $review_plugin['api_password'] = $plugin->api_password;
                    $review_plugin['api_secret'] = $plugin->api_secret;
                    $review_plugin['api_token'] = $plugin->api_token;
                }
            }

            if (sizeof($review_plugin) > 0) {

                if (!\Session()->has('review_token')) {

                    $curl = curl_init();

                    curl_setopt_array($curl, array(
                        CURLOPT_URL => $review_plugin['api_url'] . "oauth/token",
                        CURLOPT_RETURNTRANSFER => true,
                        CURLOPT_ENCODING => "",
                        CURLOPT_MAXREDIRS => 10,
                        CURLOPT_TIMEOUT => 0,
                        CURLOPT_FOLLOWLOCATION => true,
                        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                        CURLOPT_CUSTOMREQUEST => "POST",
                        CURLOPT_FAILONERROR, true,
                        CURLOPT_POSTFIELDS => array(
                            'grant_type' => 'password',
                            'client_id' => '2',
                            'client_secret' => $review_plugin['api_secret'],
                            'username' => Auth::user()->email,
                            'password' => $review_plugin['api_password']
                        ),
                    ));

                    $response = json_decode(curl_exec($curl));

                    if (isset($response->error)) {
                        if (is_string($response->error) && $response->error == "invalid_grant") {
                            abort(403, 'Rating & Review API invalid grant, please provide valid credentials.');
                        }
                    }

                    curl_close($curl);

                    if(isset($response->access_token) && isset($review_plugin['api_url'])) {
                        \Session()->put('review_token', $response->access_token);
                        \Session()->put('review_api_url', $review_plugin['api_url']);
                    } else {
                        abort(403, 'Rating & Review API is not working, please check if API service is on.');
                    }
                }

                return view('plugins.review');
            } else {
                abort(404);
            }
        } else {
            abort(404);
        }



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

        $plugins_request = new EditPluginsRequest();

        $plugins_request->id = $id;

        foreach ($languages as $language) {
            $has_seo = 'has_seo_' . $language['short_name'];
            $title   = 'title_' . $language['short_name'];
            $meta_title = 'meta_title_' . $language['short_name'];
            $meta_keywords = 'meta_keywords_' . $language['short_name'];
            $meta_description = 'meta_description_' . $language['short_name'];

            $plugins_request->{$has_seo} = $request->{$has_seo};
            $plugins_request->{$title} = $request->{$title};
            $plugins_request->{$meta_title} = $request->{$meta_title};
            $plugins_request->{$meta_keywords} = $request->{$meta_keywords};
            $plugins_request->{$meta_description} = $request->{$meta_description};
        }

        $plugins_request->plugins_images = $request->images;

        $response = $this->RequestExecutor->execute($plugins_request);
        $response->Message = \Lang::get('toaster.plugins_updated');

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
        $request = new DeletePluginsRequest();
        $request->id = $id;
        $response = $this->RequestExecutor->execute($request);
        return response()->json($response);
    }

    public function pluginstore(){
        $plugins = Plugin::get();
        return view('Plugins.plugin_store',compact('plugins'));
    }
    
    public function pluginpurchase(Request $request)
    {
        $pluginId = $request->id;
        $store_id = Auth::user()->store_id;
        $plugin = Plugin::find($pluginId);
        if($plugin){
            $StorePlugin = StorePlugin::where('store_id',$store_id)->where('plugin_id',$pluginId)->first();
            if($StorePlugin != null){
                return response()->json(['error' => \Lang::get('toaster.plugin_already_purchased') ]);
            }

            $plug = new StorePlugin();
            $plug->store_id     = $store_id;
            $plug->plugin_id    = $pluginId;
            $plug->active       = 0;
            $plug->save();
            return response()->json(['message' => \Lang::get('toaster.plugin_installed') ]);
        }
        return response()->json(['error' => \Lang::get('toaster.plugin_unableTo_installed') ]);

    }

    public function toggle(Request $request)
    {
        $id = $request->id;
        $store_id = Auth::user()->store_id;
        $purshased_plugin = StorePlugin::where('store_id', $store_id)->where('id', $id)->first();

        if($purshased_plugin != null){
            $purshased_plugin->active = 1;
            $purshased_plugin->save();

            $data = [
              'IsValid' => true,
              'message' => \Lang::get('toaster.plugin_unableTo_update'),
            ];
            return response()->json($data);
        }
        $data = [
          'IsValid' => false,
          'message' => \Lang::get('toaster.plugin_unableTo_update'),
        ];
        return response()->json($data);
    }
}
