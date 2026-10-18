<!DOCTYPE html>
<html lang="fa" dir="rtl" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'پنل مدیریت' }}</title>
    <link rel="stylesheet" href="{{ asset('admin-assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('admin-assets/fontawesome/css/all.min.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/style.css">
    <link rel="stylesheet" href="https://unpkg.com/persian-datepicker@1.2.0/dist/css/persian-datepicker.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/css/tom-select.bootstrap5.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="{{ asset('admin-assets/js/alpine.min.js') }}"></script>
    <style>
        @font-face {
            font-family: 'IRANSans';
            src: url('{{ asset('admin-assets/fonts/IRANSans/IRANSansWeb_Light.woff') }}') format('woff');
            font-weight: 300;
        }
        @font-face {
            font-family: 'IRANSans';
            src: url('{{ asset('admin-assets/fonts/IRANSans/IRANSansWeb_Bold.woff') }}') format('woff');
            font-weight: 700;
        }
        body { font-family: 'IRANSans', system-ui, sans-serif; }
        input[type="text"], input[type="number"], input[type="email"], input[type="password"], input[type="date"], input[type="search"], select, textarea {
            border: 1px solid #cbd5e1 !important;
            background-color: #fff !important;
            border-radius: .5rem !important;
            padding: .5rem .75rem !important;
            outline: none !important;
            transition: all .2s ease-in-out;
        }
        input:focus, select:focus, textarea:focus { border-color: #6366f1 !important; box-shadow: 0 0 0 3px rgba(99,102,241,.15) !important; }
        select { -webkit-appearance: none !important; -moz-appearance: none !important; appearance: none !important; background-image: none !important; }
        .ts-wrapper { direction: rtl !important; text-align: right !important; }
        .ts-control { direction: rtl !important; text-align: right !important; padding-left: 2.25rem !important; padding-right: .75rem !important; border-radius: .5rem !important; border-color: #cbd5e1 !important; min-height: 42px !important; display: flex !important; align-items: center !important; }
        .ts-wrapper.single .ts-control::after { left: 10px !important; right: auto !important; margin-top: -3px !important; border-color: #64748b transparent transparent transparent !important; }
        .ts-wrapper.single.dropdown-active .ts-control::after { border-color: transparent transparent #64748b transparent !important; margin-top: -8px !important; }
        .ts-dropdown { direction: rtl !important; text-align: right !important; border-radius: .5rem !important; box-shadow: 0 10px 15px -3px rgba(0,0,0,.1) !important; z-index: 9999 !important; }
        .ts-dropdown .option { padding: 8px 12px !important; font-size: .875rem !important; }
        .ts-dropdown .active { background-color: #f1f5f9 !important; color: #0f172a !important; }
        /* جایگاه اجباری فلش و فضای مستقل برای متن RTL */
        .ts-wrapper .ts-control { direction: rtl !important; text-align: right !important; padding-right: 12px !important; padding-left: 36px !important; }
        .ts-wrapper.single .ts-control::after { right: auto !important; left: 12px !important; }
        .ts-wrapper .ts-control > input { text-align: right !important; direction: rtl !important; }
        .ts-wrapper .ts-control .item { text-align: right !important; direction: rtl !important; }
        /* فلش CSS پیش‌فرض حذف می‌شود و فلش SVG در سمت چپ قرار می‌گیرد */
        .ts-wrapper.single .ts-control::after { display: none !important; content: none !important; }
        .ts-wrapper .ts-control {
            direction: rtl !important;
            text-align: right !important;
            padding-right: 12px !important;
            padding-left: 35px !important;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e") !important;
            background-position: left 0.75rem center !important;
            background-repeat: no-repeat !important;
            background-size: 1.25em 1.25em !important;
        }
        .ts-wrapper .ts-control > .item, .ts-wrapper .ts-control > input, .ts-wrapper .ts-control > .ts-placeholder { direction: rtl !important; text-align: right !important; float: right !important; margin-right: 0 !important; }
        .ts-dropdown, .ts-dropdown .option { direction: rtl !important; text-align: right !important; }
    </style>
    @stack('styles')
</head>
<body class="h-full font-sans antialiased" x-data="{ sidebarOpen: true, mobileSidebarOpen: false }">
    <div class="min-h-full flex flex-col">
        @include('partials.header')
        <div class="flex pt-14 flex-1">
            @include('partials.sidebar')
            <main class="flex-1 bg-slate-100 p-4 md:p-6 overflow-y-auto">
                @include('components.breadcrumb', ['items' => $breadcrumbs ?? []])
                @include('partials.flash-messages')
                @yield('content')
            </main>
        </div>
        @include('partials.footer')
    </div>
    @include('sweetalert::alert')
    <script src="{{ asset('admin-assets/js/jquery-3.5.1.min.js') }}"></script>
    <script src="{{ asset('admin-assets/js/bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/plugins/monthSelect/index.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
    <script src="https://unpkg.com/persian-date@1.1.0/dist/persian-date.min.js"></script>
    <script src="https://unpkg.com/persian-datepicker@1.2.0/dist/js/persian-datepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.2.2/dist/js/tom-select.complete.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            document.querySelectorAll('select').forEach(function (element) {
                if (element.tomselect || typeof TomSelect === 'undefined') return;
                element.setAttribute('dir', 'rtl');
                const ts = new TomSelect(element, {
                    create: false,
                    dir: 'rtl',
                    maxItems: element.hasAttribute('multiple') ? null : 1,
                    plugins: element.hasAttribute('multiple') ? ['remove_button'] : [],
                    placeholder: element.getAttribute('placeholder') || 'انتخاب کنید...',
                    render: { no_results: function () { return '<div class="no-results p-2 text-xs text-slate-500">نتیجه‌ای یافت نشد</div>'; } },
                });
                if (ts.wrapper) { ts.wrapper.setAttribute('dir', 'rtl'); ts.wrapper.classList.add('rtl-ts'); }
            });
        });
        document.addEventListener('DOMContentLoaded', function () {
            if (window.jQuery && jQuery.fn.pDatepicker) {
                jQuery('.persian-datepicker').pDatepicker({
                    format: 'YYYY/MM/DD',
                    autoClose: true,
                    initialValue: false,
                    observer: true,
                    calendar: { persian: { locale: 'fa', leapYearMode: 'astronomical' } },
                });
            }
        });
    </script>
    @stack('scripts')
</body>
</html>
