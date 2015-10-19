<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Contracts\Productable;
use App\Http\Requests;
use App\Http\Controllers\Controller;

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
        return $this->product->getList([
            'keyword' => $request->input('keyword'),
            'take' => 15,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|unique:products|productQuality',
        ]);

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
        $this->validate($request, [
            'name' => 'required|unique:products|productQuality',
        ]);

        return $this->product->updateProduct($id, $request->input());
    }
}
