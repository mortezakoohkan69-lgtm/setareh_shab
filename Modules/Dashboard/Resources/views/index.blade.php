@extends('layouts.admin')

@section('content')
@php($stats = $stats ?? [])
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="bg-gradient-to-r from-amber-500 to-orange-500 rounded-xl p-4 text-white shadow-sm transition hover:shadow-md">
        <div class="flex items-center justify-between"><div><h5 class="text-xl font-bold">{{ number_format($stats['products_count'] ?? 0) }}</h5><p class="text-xs opacity-90 mt-1">تعداد کل محصولات / مواد اولیه</p></div><div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center text-lg"><i class="fas fa-box"></i></div></div>
        <div class="mt-4 pt-2 border-t border-white/20 text-[10px] flex justify-between items-center"><span><i class="fas fa-clock ml-1"></i> به‌روزرسانی: {{ $stats['last_update'] ?? '-' }}</span><a href="{{ route('admin.products.index') }}" class="underline hover:opacity-80">مشاهده همه</a></div>
    </div>
    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 rounded-xl p-4 text-white shadow-sm transition hover:shadow-md">
        <div class="flex items-center justify-between"><div><h5 class="text-xl font-bold">{{ number_format($stats['categories_count'] ?? 0) }}</h5><p class="text-xs opacity-90 mt-1">دسته‌بندی‌های فعال</p></div><div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center text-lg"><i class="fas fa-tags"></i></div></div>
        <div class="mt-4 pt-2 border-t border-white/20 text-[10px] flex justify-between items-center"><span><i class="fas fa-clock ml-1"></i> به‌روزرسانی: {{ $stats['last_update'] ?? '-' }}</span><a href="{{ route('admin.categories.index') }}" class="underline hover:opacity-80">مشاهده همه</a></div>
    </div>
    <div class="bg-gradient-to-r from-rose-500 to-pink-600 rounded-xl p-4 text-white shadow-sm transition hover:shadow-md">
        <div class="flex items-center justify-between"><div><h5 class="text-xl font-bold">{{ number_format($stats['cheques_count'] ?? 0) }}</h5><p class="text-xs opacity-90 mt-1">چک‌های ثبت‌شده</p></div><div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center text-lg"><i class="fas fa-money-check-alt"></i></div></div>
        <div class="mt-4 pt-2 border-t border-white/20 text-[10px] flex justify-between items-center"><span><i class="fas fa-clock ml-1"></i> به‌روزرسانی: {{ $stats['last_update'] ?? '-' }}</span><a href="{{ route('admin.cheques.index') }}" class="underline hover:opacity-80">مشاهده همه</a></div>
    </div>
    <div class="bg-gradient-to-r from-cyan-500 to-blue-600 rounded-xl p-4 text-white shadow-sm transition hover:shadow-md">
        <div class="flex items-center justify-between"><div><h5 class="text-xl font-bold">{{ number_format($stats['recipes_count'] ?? 0) }}</h5><p class="text-xs opacity-90 mt-1">فرمول‌های ساخت و ترکیب</p></div><div class="w-10 h-10 rounded-lg bg-white/20 flex items-center justify-center text-lg"><i class="fas fa-flask"></i></div></div>
        <div class="mt-4 pt-2 border-t border-white/20 text-[10px] flex justify-between items-center"><span><i class="fas fa-clock ml-1"></i> به‌روزرسانی: {{ $stats['last_update'] ?? '-' }}</span><a href="{{ route('admin.product-recipes.index') }}" class="underline hover:opacity-80">مشاهده همه</a></div>
    </div>
</div>

<div class="bg-white rounded-xl shadow-sm border border-slate-200/80 p-5 mb-6">
    <div class="border-b border-slate-100 pb-3 mb-4"><h5 class="font-bold text-slate-800 text-sm">دسترسی سریع به منوها</h5><p class="text-xs text-slate-500 mt-0.5">میانبر بخش‌های پرکاربرد سیستم برای مدیریت سریع‌تر</p></div>
    <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-6 gap-3">
        @foreach ([['admin.products.create', 'افزودن محصول', 'fa-plus', 'text-indigo-600'], ['admin.categories.create', 'دسته‌بندی جدید', 'fa-folder-plus', 'text-emerald-600'], ['admin.cheques.create', 'ثبت چک جدید', 'fa-file-invoice-dollar', 'text-rose-600'], ['admin.inventory.index', 'موجودی انبار', 'fa-warehouse', 'text-amber-600'], ['admin.product-recipes.create', 'تعریف فرمول ساخت', 'fa-flask', 'text-cyan-600'], ['admin.inventory-logs.index', 'کاردکس انبار', 'fa-history', 'text-purple-600']] as [$route, $label, $icon, $color])
            <a href="{{ route($route) }}" class="flex flex-col items-center justify-center p-3 rounded-lg border border-slate-100 bg-slate-50 hover:bg-indigo-50 hover:border-indigo-200 transition group text-center"><div class="w-9 h-9 rounded-full bg-white shadow-sm border border-slate-200 flex items-center justify-center mb-2 group-hover:border-indigo-300 {{ $color }}"><i class="fas {{ $icon }} text-xs"></i></div><span class="text-xs font-semibold text-slate-700 group-hover:text-indigo-600">{{ $label }}</span></a>
        @endforeach
    </div>
</div>
<div class="bg-white rounded-xl shadow-sm border border-slate-200/80 p-5"><h5 class="font-bold text-slate-800 text-sm">وضعیت سامانه</h5><p class="text-xs text-slate-500 mt-2">آمار داشبورد مستقیماً از اطلاعات ثبت‌شده در دیتابیس خوانده می‌شود.</p></div>
@endsection
