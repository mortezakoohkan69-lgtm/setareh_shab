<?php
namespace App\Http\Requests\Api;
class CategoryRequest extends ApiRequest { public function rules(): array { return ['title' => ['required','string','max:100'], 'type' => ['required','in:product,material'], 'is_active' => ['sometimes','boolean']]; } }
