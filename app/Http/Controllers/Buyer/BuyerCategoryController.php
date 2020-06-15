<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class BuyerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        //collapse se usa para unir varias listas, ya que sin ese metodo
        // estariamos recibiendo muchas listas de categorias
        // con el unique y values se esta filtrando para que no se repitan
        //values() es para que no queden objetos vacios 
        $categories = $buyer->transactions()->with('product.categories')
        ->get()
        ->pluck('product.categories')
        ->collapse()
         ->unique('id')
         ->values();


        //dd($categories);
        return $this->showAll($categories);

    }

   
}
