<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Reports\ReportsController;
use App\Http\Controllers\Reports\StockReportController;
use App\Http\Controllers\Reports\CustomerReportController;
use App\Http\Controllers\Catalogue\ImportController;
use App\Http\Controllers\Catalogue\CategoryController;
use App\Http\Controllers\Catalogue\BrandsController;
use App\Http\Controllers\Catalogue\SupplierController;
use App\Http\Controllers\Catalogue\StockControlController;
use App\Http\Controllers\Catalogue\ProductController;
use App\Http\Controllers\Catalogue\AddOnController;
use App\Http\Controllers\Catalogue\VariantController;
use App\Http\Controllers\Catalogue\ItemController;
use App\Http\Controllers\Customers\CustomerController;
use App\Http\Controllers\Customers\CustomerGroupController;
use App\Http\Controllers\UserManagement\UsersController;
use App\Http\Controllers\UserManagement\RoleController;
use App\Http\Controllers\UserManagement\PermissionController;
use App\Http\Controllers\Apps\AppsController;
use App\Http\Controllers\Plugins\PluginsController;
use App\Http\Controllers\Plugins\DeliveryTimeController;
use App\Http\Controllers\Plugins\LoyaltyController;
use App\Http\Controllers\Plugins\SalesRepController;
use App\Http\Controllers\Plugins\InhouseRecieptController;
use App\Http\Controllers\Plugins\QRCodeController;
use App\Http\Controllers\Plugins\SalesTaxController;
use App\Http\Controllers\Outlets\OutletsController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\ImageController;
use App\Http\Controllers\DeliveryZones\DeliveryZonesController;

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

Route::get('step1',  [StoreController::class, 'step1'])->name('setup.step1');
Auth::routes();
Route::group(['middleware' => ['auth', 'has_store']], function () {

    Route::get('/',                              [DashboardController::class, 'index']);
    Route::get('/home',                          [DashboardController::class, 'index']);
    Route::get('dashboard',                      [DashboardController::class, 'index'])->name('backoffice.dashboard');
    Route::post('dashboard/kpis',                [DashboardController::class, 'getKpiData'])->name('backoffice.dashboard.kpis');
    Route::post('orders-list',                   [DashboardController::class, 'getOrders'])->name('backoffice.order.list');
    Route::post('image/upload',                  [ImageController::class, 'upload'])->name('import.product.images');
    /* View Loader Routes */
    Route::prefix('catalogue')->group(function () {
//        Route::group(['namespace' => 'Catalogue'], function () {
//            Route::namespace('Catalogue')->group(function() {
//            Route::name('Catalogue.')->group(function () {
        Route::resource('category',     CategoryController::class)->only(['index', 'create', 'edit']);
        Route::resource('brands',       BrandsController::class)->only(['index', 'create', 'edit']);
        Route::resource('supplier',     SupplierController::class)->only(['index', 'create', 'edit']);
        Route::resource('stockcontrol', StockControlController::class);
        Route::resource('product',      ProductController::class)->only(['index', 'create', 'edit']);
        Route::resource('addon',        AddOnController::class)->only(['index', 'create', 'edit']);
        Route::resource('variant',      VariantController::class)->only(['edit']);
        Route::resource('item',         ItemController::class);
        Route::get('delete-item',                 [ItemController::class, 'deleteItem'])->name('delete.item');
        Route::post('bulk-item',                  [ItemController::class, 'bulkItem'])->name('toggle.item.all');
        Route::get('addons/addItems',             [AddOnController::class, 'itemCreate'])->name('addon.item_create');
        Route::get('import/products',             [ImportController::class, 'index']);
        Route::post('import/products',            [ImportController::class, 'import'])->name('import.products');
        Route::post('import/translations',        [ImportController::class, 'importTranslations'])->name('import.Translations');
        Route::post('product/import/images',      [ImportController::class, 'images'])->name('product.import.images');
        Route::get('export/products',             [ImportController::class, 'export'])->name('export.products');
        Route::post('product/import/Sample',      [ImportController::class, 'ImportSampleCatelouge'])->name('product.import_sample_catelouge');
        Route::get('import/addons',               [ImportController::class, 'index1'])->name('import.addon.index');
        Route::post('import/addons',              [ImportController::class, 'importAddons'])->name('import.addons');
        Route::get('export/addons',               [ImportController::class, 'exportAddons'] )->name('export.addons');
    });
    Route::prefix('Reports')->group(function ()
    {
        Route::resource('reports',         ReportsController::class);
        Route::resource('stockreport',     StockReportController::class);
        Route::resource('customerreport',  CustomerReportController::class);
    });
    Route::prefix('usermanagement')->group(function ()
    {
        Route::resource('users',           UsersController::class)->only(['index', 'create', 'edit']);
        Route::resource('roles',           RoleController::class)->only(['index', 'create']);
        Route::resource('permission',      PermissionController::class)->only(['index', 'create', 'edit']);
        Route::get('profile/edit',                   [UsersController::class, 'profile_edit'])->name('edit_profile');
        Route::get('profile',                        [UsersController::class, 'profile_index'])->name('profile_index');
        Route::get('roles/assign_permission/{id}',   [RoleController::class,'assign_permission']);
        Route::get('roles/edit/{id}',                [RoleController::class,'edit']);
    });

    Route::prefix('customers')->group(function ()
    {
        Route::resource('customer',       CustomerController::class)->only(['index', 'create', 'edit']);
        Route::resource('customergroup',  CustomerGroupController::class)->only(['index', 'create', 'edit']);
    });
       //APPS ROUTES START
    Route::resource('apps',               AppsController::class)->only(['index', 'create', 'edit']);
    Route::get('app-store',                         [AppsController::class,'app_Store'])->name('app-store');
    Route::get('app-purchase',                      [AppsController::class,'appPurchase']);

    //PLUGINS ROUTES START
    Route::resource('plugins',           PluginsController::class)->only(['index', 'create', 'edit']);
    Route::get('plugin-store',                      [PluginsController::class,'pluginstore']);
    Route::get('plugin-purchase',                   [PluginsController::class,'pluginpurchase']);
    Route::post('plugins/toggle',                   [PluginsController::class,'toggle']);
    Route::get('discount',                          [PluginsController::class,'discount']);
    Route::get('rating-reviews',                    [PluginsController::class,'review']);

    Route::get('delivery-time',                     [DeliveryTimeController::class,'index'])->name('index.delivery_time');
    Route::post('delivery-time/add',                [DeliveryTimeController::class,'add'])->name('add.delivery_time');
    Route::get('loyalty',                           [LoyaltyController::class,'index'])->name('loyalty');
    Route::get('loyalty/edit',                      [LoyaltyController::class,'edit']);
    Route::post('loyalty/update',                   [LoyaltyController::class,'save'])->name('loyalty.update');


    Route::get('inhouse-reciept',                   [InhouseRecieptController::class,'index'])->name('index.inhouse_reciept');
    Route::post('inhouse-reciept/add',              [InhouseRecieptController::class,'add'])->name('add.inhouse_reciept');

    Route::get('qrcode',                            [QRCodeController::class,'index'])->name('index.qrcode');

    Route::get('salestax',                          [SalesTaxController::class,'index'])->name('index.salestax');
    route::get('salestax/edit',                     [SalesTaxController::class,'edit'])->name('edit.salestax');
    route::post('salestax/save',                    [SalesTaxController::class,'save'])->name('salestax.save');

    // sales Representative
    Route::resource('sales-rep',         SalesRepController::class);
    Route::post('sales-rep/delete/{id}',            [SalesRepController::class,'destroy'])->name('sales-rep.destroy');
    //PLUGINS ROUTES END

    Route::resource('outlets',           OutletsController::class)->only(['index', 'create', 'edit', 'show']);
    Route::get('getRegisterById/{id}',              [OutletsController::class,' editRegister']);
    Route::post('getRegisterById',                  [OutletsController::class,' updateRegister'])->name('update.register');

    Route::resource('store',             StoreController::class)->only(['edit']);
    Route::get('step1',                             [StoreController::class,'step1'])->name('setup.step1');
    Route::get('step2',                             [StoreController::class,'step2'])->name('setup.step2');
    Route::get('step3',                             [StoreController::class,'step3'])->name('setup.step3');

    // Route::get('wizard1', 'Wizard\WizardController@add1');
    // Route::get('wizard2', 'Wizard\WizardController@add2');
    // Route::get('wizard3', 'Wizard\WizardController@add3');

    //	Route::resource('user','UserController')->only('index','edit');
    //outlet business hours routes
    Route::get('business_hours/{id}',                     [OutletsController::class,'business_hours']);
    Route::post('business_hours/{id}/store',              [OutletsController::class,'business_hours_store']);
    Route::post('add-register',                           [OutletsController::class,'addregister'])->name('add-register');

    //delivery zone routes
    Route::get('delivery_zones/{id}',                     [DeliveryZonesController::class,'index']);
    Route::get('delivery_zones/{id}/add',                 [DeliveryZonesController::class,'add']);
    Route::post('delivery_zones/{id}/store',              [DeliveryZonesController::class,'store']);
    Route::get('delivery_zones/{outlet_id}/edit/{id}',    [DeliveryZonesController::class,'edit']);
    Route::post('delivery_zones/{outlet_id}/update/{id}', [DeliveryZonesController::class,'update']);
    //	Route::get('delivery_zones/{outlet_id}/delete/{id}', 'DeliveryZones\DeliveryZonesController@delete');
    Route::get('delivery_zones/',                         [DeliveryZonesController::class,'delete'])->name('delivery_zone_delete');
    Route::get('add_areas_of_all_cities',                 [DeliveryZonesController::class,'add_areas_of_all_cities']);
    Route::post('area/import',                            [DeliveryZonesController::class,'import_data']);
    Route::post('apps/toggle',                            [AppsController::class,'toggle'])->name('app.toggle');

    /* View Loader Routes end here */
});
