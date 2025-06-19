<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductPromotion extends Model
{
    use SoftDeletes;

    protected $fillable = ['product_id', 'price', 'end_date'];
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
