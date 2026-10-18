<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Discount extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'type', 'discount_type', 'discount_value', 'min_purchases', 'start_date', 'end_date', 'start_time', 'end_time', 'days_of_week', 'is_active'];
    protected $casts = ['discount_value' => 'decimal:2', 'start_date' => 'datetime', 'end_date' => 'datetime', 'is_active' => 'boolean'];

    public function products(): BelongsToMany { return $this->belongsToMany(Product::class, 'discount_products'); }
    public function orders(): HasMany { return $this->hasMany(Order::class, 'applied_discount_id'); }
}
