<?php namespace App\Services;

use App\Description;
use App\Product;
use App\Contracts\Productable;

class ProductService implements Productable
{
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    public function addProduct($options = [])
    {
        return $this->product->create([
            'name' => $options['name']
        ]);
    }

    public function addProductDescription($productId, $options = [])
    {
        $product = $this->product->findOrFail($productId);

        return $product->descriptions()->save(new Description([
            'body' => $options['body'],
        ]));
    }

    public function getList($options = [])
    {
        return $this->product
            ->withKeyword($options['keyword'])
            ->paginate($options['take']);
    }

    public function getProductDescriptions($productId, $options = [])
    {
        $product = $this->product->findOrFail($productId);

        return $product->descriptions()->paginate($options['take']);
    }

    public function updateProduct($productId, $options = [])
    {
        $product = $this->product->findOrFail($productId);

        $product->update([
            'name' => $options['name']
        ]);

        return $product;
    }
}
