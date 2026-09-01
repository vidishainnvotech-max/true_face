<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTenantRequest;
use App\Http\Requests\UpdateTenantRequest;
use App\Http\Resources\TenantResource;
use App\Models\Tenant;
use App\Services\TenantService;
use Illuminate\Http\JsonResponse;

class TenantController extends Controller
{
    public function __construct(
        protected TenantService $tenantService
    ) {}

    /**
     * Display all tenants
     */
    public function index()
    {
        $tenants = $this->tenantService->getAll();

        return TenantResource::collection($tenants);
    }

    /**
     * Store new tenant
     */
    public function store(StoreTenantRequest $request): JsonResponse
    {
        $tenant = $this->tenantService->create($request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Tenant created successfully.',
            'data' => new TenantResource($tenant),
        ], 201);
    }

    /**
     * Show single tenant
     */
    public function show(Tenant $tenant)
    {
        return new TenantResource($tenant);
    }

    /**
     * Update tenant
     */
    public function update(UpdateTenantRequest $request, Tenant $tenant): JsonResponse
    {
        $this->tenantService->update($tenant, $request->validated());

        return response()->json([
            'success' => true,
            'message' => 'Tenant updated successfully.',
            'data' => new TenantResource($tenant->fresh()),
        ]);
    }

    /**
     * Delete tenant
     */
    public function destroy(Tenant $tenant): JsonResponse
    {
        $this->tenantService->delete($tenant);

        return response()->json([
            'success' => true,
            'message' => 'Tenant deleted successfully.',
        ]);
    }
}