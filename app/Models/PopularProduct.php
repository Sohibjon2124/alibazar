<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class PopularProduct extends Model
{
    use SoftDeletes;

    public function product(): HasOne
    {
        return $this->hasOne(Product::class);
    }
}
