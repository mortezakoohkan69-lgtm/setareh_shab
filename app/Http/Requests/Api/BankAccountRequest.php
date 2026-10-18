<?php
namespace App\Http\Requests\Api;
class BankAccountRequest extends ApiRequest { public function rules(): array { return ['title' => ['required','string','max:100'], 'bank_name' => ['required','string','max:100'], 'account_number' => ['nullable','string','max:50'], 'card_number' => ['nullable','string','max:16'], 'iban' => ['nullable','string','max:30'], 'initial_balance' => ['nullable','numeric'], 'current_balance' => ['nullable','numeric'], 'account_type' => ['required','in:main,sub'], 'is_active' => ['sometimes','boolean']]; } }
