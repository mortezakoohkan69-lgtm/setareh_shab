<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\Api\PurchaseRequest;
use App\Http\Resources\Api\PurchaseResource;
use App\Models\BankAccount;
use App\Models\Inventory;
use App\Models\InventoryLog;
use App\Models\Product;
use App\Models\Purchase;
use Illuminate\Support\Facades\DB;

class PurchaseController extends CrudController
{
    protected string $modelClass = Purchase::class;
    protected string $resourceClass = PurchaseResource::class;
    protected string $requestClass = PurchaseRequest::class;

    public function store($request)
    {
        $data = $this->validated($request);
        $purchase = DB::transaction(function () use ($data, $request) {
            $total = collect($data['items'])->sum(fn (array $item) => $item['quantity'] * $item['unit_cost']);
            $purchase = Purchase::create(['supplier_name' => $data['supplier_name'], 'bank_account_id' => $data['bank_account_id'] ?? null, 'total_amount' => $total, 'purchase_date' => $data['purchase_date'], 'user_id' => $request->user()->id]);
            foreach ($data['items'] as $item) {
                $purchase->items()->create(['product_id' => $item['product_id'], 'quantity' => $item['quantity'], 'unit_cost' => $item['unit_cost'], 'total_cost' => $item['quantity'] * $item['unit_cost']]);
                $inventory = Inventory::lockForUpdate()->firstOrCreate(['product_id' => $item['product_id']], ['current_stock' => 0, 'min_stock_alert' => 0, 'unit' => 'عدد']);
                $inventory->increment('current_stock', $item['quantity']);
                InventoryLog::create(['product_id' => $item['product_id'], 'type' => 'purchase', 'quantity' => $item['quantity'], 'reference_id' => $purchase->id, 'user_id' => $request->user()->id, 'description' => 'ورود از فاکتور خرید']);
            }
            if (! empty($data['bank_account_id'])) BankAccount::lockForUpdate()->findOrFail($data['bank_account_id'])->decrement('current_balance', $total);
            return $purchase->load('items');
        });
        return $this->success(new PurchaseResource($purchase), 'فاکتور خرید ثبت شد.', 201);
    }
}
