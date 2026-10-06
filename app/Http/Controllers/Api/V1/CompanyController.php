<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyRequest;
use App\Http\Requests\UpdateCompanyRequest;
use App\Http\Resources\CompanyResource;
use App\Models\Company;
use App\Services\CompanyService;
use Illuminate\Http\JsonResponse;

class CompanyController extends Controller
{
    public function __construct(
        protected CompanyService $companyService
    ) {}

    /**
     * Display all companies
     */
    public function index()
    {
        $companies = $this->companyService->getAll();

        return CompanyResource::collection($companies);
    }

    /**
     * Store a new company
     */
    public function store(StoreCompanyRequest $request): JsonResponse
    {
        $company = $this->companyService->create(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Company created successfully.',
            'data' => new CompanyResource($company),
        ], 201);
    }

    /**
     * Display a single company
     */
    public function show(Company $company)
    {
        return new CompanyResource($company);
    }

    /**
     * Update company
     */
    public function update(
        UpdateCompanyRequest $request,
        Company $company
    ): JsonResponse {
        $this->companyService->update(
            $company,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Company updated successfully.',
            'data' => new CompanyResource($company->fresh()),
        ]);
    }

    /**
     * Delete company
     */
    public function destroy(Company $company): JsonResponse
    {
        $this->companyService->delete($company);

        return response()->json([
            'success' => true,
            'message' => 'Company deleted successfully.',
        ]);
    }
}