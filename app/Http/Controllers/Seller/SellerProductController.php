<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Seller;
use App\User;
use App\Product;

use Illuminate\Http\Request;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
        $products = $seller->products;
        return $this->showAll($products);
    }

 
   
    public function store(Request $request, User $seller)
    {
        $rules= [
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'image'=> 'required|image'
        ];

        $this->validate($request, $rules);

        $data = $request->all();
        $data['status'] = Product::PRODUCTO_NO_DISPONIBLE;
        $data['image'] = '1.png';
        $data['seller_id'] = $seller->id;

        $product = Product::create($data);
        return $this->showOne($product, 201);

    }

  
    public function update(Request $request, Seller $seller)
    {
        //
    }

   
    public function destroy(Seller $seller)
    {
        //
    }
}
