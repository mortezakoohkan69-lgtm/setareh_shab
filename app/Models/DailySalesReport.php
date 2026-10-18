<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DailySalesReport extends Model
{
    use HasFactory;

    protected $fillable = ['report_date', 'total_orders', 'total_gross_sales', 'total_discounts', 'total_net_sales', 'total_cogs', 'total_expenses', 'gross_profit', 'net_profit'];
    protected $casts = ['report_date' => 'date', 'total_gross_sales' => 'decimal:2', 'total_discounts' => 'decimal:2', 'total_net_sales' => 'decimal:2', 'total_cogs' => 'decimal:2', 'total_expenses' => 'decimal:2', 'gross_profit' => 'decimal:2', 'net_profit' => 'decimal:2'];
}
