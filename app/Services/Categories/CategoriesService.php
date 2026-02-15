<?php



namespace App\Services\Categories;

use App\Repository\Categories\CategoriesRepository;

class CategoriesService
{
    public function __construct(
        private CategoriesRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function getAll()
    {
        return $this->repository->getAll();
    }

    public function findById(int $id)
    {
        return $this->repository->findById($id);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function update(int $id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function count(): int
    {
        return $this->repository->count();
    }
}
