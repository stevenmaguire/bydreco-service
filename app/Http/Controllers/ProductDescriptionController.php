<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\Productable;
use App\Product;
use App\Description;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ProductDescriptionController extends Controller
{
    /**
     * Creates new controller instance.
     *
     * @param Productable  $product
     */
    public function __construct(Productable $product)
    {
        $this->product = $product;
    }

    /**
     * Display a listing of the resource.
     *
     * @param  int  $productId
     * @param  Request  $request
     * @return Response
     */
    public function index($productId, Request $request)
    {
        return $this->product->getProductDescriptions($productId, [
            'keyword' => $request->input('keyword'),
            'take' => 15
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  int      $productId
     * @param  Request  $request
     * @return Response
     */
    public function store($productId, Request $request)
    {
        $this->validate($request, [
            'body' => ['required'],
        ]);

        return $this->product->addProductDescription($productId, $request->input());
    }
}
