<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class BuyerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
      //  con esta linea no se puede ingresar a product, ya que se trae una lista de transacciones y es convertido en una collecion 
      //y no una instancia de transactions, por la relacion que tiene transaction de 1 a muchos en las tablas de buyer y product
       // $products = $buyer->transactions->product;

       //con esto se crea un query builder, y entrar a las relaciones con el metodo get se crea una collecion
       // con el metodo pluck() entramos una sola parte de la collecion, en este caso product
        $products = $buyer->transactions()->with('product')
            ->get()
            ->pluck('product');

       // dd($products);

        return $this->showAll($products);
    }

   
}
