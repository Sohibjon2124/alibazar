<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = ['name', 'price', 'category_id', 'count', 'image', 'description', 'status'];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function promotion(): HasOne
    {
        return $this->hasOne(ProductPromotion::class)
            ->where('end_date', '>=', Carbon::now());
    }

    public function colors(): HasMany
    {
        return $this->hasMany(ProductColor::class);
    }
    public function sizes(): HasMany
    {
        return $this->hasMany(ProductSize::class);
    }
}
