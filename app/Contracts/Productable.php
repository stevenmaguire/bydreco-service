<?php namespace App\Contracts;

interface Productable
{
    /**
     * Creates new product with given options.
     *
     * @param array  $options
     *
     * @return Product
     */
    public function addProduct($options = []);

    /**
     * Creates new product description with given options.
     *
     * @param array  $options
     *
     * @return Description
     * @throws ModelNotFoundException
     */
    public function addProductDescription($productId, $options = []);

    /**
     * Retrives paginated list of products.
     *
     * @param array  $options
     *
     * @return Collection
     */
    public function getList($options = []);

    /**
     * Attempts to retrieve a given product by id.
     *
     * @param integer  $productId
     *
     * @return Product
     * @throws ModelNotFoundException
     */
    public function getProduct($productId);

    /**
     * Retrives paginated list of discriptions for a given product.
     *
     * @param integer $productId
     * @param array   $options
     *
     * @return Collection
     * @throws ModelNotFoundException
     */
    public function getProductDescriptions($productId, $options = []);

    /**
     * Retrieves validation rules for product creation.
     *
     * @return array
     */
    public function getValidationRules();

    /**
     * Attempts to retrieve and update a given product by id.
     *
     * @param integer  $productId
     * @param array    $options
     *
     * @return Product
     * @throws ModelNotFoundException
     */
    public function updateProduct($productId, $options = []);
}
