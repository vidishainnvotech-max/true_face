<?php

namespace App\Repositories\Eloquent;

use App\Models\LegalEntity;
use App\Repositories\Contracts\LegalEntityRepositoryInterface;

class LegalEntityRepository implements LegalEntityRepositoryInterface
{
    public function getAll()
    {
        return LegalEntity::query()
            ->latest()
            ->get();
    }

    public function getById(string $publicId): ?LegalEntity
    {
        return LegalEntity::where(
            'public_id',
            $publicId
        )->first();
    }

    public function create(array $data): LegalEntity
    {
        return LegalEntity::create($data);
    }

    public function update(
        LegalEntity $legalEntity,
        array $data
    ): bool {
        return $legalEntity->update($data);
    }

    public function delete(LegalEntity $legalEntity): bool
    {
        return $legalEntity->delete();
    }
}