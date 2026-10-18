@php($flashMessages = ['success' => ['bg-emerald-50 border-emerald-200 text-emerald-800', 'fa-check-circle'], 'error' => ['bg-rose-50 border-rose-200 text-rose-800', 'fa-circle-exclamation'], 'warning' => ['bg-amber-50 border-amber-200 text-amber-800', 'fa-triangle-exclamation'], 'status' => ['bg-indigo-50 border-indigo-200 text-indigo-800', 'fa-circle-info']])
<div class="fixed top-20 left-4 z-50 w-[min(22rem,calc(100vw-2rem))] space-y-2" aria-live="polite">
    @foreach ($flashMessages as $key => [$classes, $icon])
        @if (session($key))
            <div x-data="{ show: true }" x-show="show" x-transition class="flex items-start gap-2 rounded-lg border px-4 py-3 text-xs shadow-lg {{ $classes }}">
                <i class="fas {{ $icon }} mt-0.5"></i><span class="flex-1">{{ session($key) }}</span>
                <button type="button" @click="show = false" aria-label="بستن"><i class="fas fa-times"></i></button>
            </div>
        @endif
    @endforeach
    @if ($errors->any())
        <div x-data="{ show: true }" x-show="show" x-transition class="flex items-start gap-2 rounded-lg border bg-rose-50 border-rose-200 px-4 py-3 text-xs text-rose-800 shadow-lg">
            <i class="fas fa-circle-exclamation mt-0.5"></i><span class="flex-1">لطفاً خطاهای فرم را بررسی کنید.</span>
            <button type="button" @click="show = false" aria-label="بستن"><i class="fas fa-times"></i></button>
        </div>
    @endif
</div>
