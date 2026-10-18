<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cheque extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = ['type', 'serial_number', 'amount', 'payee_payor', 'for_reason', 'issue_date', 'due_date', 'status', 'bank_account_id', 'description'];
    protected $casts = ['amount' => 'decimal:2', 'issue_date' => 'date', 'due_date' => 'date'];

    public function bankAccount(): BelongsTo { return $this->belongsTo(BankAccount::class); }
}
