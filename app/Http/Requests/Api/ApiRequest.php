<?php

namespace App\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Morilog\Jalali\Jalalian;
use Throwable;

abstract class ApiRequest extends FormRequest
{
    public function authorize(): bool { return true; }

    protected function prepareForValidation(): void
    {
        $this->merge(self::normalizeDates($this->all()));
    }

    public static function normalizeDates(array $input): array
    {
        $dateFields = ['birth_date', 'issue_date', 'due_date', 'expense_date', 'purchase_date', 'order_date', 'start_date', 'end_date', 'report_date', 'period_start', 'period_end'];

        foreach ($dateFields as $field) {
            if (! isset($input[$field]) || ! is_string($input[$field]) || ! str_contains($input[$field], '/')) {
                continue;
            }

            try {
                $format = str_contains($input[$field], ':') ? 'Y/m/d H:i' : 'Y/m/d';
                $input[$field] = Jalalian::fromFormat($format, $input[$field])->toCarbon()->format(str_contains($input[$field], ':') ? 'Y-m-d H:i:s' : 'Y-m-d');
            } catch (Throwable) {
                // Validator reports the original value when the date is invalid.
            }
        }

        return $input;
    }

    public function messages(): array
    {
        return [
            'required' => 'وارد کردن فیلد :attribute الزامی است.',
            'exists' => 'مقدار انتخاب‌شده برای :attribute معتبر نیست.',
            'numeric' => 'فیلد :attribute باید عددی باشد.',
            'integer' => 'فیلد :attribute باید عدد صحیح باشد.',
            'date' => 'فرمت تاریخ :attribute معتبر نیست.',
            'unique' => 'مقدار :attribute قبلاً ثبت شده است.',
            'in' => 'مقدار انتخاب‌شده برای :attribute معتبر نیست.',
        ];
    }
}
