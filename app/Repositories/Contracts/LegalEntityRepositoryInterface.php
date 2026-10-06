<?php

namespace App\Repositories\Contracts;

use App\Models\LegalEntity;

interface LegalEntityRepositoryInterface
{
    public function getAll();

    public function getById(string $publicId): ?LegalEntity;

    public function create(array $data): LegalEntity;

    public function update(LegalEntity $legalEntity, array $data): bool;

    public function delete(LegalEntity $legalEntity): bool;
}