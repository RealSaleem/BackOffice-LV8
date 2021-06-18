<?php

// use Illuminate\Support\Facades\Route;

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

// Route::group(['middleware' => ['auth','has_store']], function () {
	/* Api routes for view loaders */
	// Route::group(['prefix' => 'api' ,'namespace' => 'Api'], function () {
		// Route::group(['namespace' => 'Catalogue'], function () {
			// Route::post('get-categories', 'CategoryController@getCategories')->name('api.fetch.categories');
			// Route::post('toggle-category', 'CategoryController@toggleCategory')->name('api.toggle.category');
			// Route::get('delete-category', 'CategoryController@deleteCategory')->name('api.delete.category');
			// Route::post('bulk-category', 'CategoryController@bulkCategory')->name('api.toggle.category.all');
			// Route::post('add-category', 'CategoryController@addCategory')->name('api.add.category');
			// Route::post('update-category', 'CategoryController@updateCategory')->name('api.update.category');

			// Route::post('get-products', 'ProductController@getProducts')->name('api.fetch.products');
			// Route::post('toggle-product', 'ProductController@toggleProduct')->name('api.toggle.product');
			// Route::get('delete-product', 'ProductController@deleteProduct')->name('api.delete.product');
			// Route::post('bulk-product', 'ProductController@bulkProduct')->name('api.toggle.product.all');
			// Route::post('add-product', 'ProductController@addProduct')->name('api.add.product');
			// Route::post('update-product', 'ProductController@updateProduct')->name('api.update.product');
			// Route::post('product/search','ProductController@search')->name('api.search.product');

			// Route::post('get-brands', 'BrandsController@getBrands')->name('api.fetch.brands');
			// Route::post('toggle-brands', 'BrandsController@toggleBrands')->name('api.toggle.brands');
			// Route::get('delete-brand', 'BrandsController@deleteBrands')->name('api.delete.brands');
			// Route::post('bulk-brands', 'BrandsController@bulkBrands')->name('api.toggle.brands.all');
			// Route::post('add-brands', 'BrandsController@addBrands')->name('api.add.brand');
			// Route::post('update-brands', 'BrandsController@updateBrands')->name('api.update.brand');

			// Route::post('get-suppliers', 'SupplierController@getSuppliers')->name('api.fetch.suppliers');
			// Route::get('delete-supplier', 'SupplierController@deleteSupplier')->name('api.delete.supplier');
			// Route::post('toggle-supplier', 'SupplierController@toggleSupplier')->name('api.toggle.supplier');
			// Route::post('bulk-supplier', 'SupplierController@bulkSupplier')->name('api.toggle.supplier.all');
			// Route::post('add-supplier', 'SupplierController@addSupplier')->name('api.add.supplier');
			// Route::post('update-supplier', 'SupplierController@updateSupplier')->name('api.update.supplier');

			// Route::post('get-addons', 'AddonController@getAddon')->name('api.fetch.addons');
			// Route::get('delete-addon', 'AddonController@deleteAddon')->name('api.delete.addon');
			// Route::post('toggle-addon', 'AddonController@toggleAddon')->name('api.toggle.addon');
			// Route::post('bulk-addon', 'AddonController@bulkAddon')->name('api.toggle.addon.all');
			// Route::post('add-addon', 'AddonController@addAddon')->name('api.add.addon');
			// Route::post('update-addon', 'AddonController@updateAddon')->name('api.update.addon');
			// Route::post('update-variant', 'VariantController@updateVariant')->name('api.update.variant');
			// Route::post('get-stockcontrol', 'StockControlController@getstockcontrol')->name('api.fetch.stockcontrol');
		// });

		// Route::post('store-image', 'ImageController@uploadStoreImage')->name('api.upload.store.image');
		// Route::post('brand-image', 'ImageController@uploadBrandImage')->name('api.upload.brand.image');
		// Route::post('category-image', 'ImageController@uploadCategoryImage')->name('api.upload.category.image');
		// Route::post('product-image', 'ImageController@uploadProductImage')->name('api.upload.product.image');
		// Route::post('user-image', 'ImageController@uploadUserImage')->name('api.upload.user.image');
        // Route::post('outlet-image', 'ImageController@uploadOutletImage')->name('api.upload.outlet.image');

		// Route::group(['namespace' => 'Customers'], function () {
			// Route::post('get-customer', 'CustomerController@getCustomers')->name('api.fetch.customers');
			// Route::post('get-customergroup', 'CustomerGroupController@getCustomergroup')->name('api.fetch.customersgroup');
			// Route::get('delete-customergroup', 'CustomerGroupController@deleteCustomerGroup')->name('api.delete.customergroup');
			// Route::post('bulk-customergroup', 'CustomerGroupController@bulkCustomerGroup')->name('api.delete.customergroup.all');
			// Route::post('add-customer', 'CustomerController@addCustomer')->name('api.add.customer');
			// Route::post('update-customer', 'CustomerController@updateCustomer')->name('api.update.customer');
			// Route::post('add-customergroup', 'CustomerGroupController@addCustomerGroup')->name('api.add.customergroup');
			// Route::post('update-customergroup', 'CustomerGroupController@updateCustomerGroup')->name('api.update.customergroup');
		// });

		// Route::group(['namespace' => 'UserManagement'], function () {
            // Route::post('add-user', 'UsersController@adduser')->name('api.add.user');
            // Route::post('get-user', 'UsersController@getuser')->name('api.fetch.users');
            // Route::get('delete-user', 'UsersController@deleteuser')->name('api.delete.user');
            // Route::post('toggle-user', 'UsersController@toggleuser')->name('api.toggle.user');
			// Route::post('bulk-user', 'UsersController@bulkuser')->name('api.toggle.user.all');
            // Route::post('update-user', 'UsersController@updateuser')->name('api.update.user');

//--------------------------Login User Profile Update Route------------------->
            // Route::post('user','UsersController@save')->name('api.user.save');

	       	// Route::post('get-roles', 'RoleController@getRoles')->name('api.fetch.roles');
            // Route::post('add-roles', 'RoleController@store')->name('api.add.roles');

			// Route::post('update-roles', 'RoleController@updateRole')->name('api.update.roles');
			// Route::get('delete-roles', 'RoleController@deleteRole')->name('api.delete.roles');
			// Route::post('roles/assign-permission', 'RoleController@assignPermission')->name('api.roles.assign_permission');
			// Route::post('roles/get-permissions', 'api.fetch.pluginsRoleController@getAllPermissions')->name('api.fetch.role_permissions');

			// Route::post('get-permission', 'PermissionController@getPermission')->name('api.fetch.permission');
			// Route::post('add-permission', 'PermissionController@addPermission')->name('api.add.permission');
			// Route::post('update-permission', 'PermissionController@updatePermission')->name('api.update.permission');
			// Route::get('delete-permission', 'permissionController@deletePermission')->name('api.delete.permission');


		// });

// 		Route::post('get-plugins', 'Plugins\PluginsController@getplugins')->name('api.fetch.plugins');
// 		Route::post('update-plugins-setting', 'Plugins\PluginsController@UpdatePluginSetting')->name('api.update.plugins-settings');
//         Route::post('plugins/toggle', 'PluginController@toggle')->name('plugins.toggle');

// 		Route::post('store-save','StoreController@update')->name('api.store.save');

// 		Route::post('get-outlets', 'Outlets\OutletsController@getOutlets')->name('api.fetch.outlets');
// 		Route::post('add-outlets', 'Outlets\OutletsController@addOutlets')->name('api.add.outlets');
// 		Route::post('update-outlets','Outlets\OutletsController@updateOutlets')->name('api.update.outlets');



// 		Route::post('step1', 'StoreController@step1')->name('setup.step1.save');
// 		Route::post('step2', 'StoreController@step2')->name('setup.step2.save');
// 		Route::post('step3', 'StoreController@step3')->name('setup.step3.save');

// 		Route::group(['namespace' => 'Reports'], function () {
// 			Route::get('salesreport', [App\Http\Controllers\Reports\ReportsController::class,'index'])->name('api.fetch.salesreport');
// 		});

// 	});
// });
