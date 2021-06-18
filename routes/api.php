<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Outlets\OutletsController;
use App\Http\Controllers\Api\UserManagement\UsersController;
use App\Http\Controllers\Catalogue\CategoryController;
use App\Http\Controllers\Catalogue\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
	return $request->user();
});

Route::group(['middleware' => ['auth', 'has_store']], function () {
	/* Api routes for view loaders */
	Route::group(['prefix' => 'api', 'namespace' => 'Api'], function () {
		Route::group(['namespace' => 'Catalogue'], function () {
			Route::post('get-categories',							 [CategoryController::class, 'getCategories'])->name('api.fetch.categories');
			Route::post('toggle-category', 							[CategoryController::class, 'toggleCategory'])->name('api.toggle.category');
			Route::get('delete-category', 							 [CategoryController::class, 'deleteCategory'])->name('api.delete.category');
			Route::post('bulk-category',							 [CategoryController::class, 'bulkCategory'])->name('api.toggle.category.all');
			Route::post('add-category', 							 [CategoryController::class, 'addCategory'])->name('api.add.category');
			Route::post('update-category', 						   [CategoryController::class, 'updateCategory'])->name('api.update.category');
// ---------------------------Product Api Routes
			Route::post('get-products',   		 					  [ProductController::class, 'getProducts'])->name('api.fetch.products');
			Route::post('toggle-product', 		   					 [ProductController::class, 'toggleProduct'])->name('api.toggle.product');
			Route::get('delete-product', 		 					  [ProductController::class, 'deleteProduct'])->name('api.delete.product');
			Route::post('bulk-product',	 		 					  [ProductController::class, 'bulkProduct'])->name('api.toggle.product.all');
			Route::post('add-product', 			 					  [ProductController::class, 'addProduct'])->name('api.add.product');
			Route::post('update-product', 		  					[ProductController::class, 'updateProduct'])->name('api.update.product');
			Route::post('product/search', 		   				     [ProductController::class, 'search'])->name('api.search.product');

			Route::post('get-brands', 				[App\Http\Controllers\Catalogue\BrandsController::class, 'getBrands'])->name('api.fetch.brands');
            Route::post('toggle-brands',		 [App\Http\Controllers\Catalogue\BrandsController::class, 'toggleBrands'])->name('api.toggle.brands');
            Route::get('delete-brand',				[App\Http\Controllers\Catalogue\BrandsController::class,'deleteBrands'])->name('api.delete.brands');
            Route::post('bulk-brands',  			[App\Http\Controllers\Catalogue\BrandsController::class,'bulkBrands'])->name('api.toggle.brands.all');
            Route::post('add-brands',				[App\Http\Controllers\Catalogue\BrandsController::class,'addBrands'])->name('api.add.brand');
            Route::post('update-brands', 		[App\Http\Controllers\Catalogue\BrandsController::class,'updateBrands'])->name('api.update.brand');

			Route::post('get-suppliers', 		  [App\Http\Controllers\Catalogue\SupplierController::class, 'getSuppliers'] )->name('api.fetch.suppliers');
			Route::get('delete-supplier',		 [App\Http\Controllers\Catalogue\SupplierController::class, 'deleteSupplier'] )->name('api.delete.supplier');
			Route::post('toggle-supplier',		[App\Http\Controllers\Catalogue\SupplierController::class, 'toggleSupplier'] )->name('api.toggle.supplier');
			Route::post('bulk-supplier',		 [App\Http\Controllers\Catalogue\SupplierController::class, 'bulkSupplier'] )->name('api.toggle.supplier.all');
			Route::post('add-supplier',			 [App\Http\Controllers\Catalogue\SupplierController::class, 'addSupplier'] )->name('api.add.supplier');
			Route::post('update-supplier',	   [App\Http\Controllers\Catalogue\SupplierController::class, 'updateSupplier'] )->name('api.update.supplier');

			Route::post('get-addons',[App\Http\Controllers\Catalogue\AddonController::class, 'getAddon'])->name('api.fetch.addons');
			Route::get('delete-addon',[App\Http\Controllers\Catalogue\AddonController::class, 'deleteAddon'])->name('api.delete.addon');
			Route::post('toggle-addon',[App\Http\Controllers\Catalogue\AddonController::class, 'deleteAddon'])->name('api.toggle.addon');
			Route::post('bulk-addon',[App\Http\Controllers\Catalogue\AddonController::class, 'bulkAddon'])->name('api.toggle.addon.all');
			Route::post('add-addon',[App\Http\Controllers\Catalogue\AddonController::class, 'addAddon'] )->name('api.add.addon');
			Route::post('update-addon', [App\Http\Controllers\Catalogue\AddonController::class, 'updateAddon'] )->name('api.update.addon');
			Route::post('update-variant', [App\Http\Controllers\Catalogue\VariantController::class, 'updateVariant'] )->name('api.update.variant');
			Route::post('get-stockcontrol',[App\Http\Controllers\Catalogue\StockControlController::class, 'getstockcontrol'] )->name('api.fetch.stockcontrol');
		});

		Route::post('store-image', [App\Http\Controllers\Catalogue\ImageController::class,'uploadStoreImage'])->name('api.upload.store.image');
        Route::post('brand-image', [App\Http\Controllers\Catalogue\ImageController::class,'uploadBrandImage'])->name('api.upload.brand.image');
        Route::post('category-image', [App\Http\Controllers\Catalogue\ImageController::class,'uploadCategoryImage'])->name('api.upload.category.image');
        Route::post('product-image', [App\Http\Controllers\Catalogue\ImageController::class,'uploadProductImage'])->name('api.upload.product.image');
        Route::post('user-image',  [App\Http\Controllers\Catalogue\ImageController::class,'uploadUserImage'])->name('api.upload.user.image');
        Route::post('outlet-image', [App\Http\Controllers\Catalogue\ImageController::class,'uploadOutletImage'])->name('api.upload.outlet.image');

		Route::group(['namespace' => 'Customers'], function () {
			// Route::post('get-customer', 'CustomerController@getCustomers')->name('api.fetch.customers');
			// Route::post('get-customergroup', 'CustomerGroupController@getCustomergroup')->name('api.fetch.customersgroup');
			// Route::get('delete-customergroup', 'CustomerGroupController@deleteCustomerGroup')->name('api.delete.customergroup');
			// Route::post('bulk-customergroup', 'CustomerGroupController@bulkCustomerGroup')->name('api.delete.customergroup.all');
			// Route::post('add-customer', 'CustomerController@addCustomer')->name('api.add.customer');
			// Route::post('update-customer', 'CustomerController@updateCustomer')->name('api.update.customer');
			// Route::post('add-customergroup', 'CustomerGroupController@addCustomerGroup')->name('api.add.customergroup');
			// Route::post('update-customergroup', 'CustomerGroupController@updateCustomerGroup')->name('api.update.customergroup');
			Route::post('get-customer', [App\Http\Controllers\Customers\CustomerController::class,'getCustomers'])->name('api.fetch.customers');
            Route::post('get-customergroup', [App\Http\Controllers\Customers\CustomerGroupController::class,'getCustomergroup'])->name('api.fetch.customersgroup');
            Route::get('delete-customergroup', [App\Http\Controllers\Customers\CustomerGroupController::class,'deleteCustomerGroup'])->name('api.delete.customergroup');
            Route::post('bulk-customergroup', [App\Http\Controllers\Customers\CustomerGroupController::class,'bulkCustomerGroup'])->name('api.delete.customergroup.all');
            Route::post('add-customer', [App\Http\Controllers\Customers\CustomerController::class,'addCustomer'])->name('api.add.customer');
            Route::post('update-customer', [App\Http\Controllers\Customers\CustomerController::class,'updateCustomer'])->name('api.update.customer');
            Route::post('add-customergroup', [App\Http\Controllers\Customers\CustomerGroupController::class,'addCustomerGroup'])->name('api.add.customergroup');
            Route::post('update-customergroup', [App\Http\Controllers\Customers\CustomerGroupController::class,'updateCustomerGroup'])->name('api.update.customergroup');



		});

		Route::group(['namespace' => 'UserManagement'], function () {
			Route::post('add-user',                				          [UsersController::class,'adduser'])->name('api.add.user');
			Route::post('get-user',                     			       [UsersController::class,'getuser'])->name('api.fetch.users');
			Route::get('delete-user',           				          [UsersController::class,'deleteuser'])->name('api.delete.user');
			Route::post('toggle-user', 							         [UsersController::class,'toggleuser'])->name('api.toggle.user');
			Route::post('bulk-user',						               [UsersController::class,'bulkuser'])->name('api.toggle.user.all');
			Route::post('update-user',							         [UsersController::class,'updateuser'])->name('api.update.user');
			//--------------------------Login User Profile Update Route-------------------> 
			Route::post('user', 							                   [UsersController::class,'save'])->name('api.user.save');

			Route::post('get-roles', 'RoleController@getRoles')->name('api.fetch.roles');
			Route::post('add-roles', 'RoleController@store')->name('api.add.roles');

			Route::post('update-roles', 'RoleController@updateRole')->name('api.update.roles');
			Route::get('delete-roles', 'RoleController@deleteRole')->name('api.delete.roles');
			Route::post('roles/assign-permission', 'RoleController@assignPermission')->name('api.roles.assign_permission');
			Route::post('roles/get-permissions', 'api.fetch.pluginsRoleController@getAllPermissions')->name('api.fetch.role_permissions');

			Route::post('get-permission', 'PermissionController@getPermission')->name('api.fetch.permission');
			Route::post('add-permission', 'PermissionController@addPermission')->name('api.add.permission');
			Route::post('update-permission', 'PermissionController@updatePermission')->name('api.update.permission');
			Route::get('delete-permission', 'permissionController@deletePermission')->name('api.delete.permission');
		});

		Route::post('get-plugins', 'Plugins\PluginsController@getplugins')->name('api.fetch.plugins');
		Route::post('update-plugins-setting', 'Plugins\PluginsController@UpdatePluginSetting')->name('api.update.plugins-settings');
		Route::post('plugins/toggle', 'PluginController@toggle')->name('plugins.toggle');

		
		Route::post('get-outlets',									   [OutletsController::class,'getOutlets'])->name('api.fetch.outlets');
		Route::post('add-outlets', 									  [OutletsController::class,'addOutlets']	)->name('api.add.outlets');
		Route::post('update-outlets',                           	[OutletsController::class,'updateOutlets'] )->name('api.update.outlets');

		Route::post('store-save',                                     [App\Http\Controllers\Api\StoreController::class,'update'])->name('api.store.save');
		Route::post('step1',											 [App\Http\Controllers\Api\StoreController::class,'step1'])->name('setup.step1.save');
		Route::post('step2', 											 [App\Http\Controllers\Api\StoreController::class,'step2'])->name('setup.step2.save');
		Route::post('step3',											 [App\Http\Controllers\Api\StoreController::class,'step3'])->name('setup.step3.save');

		Route::group(['namespace' => 'Reports'], function () {
			Route::get('salesreport', [App\Http\Controllers\Reports\ReportsController::class, 'index'])->name('api.fetch.salesreport');
		});
	});
});
