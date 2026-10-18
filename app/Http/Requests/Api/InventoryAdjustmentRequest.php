<?php
namespace App\Http\Requests\Api;
class InventoryAdjustmentRequest extends ApiRequest { public function rules(): array { return ['product_id' => ['required','exists:products,id'], 'quantity' => ['required','numeric'], 'unit' => ['required','string','max:20'], 'description' => ['nullable','string']]; } }
