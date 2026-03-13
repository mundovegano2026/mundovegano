<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return view('home');
})->name('home');


Route::get('/{vue_capture?}', function () {
    return view('home');
})->where('vue_capture', '^((?!admin).)*$[\/\w\.-]*'); // ^((?!login|admin|logout).)*$[\/\w\.-]*

// Chains
Route::resource('admin/chains', 'ChainsController')->middleware("auth");
Route::get('/admin/chains/activate/{id}', 'ChainsController@activate')->middleware("auth");
Route::get('/admin/chains/inactivate/{id}/', 'ChainsController@inactivate')->middleware("auth");
Route::get('/admin/chains/loadModal/{id}','ChainsController@loadModal')->middleware("auth");
Route::post('/admin/chains/fetch', 'ChainsController@fetch')->middleware("auth")->name('chains.fetch');
Route::post('/admin/chains/fetchForProduct', 'ChainsController@fetchForProduct')->middleware("auth")->name('chains.fetchForProduct');
Route::post('/admin/chains/validateName', 'ChainsController@validateName')->middleware("auth")->name('chains.validateName');
Route::post('/admin/chains/delete', 'ChainsController@delete')->middleware("auth");
Route::get('/admin/chains/loadModalDelete/{id}','ChainsController@loadModalDelete')->middleware("auth");

// Stores
Route::resource('admin/stores', 'StoresController')->middleware("auth");
Route::get('/admin/stores/activate/{id}', 'StoresController@activate')->middleware("auth");
Route::get('/admin/stores/inactivate/{id}/', 'StoresController@inactivate')->middleware("auth");
Route::get('/admin/stores/loadModal/{id}','StoresController@loadModal')->middleware("auth");
Route::post('/admin/stores/fetch', 'StoresController@fetch')->middleware("auth")->name('stores.fetch');
Route::post('/admin/stores/validateName', 'StoresController@validateName')->middleware("auth")->name('stores.validateName');
Route::post('/admin/stores/delete', 'StoresController@delete')->middleware("auth");
Route::get('/admin/stores/loadModalDelete/{id}','StoresController@loadModalDelete')->middleware("auth");

// Brands
Route::resource('admin/brands', 'BrandsController')->middleware("auth");
Route::get('/admin/brands/activate/{id}', 'BrandsController@activate')->middleware("auth");
Route::get('/admin/brands/inactivate/{id}/', 'BrandsController@inactivate')->middleware("auth");
Route::get('/admin/brands/loadModal/{id}','BrandsController@loadModal')->middleware("auth");
Route::post('/admin/brands/fetch', 'BrandsController@fetch')->name('brands.fetch')->middleware("auth");
Route::post('/admin/brands/validateName', 'BrandsController@validateName')->name('brands.validateName')->middleware("auth");
Route::post('/admin/brands/delete', 'BrandsController@delete')->middleware("auth");
Route::get('/admin/brands/loadModalDelete/{id}','BrandsController@loadModalDelete')->middleware("auth");

// Tags
Route::resource('admin/tags', 'TagsController')->middleware("auth");
Route::get('/admin/tags/activate/{id}', 'TagsController@activate')->middleware("auth");
Route::get('/admin/tags/inactivate/{id}/', 'TagsController@inactivate')->middleware("auth");
Route::get('/admin/tags/loadModal/{id}','TagsController@loadModal')->middleware("auth");
Route::post('/admin/tags/fetch', 'TagsController@fetch')->name('tags.fetch')->middleware("auth");
Route::post('/admin/tags/validateName', 'TagsController@validateName')->name('tags.validateName')->middleware("auth");
Route::post('/admin/tags/delete', 'TagsController@delete')->middleware("auth");
Route::get('/admin/tags/loadModalDelete/{id}','TagsController@loadModalDelete')->middleware("auth");

// Valuelists
Route::resource('admin/valuelists', 'ValuelistsController')->middleware("auth");
Route::get('/admin/valuelists/loadModal/{id}','ValuelistsController@loadModal')->middleware("auth");
Route::post('/admin/valuelists/fetch', 'ValuelistsController@fetch')->name('tags.fetch')->middleware("auth");
Route::post('/admin/valuelists/validateName', 'ValuelistsController@validateName')->name('valuelists.validateName')->middleware("auth");
Route::post('/admin/valuelists/delete', 'ValuelistsController@delete')->middleware("auth");
Route::get('/admin/valuelists/loadModalDelete/{id}','ValuelistsController@loadModalDelete')->middleware("auth");

// Users
Route::resource('admin/users', 'UsersController')->middleware("auth");
Route::get('/admin/users/activate/{id}', 'UsersController@activate')->middleware("auth");
Route::get('/admin/users/inactivate/{id}/', 'UsersController@inactivate')->middleware("auth");
Route::get('/admin/users/loadModal/{id}','UsersController@loadModal')->middleware("auth");
Route::post('/admin/users/fetch', 'UsersController@fetch')->name('users.fetch')->middleware("auth");
Route::post('/admin/users/validateName', 'UsersController@validateName')->name('users.validateName')->middleware("auth");
Route::post('/admin/users/delete', 'UsersController@delete')->middleware("auth");
Route::get('/admin/users/loadModalDelete/{id}','UsersController@loadModalDelete')->middleware("auth");

// Product Reports
Route::get('/admin/product_reports', 'Product_reportsController@index')->middleware("auth");

// Added Prices
Route::get('/admin/prices', 'PricesController@index')->middleware("auth");
Route::get('/admin/prices/loadModal/{id}','PricesController@loadModal')->middleware("auth");
Route::post('price-update', array('uses' => 'PricesController@update'));
// Route::post('/admin/prices/update',['uses'=>'PricesController@update', 'as' =>'price_update'])->middleware("auth");

// Product Reports
Route::get('/admin/product_reports/loadModal/{id}','Product_reportsController@loadModal')->middleware("auth");
Route::post('report-update', array('uses' => 'Product_reportsController@update'));

// Products
Route::resource('admin/products', 'ProductsController')->middleware("auth");
// Route::get('/admin/products/activate/{id}', 'ProductsController@activate')->middleware("auth");
Route::post('/admin/products/activate', 'ProductsController@activate')->middleware("auth");
// Route::get('/admin/products/inactivate/{id}/', 'ProductsController@inactivate')->middleware("auth");
Route::post('/admin/products/inactivate', 'ProductsController@inactivate')->middleware("auth");
Route::post('/admin/products/delete', 'ProductsController@delete')->middleware("auth");
Route::get('/admin/products/loadModal/{id}','ProductsController@loadModal')->middleware("auth");
Route::get('/admin/products/loadModalDelete/{id}','ProductsController@loadModalDelete')->middleware("auth");
Route::post('/admin/products/validateName', 'ProductsController@validateName')->middleware("auth")->name('products.validateName');

// Reviews
Route::resource('admin/reviews', 'ReviewsController')->middleware("auth");
Route::get('/admin/reviews/activate/{id}', 'ReviewsController@activate')->middleware("auth");
Route::get('/admin/reviews/inactivate/{id}/', 'ReviewsController@inactivate')->middleware("auth");
Route::post('/admin/reviews/upvote', 'ReviewsController@upvote')->middleware("auth");
Route::post('/admin/reviews/downvote', 'ReviewsController@downvote')->middleware("auth");
Route::get('/admin/reviews/loadModal/{id}','ReviewsController@loadModal')->middleware("auth");
Route::post('/admin/reviews/delete', 'ReviewsController@delete')->middleware("auth");
Route::get('/admin/reviews/loadModalDelete/{id}','ReviewsController@loadModalDelete')->middleware("auth");

// Caop
Route::get('admin/getDicofre/{coords}', 'CaopController@getDicofre')->middleware("auth");
Route::get('admin/getConcelhos/{distrito}', 'CaopController@getConcelhos')->middleware("auth");
Route::get('admin/getFreguesias/{concelho}', 'CaopController@getFreguesias')->middleware("auth");

// Categories
Route::resource('admin/categories', 'CategoriesController')->middleware("auth");
Route::get('/admin/categories/activate/{id}', 'CategoriesController@activate')->middleware("auth");
Route::get('/admin/categories/inactivate/{id}/', 'CategoriesController@inactivate')->middleware("auth");
Route::post('/admin/categories/fetch', 'CategoriesController@fetch')->name('categories.fetch')->middleware("auth");
Route::post('/admin/categories/delete', 'CategoriesController@delete')->middleware("auth");

// Forums
Route::resource('admin/forums', 'ForumsController')->middleware("auth");
Route::get('/admin/forums/boards/{id}', 'ForumsController@show')->middleware("auth");
Route::put('/admin/forums/posts/{id}', 'ForumsController@updatePost')->middleware("auth");
Route::get('/admin/forums/posts/{id}', 'ForumsController@showPost')->middleware("auth");
Route::get('/admin/forums/comments/{id}', 'ForumsController@showComment')->middleware("auth");
Route::get('/admin/forums/activateboard/{id}', 'ForumsController@activate')->middleware("auth");
Route::get('/admin/forums/inactivateboard/{id}/', 'ForumsController@inactivate')->middleware("auth");

// Validations
Route::get('/admin/validation/products', 'ProductsController@validation')->middleware("auth");
Route::get('/admin/validation/stores', 'StoresController@validation')->middleware("auth");
Route::get('/admin/validation/prices', 'PricesController@validation')->middleware("auth");
Route::get('/admin/validation/product_reports', 'Product_reportsController@validation')->middleware("auth");
Route::get('/admin/validation/brands', 'BrandsController@validation')->middleware("auth");
Route::get('/admin/validation/products/loadModal/{id}','ProductsController@loadModal')->middleware("auth");
Route::get('/admin/validation/stores/loadModal/{id}','StoresController@loadModal')->middleware("auth");
Route::get('/admin/validation/brands/loadModal/{id}','BrandsController@loadModal')->middleware("auth");

// Auth
Auth::routes();
Route::get('/logout', 'Auth\LoginController@logout');
Route::get('/login/admin', 'Auth\LoginController@showAdminLoginForm');
Route::get('/register/admin', 'Auth\RegisterController@showAdminRegisterForm')->middleware("auth");
// Route::get('/login', 'Auth\LoginController@showUserLoginForm');

Route::post('/login/admin', 'Auth\LoginController@adminLogin');
Route::post('/register/admin', 'Auth\RegisterController@createAdmin')->middleware("auth");
// Route::post('/login', 'Auth\LoginController@userLogin');

Route::get('/admin', 'AdminController@index')->middleware("auth")->name('admin.dashboard');
Route::get('/admin/tasks', 'AdminController@tasks')->middleware("auth")->name('admin.tasks');
Route::get('/home', 'HomeController@index')->name('home');
