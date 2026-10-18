<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Requests\Api\ProductRequest;
use App\Http\Resources\Api\ProductResource;
use App\Models\Product;
use Illuminate\Http\Request;
class ProductController extends CrudController
{
    protected string $modelClass = Product::class; protected string $resourceClass = ProductResource::class; protected string $requestClass = ProductRequest::class;
    public function getRecipes(int $product) { return $this->success(Product::findOrFail($product)->recipes()->with('material')->get(), 'فرمول ساخت دریافت شد.'); }
    public function syncRecipes(Request $request, int $product) { $data = $request->validate(['recipes' => ['required','array'], 'recipes.*.material_id' => ['required','exists:products,id'], 'recipes.*.quantity' => ['required','numeric','gt:0'], 'recipes.*.unit' => ['required','string','max:20']]); $model = Product::findOrFail($product); $model->recipes()->delete(); $model->recipes()->createMany($data['recipes']); return $this->success($model->recipes()->with('material')->get(), 'فرمول ساخت ذخیره شد.'); }
}
