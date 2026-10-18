<?php
namespace App\Http\Resources\Api;

use Illuminate\Http\Request;

class CategoryResource extends ModelResource
{
    public function toArray(Request $request): array
    {
        $data = parent::toArray($request);
        $data['type_fa'] = match ($data['type'] ?? null) {
            'product' => 'محصول',
            'material' => 'ماده اولیه',
            default => $data['type'] ?? null,
        };
        $data['type'] = $data['type_fa'];

        return $data;
    }
}
