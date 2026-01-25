<?php
namespace App\Repositories\Interfaces;

interface ProductRepositoryInterface
{
    public function getAll(array $filters);
    public function findById($id);
    public function store(array $data);
    public function update($id, array $data);
    public function delete($id);
    public function restore($id);
    public function addTags(array $productIds, string $tag);
}