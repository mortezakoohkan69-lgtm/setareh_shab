<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['category_id', 'name', 'sku', 'cost_price', 'sale_price', 'discount_type', 'discount_value', 'is_active', 'is_material'];
    protected $casts = ['cost_price' => 'decimal:2', 'sale_price' => 'decimal:2', 'discount_value' => 'decimal:2', 'is_active' => 'boolean', 'is_material' => 'boolean'];

    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function inventory(): HasOne { return $this->hasOne(Inventory::class); }
    public function inventoryLogs(): HasMany { return $this->hasMany(InventoryLog::class); }
    public function recipes(): HasMany { return $this->hasMany(ProductRecipe::class, 'product_id'); }
    public function usedInRecipes(): HasMany { return $this->hasMany(ProductRecipe::class, 'material_id'); }
    public function discounts(): BelongsToMany { return $this->belongsToMany(Discount::class, 'discount_products'); }
    public function orderItems(): HasMany { return $this->hasMany(OrderItem::class); }
    public function purchaseItems(): HasMany { return $this->hasMany(PurchaseItem::class); }
    public function salesStats(): HasMany { return $this->hasMany(ProductSalesStat::class); }
}
