<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['approved_at', 'featured_since'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * Get the description records associated with the product.
     */
    public function descriptions()
    {
        return $this->hasMany(Description::class);
    }

    /**
     * Get the product records by name.
     */
    public function scopeWithName($query, $name)
    {
        return $query->where('name', $name);
    }

    /**
     * Get the product records that match the given keyword.
     */
    public function scopeWithKeyword($query, $keyword)
    {
        return $query->where('name', 'like', '%'.$keyword.'%');
    }
}
