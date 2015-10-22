<?php namespace App\Http\Controllers;

use App\Contracts\Descriptionable;
use App\Contracts\Productable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductDescriptionController extends Controller
{
    /**
     * Creates new controller instance.
     *
     * @param Productable  $product
     */
    public function __construct(Descriptionable $description, Productable $product)
    {
        $this->description = $description;
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
        return $this->product->getProductDescriptions($productId, $request->input());
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
        $this->validate($request, $this->description->getValidationRules());

        return $this->product->addProductDescription($productId, $request->input());
    }
}
