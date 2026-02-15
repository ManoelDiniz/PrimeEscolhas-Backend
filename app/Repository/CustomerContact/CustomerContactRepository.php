<?php


namespace App\Repository\CustomerContact;

use App\Models\CustomerContact;

class CustomerContactRepository
{
    public function __construct(
        private CustomerContact $model
    ) {
        $this->model = $model;
    }

    public function create(array $data): CustomerContact
    {
        return $this->model->create($data);
    }

    public function getAll()
    {
        return $this->model->all();
    }

    public function findById(int $id): ?CustomerContact
    {
        return $this->model->find($id);
    }

    public function delete(int $id): bool
    {
        $contact = $this->findById($id);
        if ($contact) {
            return $contact->delete();
        }
        return false;
    }

    public function update(int $id, array $data): ?CustomerContact
    {
        $contact = $this->findById($id);
        if ($contact) {
            $contact->update($data);
            return $contact;
        }
        return null;
    }

    public function count(): int
    {
        return $this->model->count();
    }

    public function getByNumber(int $number): ?CustomerContact
    {
        return $this->model->find($number);
    }
}
