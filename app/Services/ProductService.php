<?php namespace App\Services;

use App\Description;
use App\Events;
use App\Product;
use App\Contracts\Productable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Stevenmaguire\Laravel\Services\EloquentCache;

class ProductService extends EloquentCache implements Productable
{
    /**
     * Creates new service object.
     *
     * @param Product  $product
     */
    public function __construct(Product $product)
    {
        $this->product = $product;
    }

    /**
     * Get cache key from concrete service
     *
     * @return string
     */
    protected function getCacheKey()
    {
        return 'products';
    }

    /**
     * Get model from concrete service
     *
     * @return Illuminate\Database\Eloquent\Model
     */
    protected function getModel()
    {
        return $this->product;
    }

    /**
     * Creates new product with given options.
     *
     * @param array  $options
     *
     * @return Product
     */
    public function addProduct($options = [])
    {
        $product = $this->product->create([
            'name' => $options['name']
        ]);

        event(new Events\ProductWasCreated($product));

        return $product;
    }

    /**
     * Creates new product description with given options.
     *
     * @param array  $options
     *
     * @return Description
     * @throws ModelNotFoundException
     */
    public function addProductDescription($productId, $options = [])
    {
        $product = $this->getProduct($productId);

        $description = $product->descriptions()->save(new Description([
            'body' => $options['body'],
        ]));

        event(new Events\DescriptionWasCreated($description));

        return $description;
    }

    /**
     * Flush relavent cache for a given product.
     *
     * @param  Product  $product
     *
     * @return void
     */
    public function flushProductCache(Product $product)
    {
        $this->flushCache('^all');
    }

    /**
     * Flush relavent cache for descriptions of a given product.
     *
     * @param  Product  $product
     *
     * @return void
     */
    public function flushProductDescriptionCache(Product $product)
    {
        $this->flushCache('^find\('.$product->id.'\)\.descriptions');
    }

    /**
     * Retrives paginated list of products.
     *
     * @param array  $options
     *
     * @return Collection
     */
    public function getList($options = [])
    {
        $keyword = isset($options['keyword']) ? $options['keyword'] : null;
        $take = isset($options['take']) ? $options['take'] : null;
        $query = $this->product->withKeyword($keyword);

        return $this->cache(
            sprintf('all(keyword:%s,take:%s)', $keyword, $take),
            $query,
            sprintf('paginate:%s', $take)
        );
    }

    /**
     * Attempts to retrieve a given product by id.
     *
     * @param integer  $productId
     *
     * @return Product
     * @throws ModelNotFoundException
     */
    public function getProduct($productId)
    {
        $query = $this->product->query();

        $product = $this->cache(
            sprintf('find(%s)', $productId),
            $query,
            sprintf('find:%s', $productId)
        );

        if ($product) {
            return $product;
        }

        throw new ModelNotFoundException;
    }

    /**
     * Retrives paginated list of discriptions for a given product.
     *
     * @param integer $productId
     * @param array   $options
     *
     * @return Collection
     * @throws ModelNotFoundException
     */
    public function getProductDescriptions($productId, $options = [])
    {
        $keyword = isset($options['keyword']) ? $options['keyword'] : null;
        $take = isset($options['take']) ? $options['take'] : null;
        $product = $this->getProduct($productId);
        $query = $product->descriptions()->getQuery();

        return $this->cache(
            sprintf('find(%s).descriptions(keyword:%s,take:%s)', $productId, $keyword, $take),
            $query,
            sprintf('paginate:%s', $take)
        );
    }

    /**
     * Retrieves validation rules for product creation.
     *
     * @return array
     */
    public function getValidationRules()
    {
        return [
            'name' => 'required|unique:products|productQuality',
        ];
    }

    /**
     * Attempts to retrieve and update a given product by id.
     *
     * @param integer  $productId
     * @param array    $options
     *
     * @return Product
     * @throws ModelNotFoundException
     */
    public function updateProduct($productId, $options = [])
    {
        $product = $this->getProduct($productId);

        $product->update([
            'name' => $options['name']
        ]);

        return $product;
    }
}
