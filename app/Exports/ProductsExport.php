<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * Lấy tất cả sản phẩm cùng các biến thể đi kèm
    */
    public function collection()
    {
        return Product::with('variants')->get();
    }

    /**
    * Định nghĩa tiêu đề cho các cột trong file Excel
    */
    public function headings(): array
    {
        return [
            'ID Sản Phẩm',
            'Tên Sản Phẩm',
            'Trạng Thái',
            'Tags',
            'Giá Biến Thể',
            'Số Lượng Kho'
        ];
    }

    /**
    * Xử lý đổ dữ liệu. Vì một sản phẩm có nhiều biến thể, ta sẽ map chúng ra từng dòng
    */
    public function map($product): array
    {
        $rows = [];
        foreach ($product->variants as $variant) {
            $rows[] = [
                $product->id,
                $product->title,
                $product->status,
                $product->tags,
                $variant->price,
                $variant->inventory_quantity
            ];
        }
        return $rows;
    }
}