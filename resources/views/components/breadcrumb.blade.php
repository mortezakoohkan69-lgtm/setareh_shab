@props(['items' => []])

@if (is_array($items) && count($items) > 0)
    <nav class="mb-5 rounded-xl border border-slate-200 bg-white px-4 py-3 shadow-sm" aria-label="Breadcrumb">
        <ol class="flex flex-wrap items-center gap-1.5 text-xs text-slate-500">
            @foreach ($items as $index => $item)
                @if (is_string($item))
                    @php($item = ['label' => $item])
                @endif
                @if (is_array($item) && isset($item['label']))
                    @if ($index > 0)<li aria-hidden="true" class="text-slate-300"><i class="fas fa-chevron-left text-[10px]"></i></li>@endif
                    <li @if ($item['active'] ?? ($index === count($items) - 1)) aria-current="page" @endif>
                        @if (! empty($item['url']) && ! ($item['active'] ?? ($index === count($items) - 1)))
                            <a href="{{ $item['url'] }}" class="inline-flex items-center gap-1.5 hover:text-indigo-600 transition">
                                @if (! empty($item['icon']))<i class="{{ $item['icon'] }}"></i>@endif
                                {{ $item['label'] }}
                            </a>
                        @else
                            <span class="inline-flex items-center gap-1.5 font-semibold text-indigo-600">
                                @if (! empty($item['icon']))<i class="{{ $item['icon'] }}"></i>@endif
                                {{ $item['label'] }}
                            </span>
                        @endif
                    </li>
                @endif
            @endforeach
        </ol>
    </nav>
@endif
