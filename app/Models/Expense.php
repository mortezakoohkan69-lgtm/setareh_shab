<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'amount', 'bank_account_id', 'expense_date', 'user_id', 'description'];
    protected $casts = ['amount' => 'decimal:2', 'expense_date' => 'date'];

    public function bankAccount(): BelongsTo { return $this->belongsTo(BankAccount::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
