<?php
namespace App\Http\Requests\Api;
class OrderRequest extends ApiRequest { public function rules(): array { return ['customer_id' => ['nullable','exists:customers,id'], 'bank_account_id' => ['nullable','exists:bank_accounts,id'], 'applied_discount_id' => ['nullable','exists:discounts,id'], 'payment_type' => ['required','in:card,cash,mixed'], 'items' => ['required','array','min:1'], 'items.*.product_id' => ['required','exists:products,id'], 'items.*.quantity' => ['required','integer','min:1']]; } }
