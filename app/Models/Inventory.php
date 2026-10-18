<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    use HasFactory;

    protected $table = 'inventory';
    protected $fillable = ['product_id', 'current_stock', 'min_stock_alert', 'unit'];
    protected $casts = ['current_stock' => 'decimal:3', 'min_stock_alert' => 'decimal:3'];

    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
}
