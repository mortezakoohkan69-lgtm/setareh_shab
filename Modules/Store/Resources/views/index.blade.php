@extends('layouts.admin')

@section('content')
@php
    $dateColumns = ['created_at', 'updated_at', 'birth_date', 'issue_date', 'due_date', 'expense_date', 'purchase_date', 'order_date', 'start_date', 'end_date', 'report_date'];
    $moneyColumns = ['cost_price', 'sale_price', 'discount_value', 'total_spent', 'initial_balance', 'current_balance', 'amount', 'total_amount', 'total_raw_amount', 'total_discount', 'final_amount', 'total_cost_price', 'unit_cost', 'total_cost', 'unit_cost_price', 'unit_sale_price', 'discount_amount', 'final_price', 'total_gross_sales', 'total_discounts', 'total_net_sales', 'total_cogs', 'total_expenses', 'gross_profit', 'net_profit', 'total_revenue', 'total_profit'];
    $toPersianDigits = static fn (string $value): string => strtr($value, ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']);
    $formatPersianDate = static function ($value, string $column) use ($dateColumns, $toPersianDigits): string {
        if (blank($value)) return '-';
        if (! in_array($column, $dateColumns, true) && ! str_ends_with($column, '_date')) return (string) $value;
        try {
            $format = in_array($column, ['created_at', 'updated_at', 'purchase_date'], true) ? 'Y/m/d H:i' : 'Y/m/d';
            return $toPersianDigits(\Morilog\Jalali\Jalalian::fromDateTime($value)->format($format));
        } catch (\Throwable) {
            return (string) $value;
        }
    };
    $formatMoney = static fn ($value): string => blank($value) ? '-' : number_format((float) $value, 0, '.', ',').' تومان';
@endphp
<div class="flex flex-col md:flex-row md:items-center justify-between gap-3 mb-5">
    <div><h1 class="text-xl font-bold text-slate-800">{{ $resource['title'] }}</h1><p class="text-xs text-slate-500 mt-1">مدیریت و مشاهده اطلاعات {{ $resource['title'] }}</p></div>
    <div class="flex flex-wrap gap-2"><a href="{{ route('admin.export.excel', $resourceKey) }}" class="inline-flex items-center gap-2 rounded-lg bg-emerald-600 px-3 py-2 text-xs font-bold text-white hover:bg-emerald-700"><i class="fas fa-file-excel"></i> Excel</a><a href="{{ route('admin.export.print', $resourceKey) }}" target="_blank" class="inline-flex items-center gap-2 rounded-lg bg-slate-600 px-3 py-2 text-xs font-bold text-white hover:bg-slate-700"><i class="fas fa-print"></i> چاپ / PDF</a><a href="{{ route("admin.{$resourceKey}.create") }}" class="inline-flex items-center justify-center gap-2 rounded-lg bg-indigo-600 px-4 py-2 text-xs font-bold text-white hover:bg-indigo-700"><i class="fas fa-plus"></i> افزودن رکورد</a></div>
</div>
<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
    <form method="GET" class="flex gap-2 mb-4"><input name="search" value="{{ request('search') }}" placeholder="جستجو در جدول..." class="flex-1 rounded-lg border border-slate-200 px-3 py-2 text-xs focus:border-indigo-500 focus:outline-none"><button class="rounded-lg bg-slate-700 px-4 text-xs text-white">جستجو</button></form>
    <div class="overflow-x-auto"><table class="data-table w-full text-right text-xs"><thead><tr class="border-b border-slate-200 text-slate-500">
        @foreach ($resource['columns'] as $column)<th class="whitespace-nowrap px-3 py-3 font-bold">{{ collect($resource['fields'])->firstWhere('name', $column)['label'] ?? $column }}</th>@endforeach<th class="px-3 py-3">عملیات</th>
    </tr></thead><tbody>
        @forelse ($rows as $row)
            <tr class="border-b border-slate-100 hover:bg-slate-50">
                @foreach ($resource['columns'] as $column)<td class="whitespace-nowrap px-3 py-3 text-slate-700">{{ $resourceKey === 'categories' && $column === 'type' ? ($row->{$column} === 'product' ? 'محصول' : 'ماده اولیه') : (is_bool($row->{$column}) ? ($row->{$column} ? 'بله' : 'خیر') : (in_array($column, $moneyColumns, true) ? $formatMoney($row->{$column}) : $formatPersianDate($row->{$column}, $column))) }}</td>@endforeach
                <td class="whitespace-nowrap px-3 py-3"><a href="{{ route("admin.{$resourceKey}.edit", $row->id) }}" class="text-indigo-600 hover:text-indigo-800 ml-3"><i class="fas fa-pen"></i> ویرایش</a><form action="{{ route("admin.{$resourceKey}.destroy", $row->id) }}" method="POST" class="inline" onsubmit="return confirm('آیا از حذف این رکورد مطمئن هستید؟')">@csrf @method('DELETE')<button class="text-rose-600 hover:text-rose-800"><i class="fas fa-trash"></i> حذف</button></form></td>
            </tr>
        @empty<tr><td colspan="{{ count($resource['columns']) + 1 }}" class="px-3 py-10 text-center text-slate-400">رکوردی برای نمایش وجود ندارد.</td></tr>@endforelse
    </tbody></table></div>
    <div class="mt-4">{{ $rows->links() }}</div>
</div>
@endsection
