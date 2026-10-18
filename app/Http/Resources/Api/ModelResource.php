<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Morilog\Jalali\Jalalian;
use Throwable;

class ModelResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $attributes = $this->resource?->toArray() ?? [];

        return $this->translateDates($attributes);
    }

    protected function translateDates(array $attributes): array
    {
        foreach ($attributes as $key => $value) {
            if (is_array($value)) {
                $attributes[$key] = $this->translateDates($value);
                continue;
            }

            if (! $this->isDateKey((string) $key) || blank($value)) {
                continue;
            }

            try {
                $jalali = Jalalian::fromDateTime($value);
                $attributes[$key.'_fa'] = $jalali->format($this->isDateTimeKey((string) $key) ? 'Y/m/d H:i' : 'Y/m/d');
            } catch (Throwable) {
                // Non-date values with date-like names are left untouched.
            }
        }

        return $attributes;
    }

    private function isDateKey(string $key): bool
    {
        return str_ends_with($key, '_at') || str_ends_with($key, '_date') || in_array($key, ['issue_date', 'due_date'], true);
    }

    private function isDateTimeKey(string $key): bool
    {
        return str_ends_with($key, '_at') || in_array($key, ['purchase_date', 'start_date', 'end_date'], true);
    }
}
