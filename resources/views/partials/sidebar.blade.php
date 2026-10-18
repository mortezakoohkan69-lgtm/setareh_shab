@php
    $menuGroups = [
        ['title' => 'محصولات و انبار', 'icon' => 'heroicon-o-cube', 'items' => [
            ['label' => 'دسته‌بندی‌ها', 'route' => 'admin.categories.index', 'icon' => 'heroicon-o-tag'],
            ['label' => 'محصولات و مواد اولیه', 'route' => 'admin.products.index', 'icon' => 'heroicon-o-cube'],
            ['label' => 'فرمول ساخت و ترکیبات', 'route' => 'admin.product-recipes.index', 'icon' => 'heroicon-o-beaker'],
            ['label' => 'موجودی لحظه‌ای', 'route' => 'admin.inventory.index', 'icon' => 'heroicon-o-archive-box'],
            ['label' => 'کاردکس انبار', 'route' => 'admin.inventory-logs.index', 'icon' => 'heroicon-o-arrows-right-left'],
        ]],
        ['title' => 'مشتریان و تخفیف‌ها', 'icon' => 'heroicon-o-users', 'items' => [
            ['label' => 'مشتریان', 'route' => 'admin.customers.index', 'icon' => 'heroicon-o-user-group'],
            ['label' => 'کمپین‌های تخفیف', 'route' => 'admin.discounts.index', 'icon' => 'heroicon-o-ticket'],
            ['label' => 'تخصیص تخفیف به محصول', 'route' => 'admin.discount-products.index', 'icon' => 'heroicon-o-link'],
        ]],
        ['title' => 'مالی و خزانه‌داری', 'icon' => 'heroicon-o-banknotes', 'items' => [
            ['label' => 'حساب‌های بانکی و صندوق', 'route' => 'admin.bank-accounts.index', 'icon' => 'heroicon-o-building-library'],
            ['label' => 'مدیریت چک‌ها', 'route' => 'admin.cheques.index', 'icon' => 'heroicon-o-credit-card'],
            ['label' => 'هزینه‌های جانبی', 'route' => 'admin.expenses.index', 'icon' => 'heroicon-o-receipt-percent'],
        ]],
        ['title' => 'خرید و فروش', 'icon' => 'heroicon-o-shopping-cart', 'items' => [
            ['label' => 'فاکتورهای خرید', 'route' => 'admin.purchases.index', 'icon' => 'heroicon-o-shopping-cart'],
            ['label' => 'اقلام فاکتور خرید', 'route' => 'admin.purchase-items.index', 'icon' => 'heroicon-o-list-bullet'],
            ['label' => 'فروش و صندوق POS', 'route' => 'admin.orders.index', 'icon' => 'heroicon-o-currency-dollar'],
            ['label' => 'اقلام فروش', 'route' => 'admin.order-items.index', 'icon' => 'heroicon-o-list-bullet'],
        ]],
        ['title' => 'گزارشات و تحلیل', 'icon' => 'heroicon-o-chart-bar', 'items' => [
            ['label' => 'خلاصه روزانه', 'route' => 'admin.daily-sales-reports.index', 'icon' => 'heroicon-o-calendar-days'],
            ['label' => 'عملکرد فروش محصولات', 'route' => 'admin.product-sales-stats.index', 'icon' => 'heroicon-o-presentation-chart-line'],
        ]],
    ];
@endphp
<aside :class="[(sidebarOpen ? 'w-56' : 'w-0 md:w-16'), (mobileSidebarOpen ? 'block' : 'hidden md:block')]" class="bg-slate-800 text-slate-300 transition-all duration-300 fixed md:static right-0 top-14 bottom-0 z-20 overflow-y-auto shrink-0">
    <nav class="p-3 space-y-2 text-xs">
        <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition {{ request()->routeIs('dashboard', 'admin.dashboard') ? 'bg-indigo-600 text-white' : 'hover:bg-slate-700 text-slate-200' }}">
            @svg('heroicon-o-home', 'w-4 h-4 shrink-0')<span x-show="sidebarOpen">داشبورد</span>
        </a>
        @foreach ($menuGroups as $group)
            @php($groupIsActive = collect($group['items'])->contains(fn ($item) => request()->routeIs($item['route']) || request()->is(str_replace('.index', '*', str_replace('admin.', 'admin/', $item['route'])))))
            <div x-data="{ open: {{ $groupIsActive ? 'true' : 'false' }} }">
                <button type="button" @click="open = !open" class="w-full flex items-center justify-between px-3 py-2.5 rounded-lg hover:bg-slate-700 transition {{ $groupIsActive ? 'text-white font-bold' : '' }}">
                    <span class="flex items-center gap-3">@svg($group['icon'], 'w-4 h-4 shrink-0')<span x-show="sidebarOpen" class="whitespace-nowrap">{{ $group['title'] }}</span></span>
                    <span x-show="sidebarOpen" class="text-[10px]" :class="open ? 'rotate-180' : ''">⌄</span>
                </button>
                <div x-show="open && sidebarOpen" x-transition class="pr-6 pl-1 py-1 space-y-1 text-[11px]">
                    @foreach ($group['items'] as $item)
                        <a href="{{ route($item['route']) }}" class="flex items-center gap-2 rounded-md px-2 py-2 transition {{ request()->routeIs($item['route']) ? 'bg-indigo-600 text-white font-semibold' : 'text-slate-400 hover:bg-slate-700 hover:text-white' }}">
                            @svg($item['icon'], 'w-3.5 h-3.5 shrink-0')<span>{{ $item['label'] }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
        @endforeach
    </nav>
</aside>
