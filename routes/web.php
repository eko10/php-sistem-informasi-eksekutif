<?php

// auth
Route::group(['middleware' => 'guest'], function () {
	// Route::get('/', function () {
	// 	return view('auth.login');
	// })->name('login');
	Route::get('/', 'AuthController@login')->name('login');
	Route::post('prosesLogin', 'AuthController@prosesLogin');
});

Route::get('/logout', 'AuthController@logout');

// home
Route::group(['middleware' => ['auth','checkRole:admin']], function(){
    // user
	Route::resource('user', 'UserController');
	Route::post('user/update', 'UserController@update')->name('user.update');
	Route::post('editUser', 'UserController@editUser');
	// product
	Route::resource('category', 'CategoryController');
	Route::post('category/update', 'CategoryController@update')->name('category.update');
	Route::get('categorySearch', 'CategoryController@search');
	// product
	Route::resource('product', 'ProductController');
	Route::post('product/update', 'ProductController@update')->name('product.update');
	Route::get('productSearch', 'ProductController@search');
	Route::get('productSearchByCode', 'ProductController@searchByCode');
	Route::post('getProductById', 'ProductController@getProductById');
	// supplier
	Route::resource('supplier', 'SupplierController');
	Route::post('supplier/update', 'SupplierController@update')->name('supplier.update');
	Route::get('supplierSearch', 'SupplierController@search');
	// faculty
	Route::resource('faculty', 'FacultyController');
	Route::post('faculty/update', 'FacultyController@update')->name('faculty.update');
	Route::get('facultySearch', 'FacultyController@search');
	// major
	Route::resource('major', 'MajorController');
	Route::post('major/update', 'MajorController@update')->name('major.update');
	//Route::get('major/search', 'MajorController@search')->name('major.search');
	Route::get('majorSearch/{id}', 'MajorController@search');
	// purchasing
	Route::resource('purchasing', 'PurchasingController');
	Route::get('purchasing/create', 'PurchasingController@create')->name('purchasing.create');
	Route::post('purchasing/update', 'PurchasingController@update')->name('purchasing.update');
	Route::get('purchasing/search', 'PurchasingController@search')->name('purchasing.search');
	// sale
	Route::resource('sale', 'SaleController');
	Route::get('sale/create', 'SaleController@create')->name('sale.create');
	Route::post('sale/update', 'SaleController@update')->name('sale.update');
	Route::get('sale/search', 'SaleController@search')->name('sale.search');
});

Route::group(['middleware' => ['auth','checkRole:eksekutif']], function(){
    // report
    Route::get('/report', 'ReportController@index');
});

Route::group(['middleware' => ['auth','checkRole:eksekutif,admin']], function(){
	// dashboard
	Route::get('/dashboard', 'DashboardController@index');
});