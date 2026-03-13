<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

// Categories
Route::get('categories', 'api\CategoriesAPIController@index');
Route::get('categories/{id}', 'api\CategoriesAPIController@show');
Route::get('categoryByName/{name}', 'api\CategoriesAPIController@categoryByName');
Route::get('categories/fetch/{id}', 'api\CategoriesAPIController@fetch');
Route::get('categories/fetchname/{name}', 'api\CategoriesAPIController@fetchname');
Route::post('categoriesSearch', 'api\CategoriesAPIController@productList')->middleware('auth:sanctum');
Route::post('guestCategoriesSearch', 'api\CategoriesAPIController@productList');

// Products
Route::get('products/{id}', 'api\ProductsAPIController@show');
Route::get('products/{id}/{lat}/{lon}', 'api\ProductsAPIController@showCoord');
Route::get('newproducts', 'api\ProductsAPIController@newList');
Route::get('productByName/{name}/{lat}/{lon}', 'api\ProductsAPIController@showNameCoord');
Route::get('productByName/{name}', 'api\ProductsAPIController@showName');
Route::get('product-review/{product}', 'api\ProductsAPIController@fetchReview');
Route::get('product-review-name/{productName}', 'api\ProductsAPIController@fetchReviewName');
Route::post('products', 'api\ProductsAPIController@store')->middleware('auth:sanctum');
Route::post('report-product', 'api\ProductsAPIController@report');
Route::post('productsguest', 'api\ProductsAPIController@storeguest');
Route::get('products/fetchname/{name}', 'api\ProductsAPIController@fetchname');
Route::post('update-price', 'api\ProductsAPIController@updatePrice');
Route::get('getDicofre/{coords}', 'api\ProductsAPIController@getDicofre');

// Search
// Route::get('productList/{searchText}/{sortBy}/{distrito}/{concelho}/{brand}/{store}', 'api\SearchAPIController@productList');
Route::post('productList', 'api\SearchAPIController@productList')->middleware('auth:sanctum');
Route::post('guestProductList', 'api\SearchAPIController@productList');
Route::get('valuelist/{name}', 'api\SearchAPIController@valueList');

// Brands
Route::get('brands/fetch/{name}', 'api\BrandsAPIController@fetch');
Route::get('brands/fetchname/{name}', 'api\BrandsAPIController@fetchname');
Route::get('brandByName/{name}', 'api\BrandsAPIController@brandByName');

// Stores
Route::get('stores/fetch/{name}', 'api\StoresAPIController@fetch');
Route::get('stores/fetch/{name}/{distrito}/{concelho}/{freguesia}', 'api\StoresAPIController@geofetch');
Route::get('stores/fetchname/{name}', 'api\StoresAPIController@fetchname');
Route::post('stores', 'api\StoresAPIController@store');
Route::post('storesNew', 'api\StoresAPIController@storeNew');
Route::post('storesNewLocation', 'api\StoresAPIController@storeNewLocation');

// Tags
Route::get('tags', 'api\TagsAPIController@fetch');
Route::get('tags/{id}', 'api\TagsAPIController@show');

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
// Auth
Route::post('/login', 'api\LoginAPIController@login');
Route::post('/recover', 'api\LoginAPIController@recover');
Route::post('/setnewpwd', 'api\LoginAPIController@setnewpwd');
Route::post('/register', 'api\LoginAPIController@register');
Route::post('/updatepwd', 'api\LoginAPIController@updatepwd')->middleware('auth:sanctum');
Route::post('/deleteaccount', 'api\LoginAPIController@deleteaccount')->middleware('auth:sanctum');

// Review
Route::post('/review', 'api\ProductsAPIController@review')->middleware('auth:sanctum');
Route::post('/guest-review', 'api\GuestAPIController@review');

// Contacto
Route::post('/contact', 'api\ContactsAPIController@contact')->middleware('auth:sanctum');
Route::post('/guest-contact', 'api\ContactsAPIController@guestContact');

// CAOP
Route::get('/caop/distritos', 'api\CAOPAPIController@getDistritos');
Route::get('/caop/concelhos/{distrito}', 'api\CAOPAPIController@getConcelhos');
Route::get('/caop/freguesias/{freguesia}', 'api\CAOPAPIController@getFreguesias');
Route::post('/updatelocation', 'api\CAOPAPIController@updatelocation')->middleware('auth:sanctum');

// Forum
Route::get('/boards', 'api\ForumAPIController@boards');
Route::get('/posts/{board_id}', 'api\ForumAPIController@posts');
Route::get('/post/{post_id}', 'api\ForumAPIController@post');
Route::post('/post', 'api\ForumAPIController@store')->middleware('auth:sanctum');

// Report
Route::get('/reportTypes', 'api\ProductsAPIController@getReportTypes');

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
