<?php namespace App\Contracts;

interface Productable
{
    public function addProduct($options = []);
    public function addProductDescription($productId, $options = []);
    public function getList($options = []);
    public function getProductDescriptions($productId, $options = []);
    public function updateProduct($productId, $options = []);
}
