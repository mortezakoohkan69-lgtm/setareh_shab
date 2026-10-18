<?php

namespace Modules\Dashboard\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Cheque;
use App\Models\Product;
use App\Models\ProductRecipe;
use Illuminate\View\View;
use Morilog\Jalali\Jalalian;
use Throwable;

class DashboardController extends Controller
{
    public function index(): View
    {
        $lastUpdate = strtr(Jalalian::now()->format('H:i'), ['0' => '۰', '1' => '۱', '2' => '۲', '3' => '۳', '4' => '۴', '5' => '۵', '6' => '۶', '7' => '۷', '8' => '۸', '9' => '۹']);

        return view('dashboard::index', [
            'stats' => [
                'products_count' => $this->safeCount(fn () => Product::count()),
                'categories_count' => $this->safeCount(fn () => Category::where('is_active', true)->count()),
                'cheques_count' => $this->safeCount(fn () => Cheque::count()),
                'recipes_count' => $this->safeCount(fn () => ProductRecipe::count()),
                'last_update' => $lastUpdate,
            ],
            'breadcrumbs' => [
                ['label' => 'داشبورد مدیریت', 'icon' => 'fas fa-home', 'active' => true],
            ],
        ]);
    }

    private function safeCount(callable $query): int
    {
        try {
            return (int) $query();
        } catch (Throwable) {
            return 0;
        }
    }
}
