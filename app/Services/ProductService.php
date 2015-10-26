<?php namespace App\Services;

use App\Description;
use App\Events;
use App\Product;
use App\Contracts\Productable;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Validator;
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
     * @return Product|Illuminate\Contracts\Validation\Validator
     */
    public function addProduct($options = [])
    {
        $v = Validator::make($options, $this->getValidationRules());

        if ($v->passes()) {
            $product = $this->product->create([
                'name' => $options['name']
            ]);

            event(new Events\ProductWasCreated($product));

            return $product;
        }

        return $v;
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

        return $description->fresh();
    }

    public function findOrCreate($name)
    {
        // Attempt to find in cache
        $products = $this->getAll();

        $filtered = $products->filter(function ($item) use ($name) {
            return $item->name == $name;
        });

        if (!$filtered->isEmpty()) {
            return $filtered->first();
        }

        // Attempt to find in database
        $product = $this->product->withIdOrName($name)->first();

        if ($product) {
            return $product;
        }

        // Attempt to create
        return $this->addProduct(['name' => $name]);
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
     * Retrives list of all products.
     *
     * @return Collection
     */
    protected function getAll()
    {
        $query = $this->product->query();

        return $this->cache('all', $query, 'get');
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
        $sort = isset($options['sort']) ? $options['sort'] : null;
        $keyword = isset($options['keyword']) ? $options['keyword'] : null;
        $take = isset($options['take']) ? $options['take'] : null;

        if ($sort == 'random') {
            return $this->getRandom($take ?: 1);
        }

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
        $query = $this->product->withIdOrName($productId);

        $product = $this->cache(
            sprintf('find(%s)', $productId),
            $query,
            'first'
        );

        if ($product) {
            return $product;
        }

        throw new ModelNotFoundException;
    }

    /**
     * Retrieves a random product from cached collection.
     *
     * @param  integer  $count
     *
     * @return Collection|Product
     */
    public function getRandom($count = 1)
    {
        $products = $this->getAll();

        return $products->random($count);
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
