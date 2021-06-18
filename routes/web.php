<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Reports\ReportsController;
use App\Http\Controllers\Catalogue\ImportController;
use App\Http\Controllers\Catalogue\CategoryController;
use App\Http\Controllers\Customers\CustomerController;
use App\Http\Controllers\Customers\CustomerGroupController;
use App\Http\Controllers\UserManagement\UsersController;
use App\Http\Controllers\UserManagement\RoleController;
use App\Http\Controllers\Apps\AppsController;
use App\Http\Controllers\Plugins\PluginsController;
use App\Http\Controllers\Outlets\OutletsController;
use App\Http\Controllers\StoreController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/clear', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('route:clear');
    Artisan::call('route:cache');
    Artisan::call('view:clear');
    return redirect()->back();
});


Route::get('optimize-product-images/{store_id}', function ($store_id) {
    \App\Jobs\ProcessImages::dispatch((int) $store_id);
    return 'Job created and added in queue.';
});

Route::get('optimize-product-images-process/{store_id}', function ($store_id) {
    \App\Helpers\ImageProcessor::process((int)$store_id);
    return 'Image processing completed';
});

Route::get('/verifyemail/{token}', 'Auth\RegisterController@verify');
// Route::get('/user/verification', 'Auth\RegisterController@verification');
Route::get('/user/verification/{id}', 'Auth\RegisterController@verification');
Route::post('/user/resendmail', 'Auth\RegisterController@resendmail');

Route::get('step1', 'StoreController@step1')->name('setup.step1');
Auth::routes();
Route::group(['middleware' => ['auth', 'has_store']], function () {

    Route::get('/',                                                        [DashboardController::class, 'index']);
    Route::get('/home',                                               [DashboardController::class, 'index']);
    Route::get('dashboard',                                         [DashboardController::class, 'index'])->name('backoffice.dashboard');
    Route::post('dashboard/kpis',                               [DashboardController::class, 'getKpiData'])->name('backoffice.dashboard.kpis');
    Route::post('orders-list',                                        [DashboardController::class, 'getOrders'])->name('backoffice.order.list');
    Route::post('image/upload',                                  'ImageController@upload')->name('import.product.images');
    /* View Loader Routes */
    //  Route::group(['namespace' => 'Catalogue'], function () {
    Route::prefix('Catalogue')->group(function () {
        Route::resource('category',                                'App\Http\Controllers\Catalogue\CategoryController')->only(['index', 'create', 'edit']);
        Route::resource('brands',                                   'App\Http\Controllers\Catalogue\BrandsController')->only(['index', 'create', 'edit']);
        Route::resource('supplier',                                 'App\Http\Controllers\Catalogue\SupplierController')->only(['index', 'create', 'edit']);
        Route::resource('stockcontrol',                          'App\Http\Controllers\Catalogue\StockControlController');
        Route::resource('product',                                 'App\Http\Controllers\Catalogue\ProductController')->only(['index', 'create', 'edit']);
        Route::resource('addon',                                    'App\Http\Controllers\Catalogue\AddOnController')->only(['index', 'create', 'edit']);
        Route::resource('variant',                                   'App\Http\Controllers\Catalogue\VariantController')->only(['edit']);
        Route::resource('item',                                       'App\Http\Controllers\Catalogue\ItemController');
        Route::get('delete-item',                                    [ItemController::class, 'deleteItem'])->name('delete.item');
        Route::post('bulk-item',                                     [ItemController::class, 'bulkItem'])->name('toggle.item.all');
        Route::get('addons/addItems',                          [AddOnController::class, 'itemCreate'])->name('addon.item_create');
        Route::get('import/products',                            [ImportController::class, 'index']);
        Route::post('import/products',                          [ImportController::class, 'import'])->name('import.products');
        Route::post('import/translations',                      [ImportController::class, 'importTranslations'])->name('import.Translations');
        Route::post('product/import/images',               [ImportController::class, 'images'])->name('product.import.images');
        Route::get('export/products',                             [ImportController::class, 'export'])->name('export.products');
        Route::post('product/import/Sample',               [ImportController::class, 'ImportSampleCatelouge'])->name('product.import_sample_catelouge');
        Route::get('import/addons',                               [ImportController::class, 'index1'])->name('import.addon.index');
        Route::post('import/addons',                             [ImportController::class, 'importAddons'])->name('import.addons');
        Route::get('export/addons',                               [ImportController::class, 'exportAddons'] )->name('export.addons');
    });
    //  Route::group(['namespace' => 'Reports'], function () {
    Route::prefix('Reports')->group(function () 
    {
        Route::resource('reports',                                   'App\Http\Controllers\Reports\ReportsController');
        Route::resource('stockreport',                            'App\Http\Controllers\Reports\StockReportController');
        Route::resource('customerreport',                     'App\Http\Controllers\Reports\CustomerReportController');
    });
    //  Route::group(['namespace' => 'UserManagement'], function () {
    Route::prefix('UserManagement')->group(function () 
    {
        Route::resource('users',                                    'App\Http\Controllers\UserManagement\UsersController')->only(['index', 'create', 'edit']);
        Route::resource('roles',                                     'App\Http\Controllers\UserManagement\RoleController')->only(['index', 'create']);
        Route::resource('permission',                           'App\Http\Controllers\UserManagement\PermissionController')->only(['index', 'create', 'edit']);
        Route::get('profile/edit',                                    [UsersController::class, 'profile_edit'])->name('edit_profile');
        Route::get('profile',                                            [UsersController::class, 'profile_index'])->name('profile_index');
        Route::get('roles/assign_permission/{id}',         [RoleController::class,'assign_permission']);
        Route::get('roles/edit/{id}',                                [RoleController::class,'edit']);
    });

    Route::prefix('Customers')->group(function () 
    {
        Route::resource('customer',                             'App\Http\Controllers\Customers\CustomerController')->only(['index', 'create', 'edit']);
        Route::resource('customergroup',                   'App\Http\Controllers\Customers\CustomerGroupController')->only(['index', 'create', 'edit']);
    });
       //APPS ROUTES START
    Route::resource('apps',                                         'App\Http\Controllers\Apps\AppsController')->only(['index', 'create', 'edit']);
    Route::get('app-store',                                          [App\Http\Controllers\Apps\AppsController::class,' app_Store']);
    Route::get('app-purchase',                                    [App\Http\Controllers\Apps\AppsController::class,' appPurchase']);

    //PLUGINS ROUTES START
    Route::resource('plugins',                                     'App\Http\Controllers\Plugins\PluginsController')->only(['index', 'create', 'edit']);
    Route::get('plugin-store',                                      [PluginsController::class,' pluginstore']);
    Route::get('plugin-purchase',                                [App\Http\Controllers\Plugins\PluginsController::class,' pluginpurchase']);
    Route::post('plugins/toggle',                                [App\Http\Controllers\Plugins\PluginsController::class,' toggle']);
    Route::get('discount',                                            [App\Http\Controllers\Plugins\PluginsController::class,' discount']);
    Route::get('rating-reviews',                                   [App\Http\Controllers\Plugins\PluginsController::class,' review']);


    Route::get('delivery-time', 'Plugins\DeliveryTimeController@index')->name('index.delivery_time');
    Route::post('delivery-time/add', 'Plugins\DeliveryTimeController@add')->name('add.delivery_time');
    Route::get('loyalty', 'Plugins\LoyaltyController@index')->name('loyalty');
    Route::get('loyalty/edit', 'Plugins\LoyaltyController@edit');
    Route::post('loyalty/update', 'Plugins\LoyaltyController@save')->name('loyalty.update');


    Route::get('inhouse-reciept', 'Plugins\InhouseRecieptController@index')->name('index.inhouse_reciept');
    Route::post('inhouse-reciept/add', 'Plugins\InhouseRecieptController@add')->name('add.inhouse_reciept');

    Route::get('qrcode', 'Plugins\QRCodeController@index')->name('index.qrcode');

    Route::get('salestax', 'Plugins\SalesTaxController@index')->name('index.salestax');
    route::get('salestax/edit', 'Plugins\SalesTaxController@edit')->name('edit.salestax');
    route::post('salestax/save', 'Plugins\SalesTaxController@save')->name('salestax.save');

    // sales Representative
    Route::resource('sales-rep', 'Plugins\SalesRepController');
    Route::post('sales-rep/delete/{id}', 'Plugins\SalesRepController@destroy')->name('sales-rep.destroy');
    //PLUGINS ROUTES END

    Route::resource('outlets',                                     'App\Http\Controllers\Outlets\OutletsController')->only(['index', 'create', 'edit', 'show']);
    Route::get('getRegisterById/{id}',                          [App\Http\Controllers\Outlets\OutletsController::class,' editRegister']);
    Route::post('getRegisterById',                               [App\Http\Controllers\Outlets\OutletsController::class,' updateRegister'])->name('update.register');

    Route::resource('store',                                        'App\Http\Controllers\StoreController')->only(['edit']);
    Route::get('step1',                                                 [App\Http\Controllers\StoreController::class,'step1'])->name('setup.step1');
    Route::get('step2',                                                 [App\Http\Controllers\StoreController::class,'step2'])->name('setup.step2');
    Route::get('step3',                                                 [App\Http\Controllers\StoreController::class,'step3'])->name('setup.step3');

    // Route::get('wizard1', 'Wizard\WizardController@add1');
    // Route::get('wizard2', 'Wizard\WizardController@add2');
    // Route::get('wizard3', 'Wizard\WizardController@add3');

    //	Route::resource('user','UserController')->only('index','edit');
    //outlet business hours routes
    Route::get('business_hours/{id}', 'Outlets\OutletsController@business_hours');
    Route::post('business_hours/{id}/store', 'Outlets\OutletsController@business_hours_store');
    Route::post('add-register', 'Outlets\OutletsController@addregister')->name('add-register');

    //delivery zone routes
    Route::get('delivery_zones/{id}', 'DeliveryZones\DeliveryZonesController@index');
    Route::get('delivery_zones/{id}/add', 'DeliveryZones\DeliveryZonesController@add');
    Route::post('delivery_zones/{id}/store', 'DeliveryZones\DeliveryZonesController@store');
    Route::get('delivery_zones/{outlet_id}/edit/{id}', 'DeliveryZones\DeliveryZonesController@edit');
    Route::post('delivery_zones/{outlet_id}/update/{id}', 'DeliveryZones\DeliveryZonesController@update');
    //	Route::get('delivery_zones/{outlet_id}/delete/{id}', 'DeliveryZones\DeliveryZonesController@delete');
    Route::get('delivery_zones/', 'DeliveryZones\DeliveryZonesController@delete')->name('delivery_zone_delete');
    Route::get('add_areas_of_all_cities', 'DeliveryZones\DeliveryZonesController@add_areas_of_all_cities');
    Route::post('area/import', 'DeliveryZones\DeliveryZonesController@import_data');
    Route::post('apps/toggle', 'apps\AppsController@toggle')->name('app.toggle');

    /* View Loader Routes end here */
});
