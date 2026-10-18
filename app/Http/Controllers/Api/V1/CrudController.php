<?php

namespace App\Http\Controllers\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\Api\ApiRequest;

abstract class CrudController extends ApiController
{
    protected string $modelClass;
    protected string $resourceClass;
    protected string $requestClass;

    public function index(Request $request)
    {
        $query = ($this->modelClass)::query();
        foreach ($request->only(['type', 'status', 'is_active', 'product_id']) as $column => $value) {
            $query->where($column, $value);
        }
        return $this->success(($this->resourceClass)::collection($query->latest()->paginate(20))->response()->getData(true)['data'], 'فهرست با موفقیت دریافت شد.');
    }

    public function store($request)
    {
        $data = $this->validated($request);
        return $this->success(new ($this->resourceClass)(($this->modelClass)::create($data)), 'رکورد با موفقیت ایجاد شد.', 201);
    }

    public function show(int $id)
    {
        return $this->success(new ($this->resourceClass)(($this->modelClass)::findOrFail($id)), 'رکورد با موفقیت دریافت شد.');
    }

    public function update(Request $request, int $id)
    {
        $model = ($this->modelClass)::findOrFail($id);
        $model->update($this->validated($request, true));
        return $this->success(new ($this->resourceClass)($model->refresh()), 'رکورد با موفقیت ویرایش شد.');
    }

    public function destroy(int $id)
    {
        ($this->modelClass)::findOrFail($id)->delete();
        return $this->success(null, 'رکورد با موفقیت حذف شد.');
    }

    protected function validated(Request $request, bool $update = false): array
    {
        $formRequest = new ($this->requestClass)();
        $rules = $formRequest->rules();
        if ($update) {
            foreach ($rules as $key => $rule) {
                $rules[$key] = array_values(array_filter((array) $rule, fn ($item) => $item !== 'required'));
            }
        }
        return Validator::make(ApiRequest::normalizeDates($request->all()), $rules, $formRequest->messages())->validate();
    }
}
