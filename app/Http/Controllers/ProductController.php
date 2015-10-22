<?php namespace App\Http\Controllers;

use App\Contracts\Productable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductController extends Controller
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
     * @return Response
     */
    public function index(Request $request)
    {
        return $this->product->getList($request->input());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, $this->product->getValidationRules());

        return $this->product->addProduct($request->input());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, $this->product->getValidationRules());

        return $this->product->updateProduct($id, $request->input());
    }
}
