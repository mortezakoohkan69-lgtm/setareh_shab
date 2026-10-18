@extends('layouts.admin')

@section('content')
@if ($resourceKey === 'product-recipes')
    @php
        $productValue = old('product_id', $row->product_id ?? '');
        $materialValue = old('material_id', $row->material_id ?? '');
        $quantityValue = old('quantity', $row->quantity ?? '');
        $unitValue = old('unit', $row->unit ?? '');
        $productOptions = isset($options) && (is_array($options) || is_object($options)) && isset($options['product_id']) && (is_array($options['product_id']) || is_object($options['product_id'])) ? $options['product_id'] : [];
        $materialOptions = isset($options) && (is_array($options) || is_object($options)) && isset($options['material_id']) && (is_array($options['material_id']) || is_object($options['material_id'])) ? $options['material_id'] : [];
    @endphp
    <div class="mb-5"><h1 class="text-base font-bold text-slate-800">{{ $row ? 'ویرایش فرمول ساخت' : 'تعریف فرمول ساخت جدید' }}</h1><p class="text-xs text-slate-500 mt-1">مشخص کردن مواد اولیه و مقدار مصرفی برای تولید محصول</p></div>

    <form method="POST" action="{{ $row ? route("admin.{$resourceKey}.update", $row->id) : url('admin/product-recipes') }}" class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        @csrf
        @if ($row) @method('PUT') @endif
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <div>
                <label for="product_id" class="mb-1.5 block text-xs font-bold text-slate-700">محصول <span class="text-rose-500">*</span></label>
                <select id="product_id" name="product_id" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs focus:border-indigo-500 focus:outline-none" required>
                    <option value="">انتخاب کنید</option>
                    @foreach ($productOptions as $id => $label)<option value="{{ $id }}" @selected((string) $productValue === (string) $id)>{{ $label }}</option>@endforeach
                </select>
                @error('product_id')<p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="material_id" class="mb-1.5 block text-xs font-bold text-slate-700">ماده اولیه <span class="text-rose-500">*</span></label>
                <select id="material_id" name="material_id" class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs focus:border-indigo-500 focus:outline-none" required>
                    <option value="">انتخاب کنید</option>
                    @foreach ($materialOptions as $id => $label)<option value="{{ $id }}" @selected((string) $materialValue === (string) $id)>{{ $label }}</option>@endforeach
                </select>
                @error('material_id')<p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="quantity" class="mb-1.5 block text-xs font-bold text-slate-700">مقدار <span class="text-rose-500">*</span></label>
                <input id="quantity" type="number" name="quantity" value="{{ $quantityValue }}" step="0.001" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs focus:border-indigo-500 focus:outline-none" required>
                @error('quantity')<p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>@enderror
            </div>
            <div>
                <label for="unit" class="mb-1.5 block text-xs font-bold text-slate-700">واحد <span class="text-rose-500">*</span></label>
                <input id="unit" type="text" name="unit" value="{{ $unitValue }}" class="w-full rounded-lg border border-slate-300 px-3 py-2 text-xs focus:border-indigo-500 focus:outline-none" required>
                @error('unit')<p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>@enderror
            </div>
        </div>
        <div class="mt-6 flex gap-2"><button type="submit" class="rounded-lg bg-indigo-600 px-5 py-2 text-xs font-bold text-white hover:bg-indigo-700 transition">ذخیره</button><a href="{{ url('admin/product-recipes') }}" class="rounded-lg bg-slate-100 px-5 py-2 text-xs text-slate-700 hover:bg-slate-200 transition">انصراف</a></div>
    </form>
@else
    <div class="mb-5"><h1 class="text-xl font-bold text-slate-800">{{ $row ? 'ویرایش' : 'افزودن' }} {{ $resource['title'] }}</h1><p class="text-xs text-slate-500 mt-1">اطلاعات فرم را با دقت وارد کنید.</p></div>
    <form method="POST" action="{{ $row ? route("admin.{$resourceKey}.update", $row->id) : route("admin.{$resourceKey}.store") }}" class="bg-white rounded-xl border border-slate-200 shadow-sm p-5">
        @csrf @if ($row) @method('PUT') @endif
        @php
            $rawFields = isset($fields) && (is_array($fields) || is_object($fields))
                ? $fields
                : (isset($resource) && is_array($resource) && isset($resource['fields']) ? $resource['fields'] : []);
            if ((is_array($rawFields) || is_object($rawFields)) && count((array) $rawFields) === 0 && isset($resource['fields']) && is_array($resource['fields']) && count($resource['fields']) > 0) {
                $rawFields = $resource['fields'];
            }
            if ((is_array($rawFields) || is_object($rawFields)) && count((array) $rawFields) === 0 && ($resourceKey ?? null) === 'cheques') {
                $rawFields = [
                    ['name' => 'serial_number', 'label' => 'شماره چک', 'type' => 'text', 'required' => true],
                    ['name' => 'amount', 'label' => 'مبلغ (ریال/تومان)', 'type' => 'number', 'required' => true],
                    ['name' => 'issue_date', 'label' => 'تاریخ صدور', 'type' => 'date', 'required' => true],
                    ['name' => 'due_date', 'label' => 'تاریخ سررسید', 'type' => 'date', 'required' => true],
                    ['name' => 'payee_payor', 'label' => 'دریافت‌کننده / پرداخت‌کننده', 'type' => 'text', 'required' => true],
                    ['name' => 'status', 'label' => 'وضعیت', 'type' => 'select', 'options' => ['pending' => 'در انتظار', 'cleared' => 'وصول شده', 'bounced' => 'برگشتی'], 'required' => true],
                    ['name' => 'description', 'label' => 'توضیحات', 'type' => 'textarea', 'required' => false],
                ];
            }
            $formFields = [];
            foreach ((array) $rawFields as $rawField) {
                $normalizedField = is_object($rawField) ? get_object_vars($rawField) : $rawField;
                if (is_array($normalizedField)) {
                    $normalizedField['name'] = $normalizedField['name'] ?? $normalizedField['key'] ?? $normalizedField['field_name'] ?? null;
                    if (filled($normalizedField['name'])) $formFields[] = $normalizedField;
                }
            }
            $formRow = isset($row) && is_object($row) ? $row : null;
        @endphp
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @if (is_array($formFields) || is_object($formFields))
                @foreach ($formFields as $field)
                    @if (is_array($field) && isset($field['name']) && empty($field['computed']))
                        @php
                            $fieldName = $field['name'];
                            $fieldType = $field['type'] ?? 'text';
                            $campaignFieldId = match ($fieldName) {
                                'discount_value' => 'field_amount',
                                'min_purchases' => 'field_min_purchase_count',
                                'start_time' => 'field_start_time',
                                'end_time' => 'field_end_time',
                                'start_date' => 'field_start_date',
                                'end_date' => 'field_end_date',
                                'days_of_week' => 'field_days_of_week',
                                default => null,
                            };
                            $isCampaignField = ($resourceKey ?? null) === 'discounts' && $campaignFieldId !== null;
                            $hasFieldError = $errors->has($fieldName);
                            $fieldBorderClass = $hasFieldError ? ' border-rose-500 focus:border-rose-500' : ' border-slate-300 focus:border-indigo-500';
                            $isDateField = in_array($fieldType, ['date', 'datetime-local'], true);
                            $value = old($fieldName, $formRow?->{$fieldName} ?? ($field['default'] ?? ''));
                            if ($isDateField && filled($value) && ! str_contains((string) $value, '/')) {
                                try { $value = \Morilog\Jalali\Jalalian::fromDateTime($value)->format($fieldType === 'datetime-local' ? 'Y/m/d H:i' : 'Y/m/d'); } catch (\Throwable) { }
                            }
                        @endphp
                        <div @if ($isCampaignField) id="{{ $campaignFieldId }}" @endif class="{{ $fieldType === 'textarea' ? 'md:col-span-2 lg:col-span-3 ' : '' }}{{ $isCampaignField ? 'campaign-field hidden' : '' }}">
                            @if ($fieldType === 'checkbox')
                                <label class="flex items-center gap-2 mt-7 text-xs text-slate-700"><input type="checkbox" name="{{ $fieldName }}" value="1" @checked($value) class="rounded border-slate-300 text-indigo-600"> {{ $field['label'] ?? $fieldName }}</label>
                            @else
                                <label for="{{ $fieldName }}" class="mb-1.5 block text-xs font-bold text-slate-700">{{ $field['label'] ?? $fieldName }} @if (! empty($field['required']))<span class="text-rose-500">*</span>@endif</label>
                                @if ($fieldType === 'textarea')
                                    <textarea id="{{ $fieldName }}" name="{{ $fieldName }}" rows="3" class="w-full rounded-lg{{ $fieldBorderClass }} px-3 py-2 text-xs focus:outline-none">{{ $value }}</textarea>
                                @elseif ($fieldType === 'select')
                                    @php($fieldOptions = isset($field['options']) && (is_array($field['options']) || is_object($field['options'])) ? $field['options'] : (isset($options) && is_array($options) ? ($options[$fieldName] ?? []) : []))
                                    <select id="{{ $fieldName }}" name="{{ $fieldName }}" class="w-full rounded-lg{{ $fieldBorderClass }} bg-white px-3 py-2 text-xs focus:outline-none" @required(! empty($field['required']))><option value="">انتخاب کنید</option>@foreach ($fieldOptions as $optionValue => $optionLabel)<option value="{{ $optionValue }}" @selected((string) $value === (string) $optionValue)>{{ $optionLabel }}</option>@endforeach</select>
                                @else
                                    <input id="{{ $fieldName }}" type="{{ $isDateField ? 'text' : $fieldType }}" name="{{ $fieldName }}" value="{{ $value }}" step="{{ $field['step'] ?? 'any' }}" class="w-full rounded-lg{{ $fieldBorderClass }} px-3 py-2 text-xs focus:outline-none{{ $isDateField ? ' persian-datepicker' : '' }}" @if ($isDateField || $fieldName === 'days_of_week') placeholder="{{ $fieldName === 'days_of_week' ? 'مثلاً: شنبه، دوشنبه، چهارشنبه' : ($fieldType === 'datetime-local' ? '1403/01/01 12:00' : '1403/01/01') }}" autocomplete="off" @endif @required(! empty($field['required']))>
                                @endif
                            @endif
                            @error($fieldName)<p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>@enderror
                        </div>
                    @endif
                @endforeach
            @endif
        </div>
        @if (($resourceKey ?? null) === 'discounts')
            <div id="field_category_ids" class="w-full mt-4">
                <label for="category_ids" class="mb-1.5 block text-xs font-bold text-slate-700">انتخاب دسته‌بندی‌ها (امکان چندانتخابی)</label>
                <select id="category_ids" name="category_ids[]" class="tom-select w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs focus:border-indigo-500 focus:outline-none" multiple placeholder="یک یا چند دسته‌بندی را انتخاب کنید...">
                    @foreach (($categories ?? []) as $category)<option value="{{ $category->id }}" @selected(in_array($category->id, (array) old('category_ids', $itemCategoryIds ?? [])))>{{ $category->title }}</option>@endforeach
                </select>
            </div>
            <div id="field_product_ids" class="campaign-field hidden mt-4">
                <label for="product_ids" class="mb-1.5 block text-xs font-bold text-slate-700">انتخاب محصولات شامل تخفیف <span class="text-rose-500">*</span></label>
                <select id="product_ids" name="product_ids[]" multiple class="w-full rounded-lg border border-slate-300 bg-white px-3 py-2 text-xs focus:border-indigo-500 focus:outline-none">
                    @foreach (($categoriesWithProducts ?? []) as $category)
                        <optgroup label="دسته‌بندی: {{ $category->title }}">
                            @foreach ($category->products as $product)<option value="{{ $product->id }}" @selected(in_array($product->id, (array) old('product_ids', $selectedProductIds ?? [])))>{{ $product->name }}</option>@endforeach
                        </optgroup>
                    @endforeach
                </select>
                @error('product_ids')<p class="mt-1 text-[11px] text-rose-600">{{ $message }}</p>@enderror
            </div>
            @push('scripts')
                <script>
                    document.addEventListener('DOMContentLoaded', function () {
                        const campaignSelect = document.getElementById('type') || document.getElementById('campaign_type');
                        const campaignFieldsMap = { birthday: ['field_amount'], purchase_count: ['field_amount', 'field_min_purchase_count'], happy_hour: ['field_amount', 'field_start_time', 'field_end_time', 'field_days_of_week'], event: ['field_amount', 'field_start_date', 'field_end_date'], product_specific: ['field_amount', 'field_product_ids'] };
                        function handleCampaignChange() {
                            const selectedType = campaignSelect ? campaignSelect.value : '';
                            document.querySelectorAll('.campaign-field').forEach((element) => { element.classList.add('hidden'); element.querySelectorAll('input, select').forEach((input) => input.removeAttribute('required')); });
                            (campaignFieldsMap[selectedType] || []).forEach((fieldId) => { const element = document.getElementById(fieldId); if (element) { element.classList.remove('hidden'); element.querySelectorAll('input, select').forEach((input) => input.setAttribute('required', 'required')); } });
                        }
                        if (campaignSelect) { campaignSelect.addEventListener('change', handleCampaignChange); handleCampaignChange(); }
                    });
                </script>
            @endpush
        @endif
        <div class="mt-6 flex gap-2"><button class="rounded-lg bg-indigo-600 px-5 py-2 text-xs font-bold text-white hover:bg-indigo-700">ذخیره</button><a href="{{ route("admin.{$resourceKey}.index") }}" class="rounded-lg bg-slate-100 px-5 py-2 text-xs text-slate-700 hover:bg-slate-200">انصراف</a></div>
    </form>
@endif
@endsection
