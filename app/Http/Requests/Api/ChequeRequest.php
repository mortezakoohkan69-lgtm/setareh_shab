<?php
namespace App\Http\Requests\Api;
class ChequeRequest extends ApiRequest { public function rules(): array { return ['type' => ['required','in:received,paid'], 'serial_number' => ['required','string','max:50'], 'amount' => ['required','numeric','min:0'], 'payee_payor' => ['required','string','max:150'], 'issue_date' => ['required','date'], 'due_date' => ['required','date'], 'status' => ['sometimes','in:pending,cleared,bounced'], 'bank_account_id' => ['nullable','exists:bank_accounts,id'], 'description' => ['nullable','string']]; } }
