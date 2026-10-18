<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Requests\Api\ChequeRequest;
use App\Http\Resources\Api\ChequeResource;
use App\Models\BankAccount;
use App\Models\Cheque;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class ChequeController extends CrudController { protected string $modelClass = Cheque::class; protected string $resourceClass = ChequeResource::class; protected string $requestClass = ChequeRequest::class; public function changeStatus(Request $request, int $cheque) { $data = $request->validate(['status' => ['required','in:cleared,bounced']]); $model = DB::transaction(function () use ($data, $cheque) { $item = Cheque::lockForUpdate()->findOrFail($cheque); if ($item->status !== 'cleared' && $data['status'] === 'cleared' && $item->bank_account_id) { $account = BankAccount::lockForUpdate()->findOrFail($item->bank_account_id); $account->increment('current_balance', $item->type === 'received' ? $item->amount : -$item->amount); } $item->update(['status' => $data['status']]); return $item; }); return $this->success(new ChequeResource($model), 'وضعیت چک تغییر کرد.'); } }
