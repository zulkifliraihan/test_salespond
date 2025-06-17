<?php

namespace App\Http\Repositories\ContactRepository;

use App\Models\Contact;
use Illuminate\Contracts\Database\Query\Builder;

class ContactRepository implements ContactInterface
{
    private $contact;

    public function __construct(Contact $contact)
    {
        $this->contact = $contact;
    }

    public function query(): Builder
    {
        return $this->contact->query();
    }

    public function create(array $data): array
    {
        return $this->contact->create($data);
    }

    public function update(int $id, array $data): array
    {
        $contact = $this->contact->findOrFail($id);
        $contact->update($data);
        return $contact;
    }

    public function delete(int $id): array
    {
        $contact = $this->contact->findOrFail($id);
        $contact->delete();
        return $contact;
    }

}