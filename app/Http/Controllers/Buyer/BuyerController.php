<?php

namespace App\Http\Controllers\Buyer;
use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;


class BuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $compradores = Buyer::has('transactions')->get();
        //esto viene del ApiResponser
        return $this->showAll($compradores);

        
       // return response()->json(['data' => $compradores], 200);

    }


    public function show(Buyer $buyer)
    {
     //   $comprador = Buyer::has('transactions')->findOrfail($id);
        // ocupamos Scope de Scopes/BuyerScope
        return $this->showOne($buyer);
        
        // return response()->json(['data' => $comprador], 200);
    }


}
