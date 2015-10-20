<?php namespace App\Events;

use App\Product;

class ProductWasCreated extends Event
{
    /**
     * Creates new event.
     *
     * @param Product  $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }
}
