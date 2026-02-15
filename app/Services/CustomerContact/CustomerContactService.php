<?php

namespace App\Services\CustomerContact;

use App\Repository\CustomerContact\CustomerContactRepository;
use Illuminate\Support\Facades\Http;

class CustomerContactService
{
    public function __construct(
        private CustomerContactRepository $repository
    ) {
        $this->repository = $repository;
    }

    public function create(array $data)
    {
        Http::post(env('DISCORD_WEBHOOK_URL'), [
            "content" => "**Nova mensagem de contato**\n\nNome: {$data['name']}\nEmail: {$data['email']}\nAssunto: {$data['subject']}\nMensagem: {$data['message']}\n \n \n \n \n"
        ]);
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
