<?php

namespace App\Services;

use App\Models\LegalEntity;
use App\Repositories\Contracts\LegalEntityRepositoryInterface;

class LegalEntityService
{
    public function __construct(
        protected LegalEntityRepositoryInterface $legalEntityRepository
    ) {}

    public function getAll()
    {
        return $this->legalEntityRepository->getAll();
    }

    public function getById(string $publicId): ?LegalEntity
    {
        return $this->legalEntityRepository->getById($publicId);
    }

    public function create(array $data): LegalEntity
    {
        return $this->legalEntityRepository->create($data);
    }

    public function update(
        LegalEntity $legalEntity,
        array $data
    ): bool {
        return $this->legalEntityRepository->update(
            $legalEntity,
            $data
        );
    }

    public function delete(LegalEntity $legalEntity): bool
    {
        return $this->legalEntityRepository->delete($legalEntity);
    }
}