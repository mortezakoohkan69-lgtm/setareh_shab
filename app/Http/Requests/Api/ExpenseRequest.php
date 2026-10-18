<?php
namespace App\Http\Requests\Api;
class ExpenseRequest extends ApiRequest { public function rules(): array { return ['title' => ['required','string','max:150'], 'amount' => ['required','numeric','min:0'], 'bank_account_id' => ['nullable','exists:bank_accounts,id'], 'expense_date' => ['required','date'], 'description' => ['nullable','string']]; } }
