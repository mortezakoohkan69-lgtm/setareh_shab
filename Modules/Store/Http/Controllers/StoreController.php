<?php

namespace Modules\Store\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ApiRequest;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\View\View;

class StoreController extends Controller
{
    public static function resources(): array
    {
        return [
            'categories' => ['title' => 'دسته‌بندی‌ها', 'icon' => 'fa-tags', 'table' => 'categories', 'columns' => ['title', 'type'], 'fields' => [
                ['name' => 'title', 'label' => 'عنوان', 'type' => 'text', 'required' => true],
                ['name' => 'type', 'label' => 'نوع', 'type' => 'select', 'required' => true, 'options' => ['product' => 'محصول', 'material' => 'مواد اولیه']],
            ]],
            'products' => ['title' => 'محصولات و مواد اولیه', 'icon' => 'fa-boxes-stacked', 'table' => 'products', 'columns' => ['name', 'sku', 'cost_price', 'sale_price', 'is_active'], 'fields' => [
                ['name' => 'category_id', 'label' => 'دسته‌بندی', 'type' => 'select', 'relation' => 'categories', 'required' => true],
                ['name' => 'name', 'label' => 'نام', 'type' => 'text', 'required' => true],
                ['name' => 'sku', 'label' => 'کد کالا', 'type' => 'text'],
                ['name' => 'cost_price', 'label' => 'قیمت تمام‌شده', 'type' => 'number', 'step' => '0.01', 'required' => true],
                ['name' => 'sale_price', 'label' => 'قیمت فروش', 'type' => 'number', 'step' => '0.01', 'required' => true],
                ['name' => 'discount_type', 'label' => 'نوع تخفیف', 'type' => 'select', 'options' => ['none' => 'بدون تخفیف', 'fixed' => 'مبلغ ثابت', 'percent' => 'درصدی']],
                ['name' => 'discount_value', 'label' => 'مقدار تخفیف', 'type' => 'number', 'step' => '0.01'],
                ['name' => 'is_active', 'label' => 'فعال', 'type' => 'checkbox'],
                ['name' => 'is_material', 'label' => 'ماده اولیه', 'type' => 'checkbox'],
            ]],
            'product-recipes' => ['title' => 'فرمول ساخت و ترکیبات', 'icon' => 'fa-flask', 'table' => 'product_recipes', 'columns' => ['product_id', 'material_id', 'quantity', 'unit'], 'fields' => [
                ['name' => 'product_id', 'label' => 'محصول', 'type' => 'select', 'relation' => 'products', 'required' => true],
                ['name' => 'material_id', 'label' => 'ماده اولیه', 'type' => 'select', 'relation' => 'products', 'required' => true],
                ['name' => 'quantity', 'label' => 'مقدار', 'type' => 'number', 'step' => '0.001', 'required' => true],
                ['name' => 'unit', 'label' => 'واحد', 'type' => 'text', 'required' => true],
            ]],
            'inventory' => ['title' => 'موجودی لحظه‌ای انبار', 'icon' => 'fa-warehouse', 'table' => 'inventory', 'columns' => ['product_id', 'current_stock', 'min_stock_alert', 'unit'], 'fields' => [
                ['name' => 'product_id', 'label' => 'کالا', 'type' => 'select', 'relation' => 'products', 'required' => true],
                ['name' => 'current_stock', 'label' => 'موجودی فعلی', 'type' => 'number', 'step' => '0.001', 'required' => true],
                ['name' => 'min_stock_alert', 'label' => 'حد هشدار', 'type' => 'number', 'step' => '0.001', 'required' => true],
                ['name' => 'unit', 'label' => 'واحد', 'type' => 'text', 'required' => true],
            ]],
            'inventory-logs' => ['title' => 'کاردکس انبار', 'icon' => 'fa-right-left', 'table' => 'inventory_logs', 'columns' => ['product_id', 'type', 'quantity', 'reference_id', 'description'], 'fields' => [
                ['name' => 'product_id', 'label' => 'کالا', 'type' => 'select', 'relation' => 'products', 'required' => true],
                ['name' => 'type', 'label' => 'نوع تراکنش', 'type' => 'select', 'options' => ['purchase' => 'خرید', 'sale' => 'فروش', 'waste' => 'ضایعات', 'manual_adj' => 'اصلاح دستی'], 'required' => true],
                ['name' => 'quantity', 'label' => 'مقدار', 'type' => 'number', 'step' => '0.001', 'required' => true],
                ['name' => 'reference_id', 'label' => 'شناسه مرجع', 'type' => 'number'],
                ['name' => 'description', 'label' => 'شرح', 'type' => 'textarea'],
            ]],
            'customers' => ['title' => 'مشتریان', 'icon' => 'fa-user-group', 'table' => 'customers', 'columns' => ['first_name', 'last_name', 'mobile', 'purchase_count', 'total_spent'], 'fields' => [
                ['name' => 'first_name', 'label' => 'نام', 'type' => 'text', 'required' => true], ['name' => 'last_name', 'label' => 'نام خانوادگی', 'type' => 'text', 'required' => true], ['name' => 'mobile', 'label' => 'موبایل', 'type' => 'text', 'required' => true], ['name' => 'birth_date', 'label' => 'تاریخ تولد', 'type' => 'date'], ['name' => 'purchase_count', 'label' => 'تعداد خرید', 'type' => 'number', 'computed' => true], ['name' => 'total_spent', 'label' => 'مجموع خرید', 'type' => 'number', 'step' => '0.01', 'computed' => true],
            ]],
            'discounts' => ['title' => 'کمپین‌های تخفیف', 'icon' => 'fa-percent', 'table' => 'discounts', 'columns' => ['title', 'type', 'discount_type', 'discount_value', 'is_active'], 'fields' => [
                ['name' => 'title', 'label' => 'عنوان', 'type' => 'text', 'required' => true], ['name' => 'type', 'label' => 'نوع کمپین', 'type' => 'select', 'options' => ['birthday' => 'تولد', 'purchase_count' => 'تعداد خرید', 'happy_hour' => 'ساعت شادی', 'event' => 'رویداد', 'product_specific' => 'محصول خاص'], 'required' => true], ['name' => 'discount_type', 'label' => 'نوع تخفیف', 'type' => 'select', 'options' => ['fixed_amount' => 'مبلغ ثابت', 'percent' => 'درصدی'], 'required' => true], ['name' => 'discount_value', 'label' => 'مقدار تخفیف', 'type' => 'number', 'step' => '0.01', 'required' => true], ['name' => 'min_purchases', 'label' => 'حداقل خرید', 'type' => 'number'], ['name' => 'start_date', 'label' => 'تاریخ شروع', 'type' => 'datetime-local'], ['name' => 'end_date', 'label' => 'تاریخ پایان', 'type' => 'datetime-local'], ['name' => 'start_time', 'label' => 'ساعت شروع', 'type' => 'time'], ['name' => 'end_time', 'label' => 'ساعت پایان', 'type' => 'time'], ['name' => 'days_of_week', 'label' => 'روزهای هفته', 'type' => 'text'], ['name' => 'is_active', 'label' => 'فعال', 'type' => 'checkbox'],
            ]],
            'discount-products' => ['title' => 'تخصیص تخفیف به محصول', 'icon' => 'fa-link', 'table' => 'discount_products', 'columns' => ['discount_id', 'product_id'], 'fields' => [
                ['name' => 'discount_id', 'label' => 'تخفیف', 'type' => 'select', 'relation' => 'discounts', 'required' => true], ['name' => 'product_id', 'label' => 'محصول', 'type' => 'select', 'relation' => 'products', 'required' => true],
            ]],
            'bank-accounts' => ['title' => 'حساب‌های بانکی و صندوق', 'icon' => 'fa-building-columns', 'table' => 'bank_accounts', 'columns' => ['title', 'bank_name', 'account_number', 'current_balance', 'account_type'], 'fields' => [
                ['name' => 'title', 'label' => 'عنوان', 'type' => 'text', 'required' => true], ['name' => 'bank_name', 'label' => 'نام بانک', 'type' => 'text'], ['name' => 'account_number', 'label' => 'شماره حساب', 'type' => 'text'], ['name' => 'card_number', 'label' => 'شماره کارت', 'type' => 'text'], ['name' => 'iban', 'label' => 'شبا', 'type' => 'text'], ['name' => 'initial_balance', 'label' => 'موجودی اولیه', 'type' => 'number', 'step' => '0.01'], ['name' => 'current_balance', 'label' => 'موجودی فعلی', 'type' => 'number', 'step' => '0.01'], ['name' => 'account_type', 'label' => 'نوع حساب', 'type' => 'select', 'options' => ['main' => 'اصلی', 'sub' => 'فرعی'], 'required' => true], ['name' => 'is_active', 'label' => 'فعال', 'type' => 'checkbox'],
            ]],
            'cheques' => ['title' => 'مدیریت چک‌ها', 'icon' => 'fa-money-check', 'table' => 'cheques', 'columns' => ['type', 'serial_number', 'amount', 'payee_payor', 'due_date', 'status'], 'fields' => [
                ['name' => 'type', 'label' => 'نوع چک', 'type' => 'select', 'options' => ['received' => 'دریافتی', 'paid' => 'پرداختی'], 'required' => true], ['name' => 'serial_number', 'label' => 'شماره سریال', 'type' => 'text', 'required' => true], ['name' => 'amount', 'label' => 'مبلغ', 'type' => 'number', 'step' => '0.01', 'required' => true], ['name' => 'payee_payor', 'label' => 'دریافت‌کننده / پرداخت‌کننده', 'type' => 'text', 'required' => true], ['name' => 'for_reason', 'label' => 'بابت', 'type' => 'text'], ['name' => 'issue_date', 'label' => 'تاریخ صدور', 'type' => 'date'], ['name' => 'due_date', 'label' => 'تاریخ سررسید', 'type' => 'date', 'required' => true], ['name' => 'status', 'label' => 'وضعیت', 'type' => 'select', 'options' => ['pending' => 'در انتظار', 'cleared' => 'وصول شده', 'bounced' => 'برگشتی'], 'required' => true], ['name' => 'bank_account_id', 'label' => 'حساب بانکی', 'type' => 'select', 'relation' => 'bank_accounts'], ['name' => 'description', 'label' => 'شرح', 'type' => 'textarea'],
            ]],
            'expenses' => ['title' => 'هزینه‌های جانبی', 'icon' => 'fa-receipt', 'table' => 'expenses', 'columns' => ['title', 'amount', 'bank_account_id', 'expense_date', 'description'], 'fields' => [
                ['name' => 'title', 'label' => 'عنوان', 'type' => 'text', 'required' => true], ['name' => 'amount', 'label' => 'مبلغ', 'type' => 'number', 'step' => '0.01', 'required' => true], ['name' => 'bank_account_id', 'label' => 'حساب بانکی', 'type' => 'select', 'relation' => 'bank_accounts'], ['name' => 'expense_date', 'label' => 'تاریخ هزینه', 'type' => 'date', 'required' => true], ['name' => 'description', 'label' => 'شرح', 'type' => 'textarea'],
            ]],
            'purchases' => ['title' => 'فاکتورهای خرید', 'icon' => 'fa-cart-shopping', 'table' => 'purchases', 'columns' => ['supplier_name', 'total_amount', 'purchase_date', 'bank_account_id'], 'fields' => [
                ['name' => 'supplier_name', 'label' => 'نام تأمین‌کننده', 'type' => 'text', 'required' => true], ['name' => 'bank_account_id', 'label' => 'حساب بانکی', 'type' => 'select', 'relation' => 'bank_accounts'], ['name' => 'total_amount', 'label' => 'مبلغ کل', 'type' => 'number', 'step' => '0.01', 'required' => true], ['name' => 'purchase_date', 'label' => 'تاریخ خرید', 'type' => 'datetime-local', 'required' => true],
            ]],
            'purchase-items' => ['title' => 'اقلام فاکتور خرید', 'icon' => 'fa-list', 'table' => 'purchase_items', 'columns' => ['purchase_id', 'product_id', 'quantity', 'unit_cost', 'total_cost'], 'fields' => [
                ['name' => 'purchase_id', 'label' => 'فاکتور خرید', 'type' => 'select', 'relation' => 'purchases', 'required' => true], ['name' => 'product_id', 'label' => 'کالا', 'type' => 'select', 'relation' => 'products', 'required' => true], ['name' => 'quantity', 'label' => 'تعداد', 'type' => 'number', 'step' => '0.01', 'required' => true], ['name' => 'unit_cost', 'label' => 'قیمت فی خرید', 'type' => 'number', 'step' => '0.01', 'required' => true], ['name' => 'total_cost', 'label' => 'قیمت کل', 'type' => 'number', 'step' => '0.01', 'required' => true],
            ]],
            'orders' => ['title' => 'فروش و صندوق POS', 'icon' => 'fa-cash-register', 'table' => 'orders', 'columns' => ['customer_id', 'total_raw_amount', 'total_discount', 'final_amount', 'payment_type', 'status'], 'fields' => [
                ['name' => 'customer_id', 'label' => 'مشتری', 'type' => 'select', 'relation' => 'customers'], ['name' => 'bank_account_id', 'label' => 'حساب بانکی / صندوق', 'type' => 'select', 'relation' => 'bank_accounts'], ['name' => 'applied_discount_id', 'label' => 'تخفیف اعمال‌شده', 'type' => 'select', 'relation' => 'discounts'], ['name' => 'total_raw_amount', 'label' => 'مبلغ ناخالص', 'type' => 'number', 'step' => '0.01', 'required' => true], ['name' => 'total_discount', 'label' => 'تخفیف', 'type' => 'number', 'step' => '0.01'], ['name' => 'final_amount', 'label' => 'مبلغ نهایی', 'type' => 'number', 'step' => '0.01', 'required' => true], ['name' => 'total_cost_price', 'label' => 'بهای تمام‌شده', 'type' => 'number', 'step' => '0.01', 'required' => true], ['name' => 'payment_type', 'label' => 'نوع پرداخت', 'type' => 'select', 'options' => ['card' => 'کارت', 'cash' => 'نقدی', 'mixed' => 'ترکیبی'], 'required' => true], ['name' => 'status', 'label' => 'وضعیت', 'type' => 'select', 'options' => ['completed' => 'تکمیل‌شده', 'cancelled' => 'لغوشده'], 'required' => true],
            ]],
            'order-items' => ['title' => 'اقلام فروش', 'icon' => 'fa-list-check', 'table' => 'order_items', 'columns' => ['order_id', 'product_id', 'quantity', 'unit_cost_price', 'unit_sale_price', 'discount_amount', 'final_price'], 'fields' => [
                ['name' => 'order_id', 'label' => 'فاکتور فروش', 'type' => 'select', 'relation' => 'orders', 'required' => true], ['name' => 'product_id', 'label' => 'محصول', 'type' => 'select', 'relation' => 'products', 'required' => true], ['name' => 'quantity', 'label' => 'تعداد', 'type' => 'number', 'required' => true], ['name' => 'unit_cost_price', 'label' => 'بهای تمام‌شده واحد', 'type' => 'number', 'step' => '0.01', 'required' => true], ['name' => 'unit_sale_price', 'label' => 'قیمت فروش واحد', 'type' => 'number', 'step' => '0.01', 'required' => true], ['name' => 'discount_amount', 'label' => 'تخفیف', 'type' => 'number', 'step' => '0.01'], ['name' => 'final_price', 'label' => 'مبلغ نهایی', 'type' => 'number', 'step' => '0.01', 'required' => true],
            ]],
            'daily-sales-reports' => ['title' => 'گزارش خلاصه روزانه', 'icon' => 'fa-chart-line', 'table' => 'daily_sales_reports', 'columns' => ['report_date', 'total_orders', 'total_gross_sales', 'total_net_sales', 'gross_profit', 'net_profit'], 'fields' => [
                ['name' => 'report_date', 'label' => 'تاریخ گزارش', 'type' => 'date', 'required' => true], ['name' => 'total_orders', 'label' => 'تعداد سفارش', 'type' => 'number'], ['name' => 'total_gross_sales', 'label' => 'فروش ناخالص', 'type' => 'number', 'step' => '0.01'], ['name' => 'total_discounts', 'label' => 'تخفیف کل', 'type' => 'number', 'step' => '0.01'], ['name' => 'total_net_sales', 'label' => 'فروش خالص', 'type' => 'number', 'step' => '0.01'], ['name' => 'total_cogs', 'label' => 'بهای تمام‌شده', 'type' => 'number', 'step' => '0.01'], ['name' => 'total_expenses', 'label' => 'هزینه‌ها', 'type' => 'number', 'step' => '0.01'], ['name' => 'gross_profit', 'label' => 'سود ناخالص', 'type' => 'number', 'step' => '0.01'], ['name' => 'net_profit', 'label' => 'سود خالص', 'type' => 'number', 'step' => '0.01'],
            ]],
            'product-sales-stats' => ['title' => 'عملکرد فروش محصولات', 'icon' => 'fa-ranking-star', 'table' => 'product_sales_stats', 'columns' => ['product_id', 'report_period', 'total_quantity_sold', 'total_revenue', 'total_profit'], 'fields' => [
                ['name' => 'product_id', 'label' => 'محصول', 'type' => 'select', 'relation' => 'products', 'required' => true], ['name' => 'report_period', 'label' => 'دوره گزارش (مانند 2026-09)', 'type' => 'text', 'required' => true], ['name' => 'total_quantity_sold', 'label' => 'مجموع تعداد فروش', 'type' => 'number', 'step' => '0.01'], ['name' => 'total_revenue', 'label' => 'مجموع درآمد', 'type' => 'number', 'step' => '0.01'], ['name' => 'total_profit', 'label' => 'مجموع سود', 'type' => 'number', 'step' => '0.01'],
            ]],
        ];
    }

    public function index(Request $request): View
    {
        $resource = $this->resource($request);
        $query = $this->resourceKey($request) === 'customers'
            ? Customer::withCount(['orders as completed_orders_count' => fn ($orders) => $orders->where('status', 'completed')])->withSum(['orders as completed_orders_sum_final_amount' => fn ($orders) => $orders->where('status', 'completed')], 'final_amount')
            : DB::table($resource['table']);
        $search = $request->string('search')->trim()->toString();
        if ($search !== '') {
            $query->where(function ($builder) use ($search, $resource): void {
                foreach ($resource['columns'] as $column) {
                    $builder->orWhere($column, 'like', "%{$search}%");
                }
            });
        }
        return view('store::index', ['resource' => $resource, 'rows' => $query->latest('id')->paginate(15)->withQueryString(), 'resourceKey' => $this->resourceKey($request), 'breadcrumbs' => $this->breadcrumbs($request, $resource, 'index')]);
    }

    public function create(Request $request): View
    {
        $resource = $this->resource($request);
        return view('store::form', ['resource' => $resource, 'fields' => $resource['fields'] ?? [], 'products' => $this->activeProducts($request), 'categories' => $this->activeCategories($request), 'categoriesWithProducts' => $this->categoriesWithProducts($request), 'selectedProductIds' => [], 'itemCategoryIds' => [], 'resourceKey' => $this->resourceKey($request), 'row' => null, 'options' => $this->options($resource), 'breadcrumbs' => $this->breadcrumbs($request, $resource, 'create')]);
    }

    public function store(Request $request): RedirectResponse
    {
        $resource = $this->resource($request);
        $this->validateForm($request, $resource);
        $id = DB::table($resource['table'])->insertGetId($this->payload($request, $resource));
        $this->syncDiscountProducts($request, $id);
        return redirect()->route("admin.{$this->resourceKey($request)}.index")->with('success', 'رکورد با موفقیت ثبت شد.');
    }

    public function edit(Request $request, int $id): View
    {
        $resource = $this->resource($request);
        $row = DB::table($resource['table'])->find($id);
        abort_if($row === null, 404);
        return view('store::form', ['resource' => $resource, 'fields' => $resource['fields'] ?? [], 'products' => $this->activeProducts($request), 'categories' => $this->activeCategories($request), 'categoriesWithProducts' => $this->categoriesWithProducts($request), 'selectedProductIds' => $this->selectedProductIds($request, $id), 'itemCategoryIds' => $this->selectedCategoryIds($request, $id), 'resourceKey' => $this->resourceKey($request), 'row' => $row, 'options' => $this->options($resource), 'breadcrumbs' => $this->breadcrumbs($request, $resource, 'edit')]);
    }

    public function update(Request $request, int $id): RedirectResponse
    {
        $resource = $this->resource($request);
        $this->validateForm($request, $resource, true);
        DB::table($resource['table'])->where('id', $id)->update($this->payload($request, $resource));
        $this->syncDiscountProducts($request, $id);
        return redirect()->route("admin.{$this->resourceKey($request)}.index")->with('success', 'رکورد با موفقیت ویرایش شد.');
    }

    public function destroy(Request $request, int $id): RedirectResponse
    {
        $resource = $this->resource($request);
        DB::table($resource['table'])->where('id', $id)->delete();
        return back()->with('success', 'رکورد حذف شد.');
    }

    private function resource(Request $request): array
    {
        return self::resources()[$this->resourceKey($request)] ?? abort(404);
    }

    private function breadcrumbs(Request $request, array $resource, string $action): array
    {
        $resourceKey = $this->resourceKey($request);
        $items = [
            ['label' => 'داشبورد', 'url' => route('dashboard'), 'icon' => 'fas fa-home'],
            ['label' => 'مدیریت فروشگاه'],
            ['label' => $resource['title'], 'url' => route("admin.{$resourceKey}.index")],
        ];

        if ($action !== 'index') {
            $items[] = ['label' => $action === 'edit' ? 'ویرایش' : 'ایجاد', 'active' => true];
        } else {
            $items[2]['active'] = true;
        }

        return $items;
    }

    private function resourceKey(Request $request): string
    {
        return Str::before(Str::after($request->route()->getName(), 'admin.'), '.');
    }

    private function options(array $resource): array
    {
        $options = [];
        foreach ($resource['fields'] as $field) {
            if (isset($field['relation'])) {
                $table = $field['relation'];
                $options[$field['name']] = DB::table($table)->orderBy('id')->get()->mapWithKeys(function ($item): array {
                    $label = $item->title ?? $item->name ?? ($item->first_name ?? "رکورد {$item->id}");
                    return [$item->id => $label];
                })->all();
            }
        }
        return $options;
    }

    private function payload(Request $request, array $resource): array
    {
        $request->validate($this->rules($resource));
        $payload = [];
        foreach ($resource['fields'] as $field) {
            if (! empty($field['computed'])) continue;
            $name = $field['name'];
            $value = $field['type'] === 'checkbox' ? $request->boolean($name) : $request->input($name);
            if (in_array($field['type'], ['date', 'datetime-local'], true) && filled($value)) {
                $value = ApiRequest::normalizeDates([$name => $value])[$name] ?? $value;
            }
            $payload[$name] = $value;
        }
        $payload['updated_at'] = now();
        if ($request->isMethod('post')) {
            $payload['created_at'] = now();
        }
        return $payload;
    }

    private function validateForm(Request $request, array $resource, bool $update = false): void
    {
        $rules = [];
        foreach ($resource['fields'] as $field) {
            if (! empty($field['computed'])) continue;
            $type = $field['type'] ?? 'text';
            $rule = match ($type) {
                'number' => 'numeric',
                'date', 'datetime-local' => 'date',
                'checkbox' => 'boolean',
                default => 'string',
            };
            $rules[$field['name']] = array_filter([$update ? 'nullable' : (($field['required'] ?? false) ? 'required' : 'nullable'), $rule]);
        }
        $data = ApiRequest::normalizeDates($request->all());
        $request->replace($data);
        if (($this->resourceKey($request) ?? null) === 'discounts' && ($request->input('type') ?? null) === 'product_specific') {
            $rules['product_ids'] = ['required_without:category_ids', 'array', 'min:1'];
            $rules['product_ids.*'] = ['exists:products,id'];
        }
        if (($this->resourceKey($request) ?? null) === 'discounts') {
            $rules['category_ids'] = ['nullable', 'array'];
            $rules['category_ids.*'] = ['exists:categories,id'];
        }
        Validator::make($data, $rules, [
            'required' => 'وارد کردن فیلد :attribute الزامی است.',
            'numeric' => 'فیلد :attribute باید عددی باشد.',
            'date' => 'فرمت تاریخ :attribute معتبر نیست.',
        ])->validate();
    }

    private function activeProducts(Request $request): array
    {
        return $this->resourceKey($request) === 'discounts' ? Product::where('is_active', true)->orderBy('name')->get()->all() : [];
    }

    private function activeCategories(Request $request): array
    {
        return $this->resourceKey($request) === 'discounts' ? Category::where('is_active', true)->orderBy('title')->get()->all() : [];
    }

    private function categoriesWithProducts(Request $request): array
    {
        return $this->resourceKey($request) === 'discounts'
            ? Category::where('is_active', true)->with(['products' => fn ($products) => $products->where('is_active', true)->orderBy('name')])->orderBy('title')->get()->all()
            : [];
    }

    private function selectedProductIds(Request $request, int $discountId): array
    {
        return $this->resourceKey($request) === 'discounts' ? DB::table('discount_products')->where('discount_id', $discountId)->pluck('product_id')->all() : [];
    }

    private function selectedCategoryIds(Request $request, int $discountId): array
    {
        return $this->resourceKey($request) === 'discounts'
            ? DB::table('discount_products')->join('products', 'products.id', '=', 'discount_products.product_id')->where('discount_id', $discountId)->distinct()->pluck('products.category_id')->all()
            : [];
    }

    private function syncDiscountProducts(Request $request, int $discountId): void
    {
        if ($this->resourceKey($request) !== 'discounts') return;
        DB::table('discount_products')->where('discount_id', $discountId)->delete();
        $categoryProductIds = Product::whereIn('category_id', $request->input('category_ids', []))->pluck('id')->all();
        $productIds = array_values(array_unique(array_merge($categoryProductIds, $request->input('product_ids', []))));
        $rows = collect($productIds)->map(fn ($productId) => ['discount_id' => $discountId, 'product_id' => $productId, 'created_at' => now(), 'updated_at' => now()])->all();
        if ($rows !== []) DB::table('discount_products')->insert($rows);
    }

    private function rules(array $resource): array
    {
        $rules = [];
        foreach ($resource['fields'] as $field) {
            $rule = match ($field['type']) {
                'number' => 'numeric',
                'date' => 'date',
                'checkbox' => 'boolean',
                'select' => 'string',
                default => 'string',
            };
            $rules[$field['name']] = ($field['required'] ?? false) ? ['required', $rule] : ['nullable', $rule];
        }
        return $rules;
    }
}
