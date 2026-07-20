<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\PaginatedApiRequest;
use App\Http\Resources\StaffResource;
use App\Models\Staff;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class StaffController extends Controller
{
    public function index(PaginatedApiRequest $request): AnonymousResourceCollection
    {
        return StaffResource::collection(Staff::query()->active()->paginate($request->perPage(24)));
    }
}
