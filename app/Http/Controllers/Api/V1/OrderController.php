<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\OrderRequest;
use App\Http\Resources\Api\OrderResource;
use App\Models\BankAccount;
use App\Models\DailySalesReport;
use App\Models\Discount;
use App\Models\Inventory;
use App\Models\InventoryLog;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends ApiController
{
    public function index() { return $this->success(OrderResource::collection(Order::with(['customer', 'items.product'])->latest()->paginate(20))->response()->getData(true)['data'], 'فاکتورها دریافت شدند.'); }
    public function show(int $order) { return $this->success(new OrderResource(Order::with(['customer', 'items.product', 'discount'])->findOrFail($order)), 'جزئیات فاکتور دریافت شد.'); }

    public function store(OrderRequest $request)
    {
        $data = $request->validated();
        $order = DB::transaction(function () use ($data, $request) {
            $raw = 0; $cost = 0; $consumptions = [];
            foreach ($data['items'] as $line) {
                $product = Product::with('recipes.material')->findOrFail($line['product_id']);
                $raw += $product->sale_price * $line['quantity'];
                if ($product->recipes->isEmpty()) {
                    $this->consume($product->id, $line['quantity'], $request->user()->id, $consumptions);
                    $cost += $product->cost_price * $line['quantity'];
                } else {
                    foreach ($product->recipes as $recipe) {
                        $amount = $recipe->quantity * $line['quantity'];
                        $this->consume($recipe->material_id, $amount, $request->user()->id, $consumptions);
                        $cost += $recipe->material->cost_price * $amount;
                    }
                }
            }
            $discountAmount = $this->discount($data['applied_discount_id'] ?? null, $raw);
            $order = Order::create(['customer_id' => $data['customer_id'] ?? null, 'bank_account_id' => $data['bank_account_id'] ?? null, 'applied_discount_id' => $data['applied_discount_id'] ?? null, 'total_raw_amount' => $raw, 'total_discount' => $discountAmount, 'final_amount' => max(0, $raw - $discountAmount), 'total_cost_price' => $cost, 'payment_type' => $data['payment_type'], 'status' => 'completed', 'user_id' => $request->user()->id]);
            foreach ($data['items'] as $line) { $product = Product::findOrFail($line['product_id']); $lineTotal = $product->sale_price * $line['quantity']; $order->items()->create(['product_id' => $product->id, 'quantity' => $line['quantity'], 'unit_cost_price' => $product->cost_price, 'unit_sale_price' => $product->sale_price, 'discount_amount' => $discountAmount * ($lineTotal / max($raw, 1)), 'final_price' => $lineTotal - ($discountAmount * ($lineTotal / max($raw, 1)))]); }
            if (! empty($data['bank_account_id'])) BankAccount::lockForUpdate()->findOrFail($data['bank_account_id'])->increment('current_balance', $order->final_amount);
            if (! empty($data['customer_id'])) DB::table('customers')->where('id', $data['customer_id'])->update(['purchase_count' => DB::raw('purchase_count + 1'), 'total_spent' => DB::raw('total_spent + '.(float) $order->final_amount)]);
            $this->updateDailyReport($order, $cost, $discountAmount);
            return $order->load('items');
        });
        return $this->success(new OrderResource($order), 'فاکتور فروش ثبت شد.', 201);
    }

    public function cancel(Request $request, int $order)
    {
        $model = DB::transaction(function () use ($request, $order) { $model = Order::with('items')->lockForUpdate()->findOrFail($order); abort_if($model->status === 'cancelled', 422, 'فاکتور قبلاً باطل شده است.'); foreach ($model->items as $item) { $inventory = Inventory::lockForUpdate()->firstOrCreate(['product_id' => $item->product_id], ['current_stock' => 0, 'min_stock_alert' => 0, 'unit' => 'عدد']); $inventory->increment('current_stock', $item->quantity); InventoryLog::create(['product_id' => $item->product_id, 'type' => 'manual_adj', 'quantity' => $item->quantity, 'reference_id' => $model->id, 'user_id' => $request->user()->id, 'description' => 'بازگشت موجودی فاکتور باطل‌شده']); } if ($model->bank_account_id) BankAccount::lockForUpdate()->findOrFail($model->bank_account_id)->decrement('current_balance', $model->final_amount); $model->update(['status' => 'cancelled']); return $model; }); return $this->success(new OrderResource($model), 'فاکتور باطل شد.');
    }

    private function consume(int $productId, float $quantity, int $userId, array &$consumptions): void { $inventory = Inventory::lockForUpdate()->where('product_id', $productId)->first(); abort_if(! $inventory || $inventory->current_stock < $quantity, 422, 'موجودی کالا برای ثبت فروش کافی نیست.'); $inventory->decrement('current_stock', $quantity); InventoryLog::create(['product_id' => $productId, 'type' => 'sale', 'quantity' => -$quantity, 'user_id' => $userId, 'description' => 'خروج از فروش']); $consumptions[] = $productId; }
    private function discount(?int $id, float $raw): float { if (! $id) return 0; $discount = Discount::findOrFail($id); return min($raw, $discount->discount_type === 'percent' ? $raw * ($discount->discount_value / 100) : $discount->discount_value); }
    private function updateDailyReport(Order $order, float $cost, float $discount): void { $report = DailySalesReport::firstOrCreate(['report_date' => now()->toDateString()], ['total_orders' => 0, 'total_gross_sales' => 0, 'total_discounts' => 0, 'total_net_sales' => 0, 'total_cogs' => 0, 'total_expenses' => 0, 'gross_profit' => 0, 'net_profit' => 0]); $report->increment('total_orders'); $report->increment('total_gross_sales', $order->total_raw_amount); $report->increment('total_discounts', $discount); $report->increment('total_net_sales', $order->final_amount); $report->increment('total_cogs', $cost); $report->increment('gross_profit', $order->final_amount - $cost); $report->increment('net_profit', $order->final_amount - $cost); }
}
