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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

 
/*
el metodo resource recibe un nombre, normalmente en plural
despues recibe el controlador, si esta en carpeta debe ser incluido igual

- el tercer parametro recibe un array con los filtros de los permisos 
    que tiene los metodos de cada controlador
only = solamente podran ocupar los qque esten en el array
except = seran las excepciones que tendra el array 

*/

//buyers
Route::resource('buyers', 'Buyer\BuyerController', ['only' => ['index', 'show']]);

//categories
Route::resource('categories', 'Category\CategoryController', ['except' => ['create', 'edit']]);

//Products
Route::resource('products', 'Product\ProductController', ['only' => ['index', 'show']]);

// Transactions
Route::resource('transactions', 'Transaction\TransactionController', ['only' => ['index', 'show']]);
Route::resource('transactions.categories', 'Transaction\TransactionCategoryController', ['only' => ['index']]);
Route::resource('transactions.sellers', 'Transaction\TransactionSellerController', ['only' => ['index']]);

//sellers
Route::resource('sellers', 'Seller\SellerController', ['only' => ['index', 'show']]);

//Users
Route::resource('users', 'User\UserController', ['except' => ['create', 'edit']]);
