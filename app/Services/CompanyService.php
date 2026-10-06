<?php

namespace App\Services;

use App\Models\Company;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class CompanyService
{
    public function __construct(
        protected CompanyRepositoryInterface $companyRepository
    ) {}

    public function getAll(): Collection
    {
        return $this->companyRepository->getAll();
    }

    public function getById(string $publicId): ?Company
    {
        return $this->companyRepository->getById($publicId);
    }

    public function create(array $data): Company
    {
        return $this->companyRepository->create($data);
    }

    public function update(Company $company, array $data): bool
    {
        return $this->companyRepository->update($company, $data);
    }

    public function delete(Company $company): bool
    {
        return $this->companyRepository->delete($company);
    }
}