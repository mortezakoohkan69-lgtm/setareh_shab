<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Requests\Api\DiscountRequest;
use App\Http\Resources\Api\DiscountResource;
use App\Models\Discount;
use Illuminate\Http\Request;
class DiscountController extends CrudController { protected string $modelClass = Discount::class; protected string $resourceClass = DiscountResource::class; protected string $requestClass = DiscountRequest::class; public function syncProducts(Request $request, int $discount) { $data = $request->validate(['product_ids' => ['required','array'], 'product_ids.*' => ['exists:products,id']]); $model = Discount::findOrFail($discount); $model->products()->sync($data['product_ids']); return $this->success($model->load('products'), 'محصولات تخفیف به‌روزرسانی شد.'); } }
