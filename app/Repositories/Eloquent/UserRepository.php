<?php

namespace App\Repositories\Eloquent;

use App\Models\User;
use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Models\Product;

class UserRepository implements UserRepositoryInterface
{
    public function create(array $data)
    {
        return User::create($data);
    }

    public function findByEmail(string $email)
    {
        return User::where('email', $email)->first();
    }

    public function findById(int $id)
    {
        return User::find($id);
    }

    public function getAllPaginated($filters, $perPage = 10)
    {
        $perPage = max(10, min($perPage, 100));

        return Product::query()
            ->with('variants')
            ->when($filters['search'] ?? null, function ($query, $search) {
                $query->where('title', 'like', "%{$search}%");
            })
            ->when($filters['status'] ?? null, function ($query, $status) {
                $query->where('status', $status);
            })
            ->when($filters['tag'] ?? null, function ($query, $tag) {
                $query->where('tags', 'like', "%{$tag}%");
            })
            ->paginate($perPage);
    }
}