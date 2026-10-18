<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Requests\Api\InventoryAdjustmentRequest;
use App\Http\Resources\Api\InventoryResource;
use App\Models\Inventory;
use App\Models\InventoryLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class InventoryController extends ApiController { public function index(Request $request) { $query = Inventory::with('product'); if ($request->boolean('low_stock')) $query->whereColumn('current_stock', '<=', 'min_stock_alert'); return $this->success(InventoryResource::collection($query->paginate(20))->response()->getData(true)['data'], 'موجودی دریافت شد.'); } public function logs(int $product) { return $this->success(InventoryLog::where('product_id', $product)->latest()->paginate(20), 'لاگ انبار دریافت شد.'); } public function adjust(InventoryAdjustmentRequest $request) { $data = $request->validated(); $inventory = DB::transaction(function () use ($data, $request) { $item = Inventory::lockForUpdate()->firstOrCreate(['product_id' => $data['product_id']], ['current_stock' => 0, 'min_stock_alert' => 0, 'unit' => $data['unit']]); $item->increment('current_stock', $data['quantity']); InventoryLog::create(['product_id' => $data['product_id'], 'type' => 'manual_adj', 'quantity' => $data['quantity'], 'user_id' => $request->user()->id, 'description' => $data['description'] ?? null]); return $item->refresh(); }); return $this->success(new InventoryResource($inventory), 'موجودی اصلاح شد.'); } }
