<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductRecipe extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'material_id', 'quantity', 'unit'];
    protected $casts = ['quantity' => 'decimal:3'];

    public function product(): BelongsTo { return $this->belongsTo(Product::class, 'product_id'); }
    public function material(): BelongsTo { return $this->belongsTo(Product::class, 'material_id'); }
}
