<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Requests\Api\BankAccountRequest;
use App\Http\Resources\Api\BankAccountResource;
use App\Models\BankAccount;
class BankAccountController extends CrudController { protected string $modelClass = BankAccount::class; protected string $resourceClass = BankAccountResource::class; protected string $requestClass = BankAccountRequest::class; public function turnover(int $bankAccount) { $model = BankAccount::findOrFail($bankAccount); return $this->success(['expenses' => $model->expenses()->latest()->get(), 'purchases' => $model->purchases()->latest()->get(), 'orders' => $model->orders()->latest()->get()], 'گردش حساب دریافت شد.'); } }
