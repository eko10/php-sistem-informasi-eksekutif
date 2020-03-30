<?php

// auth
Route::group(['middleware' => 'guest'], function () {
	// Route::get('/', function () {
	// 	return view('auth.login');
	// })->name('login');
	Route::get('/', 'AuthController@login')->name('login');
	Route::post('prosesLogin', 'AuthController@prosesLogin')->name('prosesLogin');
});

Route::get('/logout', 'AuthController@logout')->name('logout');

// home
Route::group(['middleware' => ['auth','checkRole:admin']], function(){
    // user
	Route::resource('user', 'UserController')->name('*', 'user');
	Route::post('user/update', 'UserController@update')->name('user.update');
	Route::post('editUser', 'UserController@editUser')->name('user.edit');
	// product
	Route::resource('category', 'CategoryController')->name('*', 'category');
	Route::post('category/update', 'CategoryController@update')->name('category.update');
	Route::get('categorySearch', 'CategoryController@search')->name('category.search');
	// product
	Route::resource('product', 'ProductController')->name('*', 'product');
	Route::post('product/update', 'ProductController@update')->name('product.update');
	Route::get('productSearch', 'ProductController@search')->name('product.search');
	Route::get('productSearchByCode', 'ProductController@searchByCode')->name('product.searchByCode');
	Route::post('getProductById', 'ProductController@getProductById')->name('product.getProductById');
	// supplier
	Route::resource('supplier', 'SupplierController')->name('*', 'supplier');
	Route::post('supplier/update', 'SupplierController@update')->name('supplier.update');
	Route::get('supplierSearch', 'SupplierController@search')->name('supplier.search');
	// faculty
	Route::resource('faculty', 'FacultyController')->name('*', 'faculty');
	Route::post('faculty/update', 'FacultyController@update')->name('faculty.update');
	Route::get('facultySearch', 'FacultyController@search')->name('faculty.search');
	// major
	Route::resource('major', 'MajorController')->name('*', 'major');
	Route::post('major/update', 'MajorController@update')->name('major.update');
	Route::get('majorSearch/{id}', 'MajorController@search')->name('major.search');
	// purchasing
	Route::resource('purchasing', 'PurchasingController')->name('*', 'purchasing');
	Route::get('purchasing/create', 'PurchasingController@create')->name('purchasing.create');
	Route::post('purchasing/update', 'PurchasingController@update')->name('purchasing.update');
	Route::get('purchasing/search', 'PurchasingController@search')->name('purchasing.search');
	// sale
	Route::resource('sale', 'SaleController')->name('*', 'sale');
	Route::get('sale/create', 'SaleController@create')->name('sale.create');
	Route::post('sale/update', 'SaleController@update')->name('sale.update');
	Route::get('sale/search', 'SaleController@search')->name('sale.search');
});

Route::group(['middleware' => ['auth','checkRole:eksekutif']], function(){
    // report
    Route::get('/report', 'ReportController@index')->name('report');
});

Route::group(['middleware' => ['auth','checkRole:eksekutif,admin']], function(){
	// dashboard
	Route::get('/dashboard', 'DashboardController@index')->name('dashboard');
	Route::get('report/sumPurchasingByYear', 'ReportController@sumPurchasingByYear')->name('report.sumPurchasingByYear');
	Route::get('report/sumPurchasingTest', 'ReportController@sumPurchasingTest')->name('report.sumPurchasingTest');
	Route::get('report/sumPurchasingByMonth', 'ReportController@sumPurchasingByMonth')->name('report.sumPurchasingByMonth');
	Route::get('report/sumPurchasingByMonthTest', 'ReportController@sumPurchasingByMonthTest')->name('report.sumPurchasingByMonthTest');
});