<?php
namespace App\Repositories\Eloquent;

use App\Models\Variant;
use App\Models\Product;
use App\Repositories\Interfaces\VariantRepositoryInterface;

class VariantRepository implements VariantRepositoryInterface
{
    public function getByProduct($productId, array $filters)
    {
        $perPage = min($filters['per_page'] ?? 10, 100);
        
        return Variant::where('product_id', $productId)
            ->paginate($perPage);
    }

    public function findById($id)
    {
        return Variant::findOrFail($id);
    }

    public function store(array $data)
    {
        return Variant::create($data);
    }

    public function update($id, array $data)
    {
        $variant = Variant::findOrFail($id);
        $variant->update($data);
        return $variant;
    }

    public function destroy($id)
    {
        $variant = Variant::findOrFail($id);
        $product = $variant->product;

        if ($product->variants()->count() <= 1) {
            throw new \Exception("Sản phẩm phải có ít nhất một biến thể. Không thể xóa!");
        }

        return $variant->forceDelete(); 
    }
}