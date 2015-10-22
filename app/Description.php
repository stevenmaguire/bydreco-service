<?php namespace App;

use Illuminate\Database\Eloquent\Model;

class Description extends Model
{
    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['preferred_since'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['body'];

    /**
     * Get the product record associated with the description.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the description records associated with the given product id.
     */
    public function scopeOfProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }

    /**
     * Get the description records that match the given keyword.
     */
    public function scopeWithKeyword($query, $keyword)
    {
        return $query->where('body', 'like', '%'.$keyword.'%');
    }

    /**
     * Increments appropriate vote tally and recalculates score.
     *
     * @param boolean  $up
     *
     * @return Description
     */
    public function addVote($up = true)
    {
        if ($up) {
            $this->vote_ups++;
        } else {
            $this->vote_downs++;
        }

        return $this->updateScore();
    }

    /**
     * Recalculates score based on current vote composition.
     *
     * @return Description
     */
    protected function updateScore()
    {
        $votes = $this->vote_ups + $this->vote_downs;
        $ratio = $this->vote_downs > 0 ? ($this->vote_ups / $this->vote_downs) : 1;
        $score = round($votes * $ratio, 2);
        \Log::info($this->id.': '.$score);

        $this->score = $score;

        return $this;
    }
}
