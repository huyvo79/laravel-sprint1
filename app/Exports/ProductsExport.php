<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromQuery, WithHeadings, WithMapping
{
    protected $request;

    public function __construct($request)
    {
        $this->request = $request;
    }

    public function query()
    {
        $query = Product::query()->with('variants');

        if ($this->request->has('ids')) {
            $ids = explode(',', $this->request->ids);
            return $query->whereIn('id', $ids);
        }

        if ($this->request->has('status')) {
            $query->where('status', $this->request->status);
        }
        if ($this->request->has('tag')) {
            $query->where('tags', 'like', '%' . $this->request->tag . '%');
        }

        if ($this->request->has('page')) {
            $perPage = $this->request->get('per_page', 10); // Mặc định 10
            $page = $this->request->page;
            $query->skip(($page - 1) * $perPage)->take($perPage);
        }

        return $query;
    }

    public function headings(): array
    {
        return ['ID', 'Tên sản phẩm', 'Slug', 'Trạng thái', 'Tags', 'Giá biến thể', 'Số lượng'];
    }

    public function map($product): array
    {
        $rows = [];
        foreach ($product->variants as $variant) {
            $rows[] = [
                $product->id,
                $product->title,
                $product->slug,
                $product->status,
                $product->tags,
                $variant->price,
                $variant->inventory_quantity,
            ];
        }
        return $rows;
    }
}