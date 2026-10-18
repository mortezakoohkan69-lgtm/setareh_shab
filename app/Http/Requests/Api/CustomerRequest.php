<?php
namespace App\Http\Requests\Api;
class CustomerRequest extends ApiRequest { public function rules(): array { return ['first_name' => ['required','string','max:100'], 'last_name' => ['required','string','max:100'], 'mobile' => ['required','string','max:15'], 'birth_date' => ['nullable','date']]; } }
