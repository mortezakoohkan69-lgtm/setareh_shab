<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Requests\Api\ExpenseRequest;
use App\Http\Resources\Api\ExpenseResource;
use App\Models\BankAccount;
use App\Models\Expense;
use Illuminate\Support\Facades\DB;
class ExpenseController extends CrudController { protected string $modelClass = Expense::class; protected string $resourceClass = ExpenseResource::class; protected string $requestClass = ExpenseRequest::class; public function store($request) { $data = $this->validated($request); $data['user_id'] = $request->user()->id; $model = DB::transaction(function () use ($data) { if (!empty($data['bank_account_id'])) BankAccount::lockForUpdate()->findOrFail($data['bank_account_id'])->decrement('current_balance', $data['amount']); return Expense::create($data); }); return $this->success(new ExpenseResource($model), 'هزینه ثبت شد.', 201); } }
