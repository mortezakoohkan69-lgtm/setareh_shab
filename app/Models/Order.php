<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = ['customer_id', 'bank_account_id', 'applied_discount_id', 'total_raw_amount', 'total_discount', 'final_amount', 'total_cost_price', 'payment_type', 'status', 'user_id'];
    protected $casts = ['total_raw_amount' => 'decimal:2', 'total_discount' => 'decimal:2', 'final_amount' => 'decimal:2', 'total_cost_price' => 'decimal:2'];

    public function customer(): BelongsTo { return $this->belongsTo(Customer::class); }
    public function bankAccount(): BelongsTo { return $this->belongsTo(BankAccount::class); }
    public function discount(): BelongsTo { return $this->belongsTo(Discount::class, 'applied_discount_id'); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function items(): HasMany { return $this->hasMany(OrderItem::class); }
}
