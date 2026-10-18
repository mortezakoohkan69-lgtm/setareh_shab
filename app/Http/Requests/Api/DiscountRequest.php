<?php
namespace App\Http\Requests\Api;
class DiscountRequest extends ApiRequest { public function rules(): array { return ['title' => ['required','string','max:150'], 'type' => ['required','in:birthday,purchase_count,happy_hour,event,product_specific'], 'discount_type' => ['required','in:percent,fixed_amount'], 'discount_value' => ['required','numeric','min:0'], 'min_purchases' => ['nullable','integer','min:0'], 'start_date' => ['nullable','date'], 'end_date' => ['nullable','date'], 'is_active' => ['sometimes','boolean']]; } }
