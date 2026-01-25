<?php
namespace App\Repositories\Interfaces;

interface VariantRepositoryInterface
{
    public function getByProduct($productId, array $filters);
    public function findById($id);
    public function store(array $data);
    public function update($id, array $data);
    public function destroy($id);
}