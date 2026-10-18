<?php
namespace App\Http\Requests\Api;
class ProductRequest extends ApiRequest { public function rules(): array { return ['category_id' => ['required','exists:categories,id'], 'name' => ['required','string','max:150'], 'sku' => ['nullable','string','max:50'], 'cost_price' => ['required','numeric','min:0'], 'sale_price' => ['required','numeric','min:0'], 'discount_type' => ['nullable','in:none,percent,fixed'], 'discount_value' => ['nullable','numeric','min:0'], 'is_active' => ['sometimes','boolean'], 'is_material' => ['sometimes','boolean']]; } }
