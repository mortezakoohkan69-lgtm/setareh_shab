<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductSalesStat extends Model
{
    use HasFactory;

    protected $fillable = ['product_id', 'report_period', 'total_quantity_sold', 'total_revenue', 'total_profit'];
    protected $casts = ['total_quantity_sold' => 'decimal:2', 'total_revenue' => 'decimal:2', 'total_profit' => 'decimal:2'];

    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
}
