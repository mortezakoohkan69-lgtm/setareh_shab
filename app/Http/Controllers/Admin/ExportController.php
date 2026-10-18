<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Modules\Store\Http\Controllers\StoreController;

class ExportController
{
    public function excel(string $resource): Response
    {
        [$config, $key] = $this->resolve($resource);
        $rows = DB::table($config['table'])->orderByDesc('id')->get($config['columns']);
        $content = "\xEF\xBB\xBF".implode(',', array_map([$this, 'escape'], $config['columns']))."\n";
        foreach ($rows as $row) $content .= implode(',', array_map(fn ($column) => $this->escape($row->{$column}), $config['columns']))."\n";
        return response($content, 200, ['Content-Type' => 'text/csv; charset=UTF-8', 'Content-Disposition' => "attachment; filename={$key}.csv"]);
    }

    public function print(string $resource): View
    {
        [$config] = $this->resolve($resource);
        return view('exports.table', ['resource' => $config, 'rows' => DB::table($config['table'])->latest('id')->get($config['columns'])]);
    }

    private function resolve(string $resource): array
    {
        $config = StoreController::resources()[$resource] ?? abort(404);
        abort_unless(in_array($resource, ['categories', 'products', 'cheques', 'purchases', 'orders', 'daily-sales-reports', 'product-sales-stats'], true), 404);
        return [$config, $resource];
    }

    private function escape(mixed $value): string
    {
        return '"'.str_replace('"', '""', (string) ($value ?? '')).'"';
    }
}
