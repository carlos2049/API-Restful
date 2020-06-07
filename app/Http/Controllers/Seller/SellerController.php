<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;
use App\Seller;

class SellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vendedores = Seller::has('products')->get();
       return $this->showAll($vendedores);

       // return response()->json(['data' => $vendedores ], 200);
    }

 


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $vendedores = Seller::has('products')->findOrFail($id);
       
       return $this->showOne($vendedores);
       
       // return response()->json(['data' => $vendedores], 200);
    }





}
