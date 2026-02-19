<?php



namespace App\Services\Categories;

use App\Dto\ParamDto;
use App\Repository\Categories\CategoriesRepository;
use Illuminate\Support\Facades\Cache;

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

    public function getAll(?ParamDto $params = null)
    {

        $cacheKey = 'categories_all';
        return Cache::tags(['categories'])->remember($cacheKey, now()->addHours(6), function () use ($params) {
            return $this->repository->getAll($params);
        });
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

    public function getbySlug(string $slug)
    {
        return $this->repository->getBySlug($slug);
    }
}
