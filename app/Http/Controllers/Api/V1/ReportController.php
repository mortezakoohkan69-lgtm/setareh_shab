<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\BankAccount;
use App\Models\Cheque;
use App\Models\DailySalesReport;
use App\Models\Inventory;
use App\Models\OrderItem;
use App\Models\ProductSalesStat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends ApiController
{
    public function dailySales(Request $request) { $query = DailySalesReport::query(); if ($request->filled('from')) $query->whereDate('report_date', '>=', $request->date('from')); if ($request->filled('to')) $query->whereDate('report_date', '<=', $request->date('to')); return $this->success($query->orderByDesc('report_date')->paginate(31), 'گزارش فروش روزانه دریافت شد.'); }
    public function productStats(Request $request) { $stats = ProductSalesStat::with('product')->when($request->filled('period'), fn ($query) => $query->where('report_period', $request->string('period')))->orderByDesc('total_quantity_sold')->paginate(30); return $this->success($stats, 'آمار فروش محصولات دریافت شد.'); }
    public function dashboardSummary() { $today = now()->toDateString(); return $this->success(['today_sales' => DailySalesReport::whereDate('report_date', $today)->first(), 'accounts_balance' => BankAccount::where('is_active', true)->sum('current_balance'), 'due_cheques' => Cheque::where('status', 'pending')->whereDate('due_date', '<=', $today)->count(), 'low_stock' => Inventory::with('product')->whereColumn('current_stock', '<=', 'min_stock_alert')->get()], 'خلاصه داشبورد دریافت شد.'); }
}
