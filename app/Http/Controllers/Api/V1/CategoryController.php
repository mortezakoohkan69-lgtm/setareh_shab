<?php
namespace App\Http\Controllers\Api\V1;
use App\Http\Requests\Api\CategoryRequest;
use App\Http\Resources\Api\CategoryResource;
use App\Models\Category;
class CategoryController extends CrudController { protected string $modelClass = Category::class; protected string $resourceClass = CategoryResource::class; protected string $requestClass = CategoryRequest::class; }
