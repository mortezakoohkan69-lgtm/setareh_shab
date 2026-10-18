<?php
namespace App\Http\Requests\Api;
class PurchaseRequest extends ApiRequest { public function rules(): array { return ['supplier_name' => ['required','string','max:150'], 'bank_account_id' => ['nullable','exists:bank_accounts,id'], 'purchase_date' => ['required','date'], 'items' => ['required','array','min:1'], 'items.*.product_id' => ['required','exists:products,id'], 'items.*.quantity' => ['required','numeric','gt:0'], 'items.*.unit_cost' => ['required','numeric','min:0']]; } }
