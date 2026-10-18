<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Requests\Api\CustomerRequest;
use App\Http\Resources\Api\CustomerResource;
use App\Models\Customer;
class CustomerController extends CrudController { protected string $modelClass = Customer::class; protected string $resourceClass = CustomerResource::class; protected string $requestClass = CustomerRequest::class; public function purchases(int $customer) { return $this->success(Customer::findOrFail($customer)->orders()->with('items')->latest()->paginate(20), 'سوابق خرید دریافت شد.'); } }
