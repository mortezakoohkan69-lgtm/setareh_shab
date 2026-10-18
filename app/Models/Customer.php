<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['first_name', 'last_name', 'mobile', 'birth_date', 'purchase_count', 'total_spent'];
    protected $casts = ['birth_date' => 'date', 'total_spent' => 'decimal:2'];
    protected $appends = ['purchase_count', 'total_purchase_amount'];

    public function orders(): HasMany { return $this->hasMany(Order::class); }

    public function getPurchaseCountAttribute(): int
    {
        if (array_key_exists('completed_orders_count', $this->attributes)) return (int) $this->attributes['completed_orders_count'];
        return $this->orders()->where('status', 'completed')->count();
    }

    public function getTotalPurchaseAmountAttribute(): float
    {
        if (array_key_exists('completed_orders_sum_final_amount', $this->attributes)) return (float) $this->attributes['completed_orders_sum_final_amount'];
        return (float) $this->orders()->where('status', 'completed')->sum('final_amount');
    }

    public function getTotalSpentAttribute(): float
    {
        return $this->total_purchase_amount;
    }
}
