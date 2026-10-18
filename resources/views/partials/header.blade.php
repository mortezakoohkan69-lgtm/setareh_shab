<header class="bg-slate-800 text-slate-200 h-14 fixed top-0 left-0 right-0 z-30 flex items-center justify-between px-4 shadow">
    <div class="flex items-center gap-3 w-56">
        <button type="button" @click="sidebarOpen = !sidebarOpen" class="hidden md:block text-slate-300 hover:text-white focus:outline-none" aria-label="تغییر وضعیت منو">
            <i class="fas text-xl" :class="sidebarOpen ? 'fa-toggle-on text-emerald-400' : 'fa-toggle-off'"></i>
        </button>
        <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
            <img class="h-8 w-auto" src="{{ asset('admin-assets/images/logo.png') }}" alt="لوگو">
            <span class="font-bold text-sm">پنل مدیریت</span>
        </a>
        <button type="button" @click="mobileSidebarOpen = !mobileSidebarOpen" class="md:hidden text-slate-300 hover:text-white mr-auto" aria-label="نمایش منو">
            <i class="fas fa-bars text-lg"></i>
        </button>
    </div>
    <div class="flex items-center gap-4">
        <div class="relative" x-data="{ open: false }">
            <button type="button" @click="open = !open" class="p-1.5 hover:text-white text-slate-300" aria-label="جستجو"><i class="fas fa-search"></i></button>
            <div x-show="open" @click.outside="open = false" x-transition class="absolute left-0 mt-2 w-64 bg-white rounded-md shadow-lg p-2 z-50 text-slate-800">
                <input type="search" placeholder="جستجو کنید..." class="w-full px-3 py-1.5 border text-sm rounded-md focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>
        </div>
        <div class="relative" x-data="{ open: false }">
            <button type="button" @click="open = !open" class="relative p-1.5 text-slate-300 hover:text-white" aria-label="اعلان‌ها">
                <i class="far fa-bell text-lg"></i><span class="absolute top-0 right-0 h-4 w-4 bg-rose-500 text-white rounded-full text-[10px] flex items-center justify-center">4</span>
            </button>
            <div x-show="open" @click.outside="open = false" x-transition class="absolute left-0 mt-2 w-72 bg-white rounded-lg shadow-xl border border-slate-100 z-50 text-slate-800">
                <div class="p-3 border-b border-slate-100 text-xs font-bold">نوتیفیکیشن‌ها <span class="float-left px-2 py-0.5 bg-rose-100 text-rose-600 rounded-full">جدید</span></div>
                <div class="p-3 text-xs text-slate-500">اعلان جدیدی برای نمایش وجود ندارد.</div>
            </div>
        </div>
        <div class="relative" x-data="{ open: false }">
            <button type="button" @click="open = !open" class="flex items-center gap-2 focus:outline-none">
                <img class="w-8 h-8 rounded-md object-cover border border-slate-600" src="{{ asset('admin-assets/images/avatar-2.jpg') }}" alt="کاربر">
                <span class="hidden md:inline text-xs font-medium text-slate-200">کامران محمدی</span><i class="fas fa-angle-down text-xs text-slate-400"></i>
            </button>
            <div x-show="open" @click.outside="open = false" x-transition class="absolute left-0 mt-2 w-48 bg-white rounded-md shadow-lg border border-slate-100 py-1 z-50 text-xs text-slate-700">
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-slate-100"><i class="fas fa-cog w-4 text-slate-400"></i> تنظیمات</a>
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-slate-100"><i class="fas fa-user w-4 text-slate-400"></i> پروفایل</a>
                <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-rose-50 text-rose-600"><i class="fas fa-sign-out-alt w-4"></i> خروج</a>
            </div>
        </div>
    </div>
</header>
