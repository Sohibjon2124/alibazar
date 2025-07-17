<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class PopularProduct extends Model
{
    use SoftDeletes, HasFactory;

    public function product(): HasOne
    {
        return $this->hasOne(Product::class);
    }
}
