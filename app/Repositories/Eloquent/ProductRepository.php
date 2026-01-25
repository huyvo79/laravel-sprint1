<?php
namespace App\Repositories\Eloquent;

use App\Models\Product;
use App\Models\Variant;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Support\Facades\DB;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAll(array $filters)
    {
        $perPage = min($filters['per_page'] ?? 10, 100);

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

    public function findById($id)
    {
        return Product::with('variants')->findOrFail($id);
    }

    public function store(array $data)
    {
        return DB::transaction(function () use ($data) {
            $product = Product::create($data);
            $product->variants()->createMany($data['variants']);
            return $product->load('variants');
        });
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id);
        return $product->delete();
    }

    public function update($id, array $data)
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product;
    }

    public function restore($id)
    {
        return Product::onlyTrashed()->findOrFail($id)->restore();
    }

    public function addTags(array $productIds, string $tag)
    {
        return Product::whereIn('id', $productIds)->update(['tags' => $tag]);
    }

    public function importCSV($filePath)
    {
        return DB::transaction(function () use ($filePath) {
            $file = fopen($filePath, 'r');
            $header = fgetcsv($file); 

            while (($row = fgetcsv($file)) !== FALSE) {
                $data = array_combine($header, $row);

                $product = Product::create([
                    'title' => $data['title'],
                    'description' => $data['description'],
                    'status' => $data['status'],
                    'tags' => $data['tags'],
                ]);

                $product->variants()->create([
                    'price' => $data['price'],
                    'inventory_quantity' => $data['inventory_quantity'],
                    'option_1' => $data['option_1'],
                ]);
            }
            fclose($file);
            return true;
        });
    }
}