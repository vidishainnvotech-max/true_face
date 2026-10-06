<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLegalEntityRequest;
use App\Http\Requests\UpdateLegalEntityRequest;
use App\Http\Resources\LegalEntityResource;
use App\Models\LegalEntity;
use App\Services\LegalEntityService;
use Illuminate\Http\JsonResponse;

class LegalEntityController extends Controller
{
    public function __construct(
        protected LegalEntityService $legalEntityService
    ) {}

    /**
     * Display all legal entities.
     */
    public function index()
    {
        $legalEntities = $this->legalEntityService->getAll();

        return LegalEntityResource::collection($legalEntities);
    }

    /**
     * Store a new legal entity.
     */
    public function store(
        StoreLegalEntityRequest $request
    ): JsonResponse {
        $legalEntity = $this->legalEntityService->create(
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Legal entity created successfully.',
            'data' => new LegalEntityResource($legalEntity),
        ], 201);
    }

    /**
     * Display a single legal entity.
     */
    public function show(LegalEntity $legal_entity)
    {
        return new LegalEntityResource($legal_entity);
    }

    /**
     * Update a legal entity.
     */
    public function update(
        UpdateLegalEntityRequest $request,
        LegalEntity $legal_entity
    ): JsonResponse {
        $this->legalEntityService->update(
            $legal_entity,
            $request->validated()
        );

        return response()->json([
            'success' => true,
            'message' => 'Legal entity updated successfully.',
            'data' => new LegalEntityResource(
                $legal_entity->fresh()
            ),
        ]);
    }

    /**
     * Delete a legal entity.
     */
    public function destroy(
        LegalEntity $legal_entity
    ): JsonResponse {
        $this->legalEntityService->delete($legal_entity);

        return response()->json([
            'success' => true,
            'message' => 'Legal entity deleted successfully.',
        ]);
    }
}