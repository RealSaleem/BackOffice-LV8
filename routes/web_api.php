<?php

 use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Outlets\OutletsController;
use App\Http\Controllers\Api\UserManagement\UsersController;
use App\Http\Controllers\Api\UserManagement\RoleController;
use App\Http\Controllers\Api\UserManagement\PermissionController;
use App\Http\Controllers\Api\Catalogue\CategoryController;
use App\Http\Controllers\Api\Catalogue\BrandsController;
use App\Http\Controllers\Api\Catalogue\ProductController;
use App\Http\Controllers\Api\Catalogue\SupplierController;
use App\Http\Controllers\Api\Catalogue\AddOnController;
use App\Http\Controllers\Api\Catalogue\VariantController;
use App\Http\Controllers\Api\Catalogue\StockControlController;
use App\Http\Controllers\Reports\ReportsController;
use App\Http\Controllers\Api\ImageController;
use App\Http\Controllers\Api\Customers\CustomerController;
use App\Http\Controllers\Api\Customers\CustomerGroupController;
use App\Http\Controllers\Api\StoreController;


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

Route::group(['middleware' => ['auth', 'has_store']], function () {
    /* Api routes for view loaders */
    Route::group(['prefix' => 'api', 'namespace' => 'Api'], function () {
        Route::group(['namespace' => 'Catalogue'], function () {
// ---------------------------Category Api Routes
            Route::post('get-categories',				[CategoryController::class, 'getCategories'])->name('api.fetch.categories');
            Route::post('toggle-category', 				[CategoryController::class, 'toggleCategory'])->name('api.toggle.category');
            Route::get('delete-category', 				[CategoryController::class, 'deleteCategory'])->name('api.delete.category');
            Route::post('bulk-category',				[CategoryController::class, 'bulkCategory'])->name('api.toggle.category.all');
            Route::post('add-category', 				[CategoryController::class, 'addCategory'])->name('api.add.category');
            Route::post('update-category', 				[CategoryController::class, 'updateCategory'])->name('api.update.category');
// ---------------------------Product Api Routes
            Route::post('get-products',   		 		[ProductController::class, 'getProducts'])->name('api.fetch.products');
            Route::post('toggle-product', 		   		[ProductController::class, 'toggleProduct'])->name('api.toggle.product');
            Route::get('delete-product', 		 		[ProductController::class, 'deleteProduct'])->name('api.delete.product');
            Route::post('bulk-product',	 		 		[ProductController::class, 'bulkProduct'])->name('api.toggle.product.all');
            Route::post('add-product', 			 		[ProductController::class, 'addProduct'])->name('api.add.product');
            Route::post('update-product', 		  		[ProductController::class, 'updateProduct'])->name('api.update.product');
            Route::post('product/search', 		   		[ProductController::class, 'search'])->name('api.search.product');
// ---------------------------Brand Api Routes
            Route::post('get-brands', 				    [BrandsController::class, 'getBrands'])->name('api.fetch.brands');
            Route::post('toggle-brands',		        [BrandsController::class, 'toggleBrands'])->name('api.toggle.brands');
            Route::get('delete-brand',				    [BrandsController::class,'deleteBrands'])->name('api.delete.brands');
            Route::post('bulk-brands',  			    [BrandsController::class,'bulkBrands'])->name('api.toggle.brands.all');
            Route::post('add-brands',				    [BrandsController::class,'addBrands'])->name('api.add.brand');
            Route::post('update-brands', 		        [BrandsController::class,'updateBrands'])->name('api.update.brand');
// ---------------------------Supplier Api Routes
            Route::post('get-suppliers', 		        [SupplierController::class, 'getSuppliers'] )->name('api.fetch.suppliers');
            Route::get('delete-supplier',		        [SupplierController::class, 'deleteSupplier'] )->name('api.delete.supplier');
            Route::post('toggle-supplier',		        [SupplierController::class, 'toggleSupplier'] )->name('api.toggle.supplier');
            Route::post('bulk-supplier',		        [SupplierController::class, 'bulkSupplier'] )->name('api.toggle.supplier.all');
            Route::post('add-supplier',			        [SupplierController::class, 'addSupplier'] )->name('api.add.supplier');
            Route::post('update-supplier',	            [SupplierController::class, 'updateSupplier'] )->name('api.update.supplier');
// ---------------------------Addon Api Routes
            Route::post('get-addons',                   [AddOnController::class, 'getAddon'])->name('api.fetch.addons');
            Route::get('delete-addon',                  [AddOnController::class, 'deleteAddon'])->name('api.delete.addon');
            Route::post('toggle-addon',                 [AddOnController::class, 'deleteAddon'])->name('api.toggle.addon');
            Route::post('bulk-addon',                   [AddOnController::class, 'bulkAddon'])->name('api.toggle.addon.all');
            Route::post('add-addon',                    [AddOnController::class, 'addAddon'] )->name('api.add.addon');
            Route::post('update-addon',                 [AddOnController::class, 'updateAddon'] )->name('api.update.addon');
            Route::post('update-variant',               [VariantController::class, 'updateVariant'] )->name('api.update.variant');
            Route::post('get-stockcontrol',             [StockControlController::class, 'getstockcontrol'] )->name('api.fetch.stockcontrol');
        });
// ---------------------------Image Api Routes
            Route::post('store-image',                  [ImageController::class,'uploadStoreImage'])->name('api.upload.store.image');
            Route::post('brand-image',                  [ImageController::class,'uploadBrandImage'])->name('api.upload.brand.image');
            Route::post('category-image',               [ImageController::class,'uploadCategoryImage'])->name('api.upload.category.image');
            Route::post('product-image',                [ImageController::class,'uploadProductImage'])->name('api.upload.product.image');
            Route::post('user-image',                   [ImageController::class,'uploadUserImage'])->name('api.upload.user.image');
            Route::post('outlet-image',                 [ImageController::class,'uploadOutletImage'])->name('api.upload.outlet.image');

//-------------------------- Customers Route------------------->
        Route::group(['namespace' => 'Customers'], function () {
            Route::post('get-customer',                 [CustomerController::class,'getCustomers'])->name('api.fetch.customers');
            Route::post('get-customergroup',            [CustomerGroupController::class,'getCustomergroup'])->name('api.fetch.customersgroup');
            Route::get('delete-customergroup',          [CustomerGroupController::class,'deleteCustomerGroup'])->name('api.delete.customergroup');
            Route::post('bulk-customergroup',           [CustomerGroupController::class,'bulkCustomerGroup'])->name('api.delete.customergroup.all');
            Route::post('add-customer',                 [CustomerController::class,'addCustomer'])->name('api.add.customer');
            Route::post('update-customer',              [CustomerController::class,'updateCustomer'])->name('api.update.customer');
            Route::post('add-customergroup',            [CustomerGroupController::class,'addCustomerGroup'])->name('api.add.customergroup');
            Route::post('update-customergroup',         [CustomerGroupController::class,'updateCustomerGroup'])->name('api.update.customergroup');
        });
//-------------------------- User Route------------------->
        Route::group(['namespace' => 'UserManagement'], function () {
            Route::post('add-user',                	    [UsersController::class,'adduser'])->name('api.add.user');
            Route::post('get-user',                     [UsersController::class,'getuser'])->name('api.fetch.users');
            Route::get('delete-user',           	    [UsersController::class,'deleteuser'])->name('api.delete.user');
            Route::post('toggle-user', 				    [UsersController::class,'toggleuser'])->name('api.toggle.user');
            Route::post('bulk-user',				    [UsersController::class,'bulkuser'])->name('api.toggle.user.all');
            Route::post('update-user',					[UsersController::class,'updateuser'])->name('api.update.user');
//--------------------------Login User Profile Update Route------------------->
            Route::post('user', 						[UsersController::class,'save'])->name('api.user.save');
//--------------------------User Role Route------------------->
            Route::post('get-roles',                    [RoleController::class,'getRoles'])->name('api.fetch.roles');
            Route::post('add-roles',                    [RoleController::class,'store'])->name('api.add.roles');
            Route::post('update-roles',                 [RoleController::class,'updateRole'])->name('api.update.roles');
            Route::get('delete-roles',                  [RoleController::class,'deleteRole'])->name('api.delete.roles');
            Route::post('roles/assign-permission',      [RoleController::class,'assignPermission'])->name('api.roles.assign_permission');
            Route::post('roles/get-permissions',        [RoleController::class,'getAllPermissions'])->name('api.fetch.role_permissions');
//--------------------------User Permissions Route------------------->
            Route::post('get-permission',               [PermissionController::class,'getPermission'])->name('api.fetch.permission');
            Route::post('add-permission',               [PermissionController::class,'addPermission'])->name('api.add.permission');
            Route::post('update-permission',            [PermissionController::class,'updatePermission'])->name('api.update.permission');
            Route::get('delete-permission',             [PermissionController::class,'deletePermission'])->name('api.delete.permission');
        });

            Route::post('get-plugins', 'Plugins\PluginsController@getplugins')->name('api.fetch.plugins');
            Route::post('update-plugins-setting', 'Plugins\PluginsController@UpdatePluginSetting')->name('api.update.plugins-settings');
            Route::post('plugins/toggle', 'PluginController@toggle')->name('plugins.toggle');

//--------------------------Outlets Route------------------->
            Route::post('get-outlets',				    [OutletsController::class,'getOutlets'])->name('api.fetch.outlets');
            Route::post('add-outlets', 				    [OutletsController::class,'addOutlets']	)->name('api.add.outlets');
            Route::post('update-outlets',               [OutletsController::class,'updateOutlets'] )->name('api.update.outlets');
//--------------------------Store Route------------------->
            Route::post('store-save',                   [StoreController::class,'update'])->name('api.store.save');
            Route::post('step1',						[StoreController::class,'step1'])->name('setup.step1.save');
            Route::post('step2', 						[StoreController::class,'step2'])->name('setup.step2.save');
            Route::post('step3',						[StoreController::class,'step3'])->name('setup.step3.save');
//--------------------------Reports Route------------------->
        Route::group(['namespace' => 'Reports'], function () {
            Route::get('salesreport',                   [ReportsController::class, 'index'])->name('api.fetch.salesreport');
        });
    });
});
