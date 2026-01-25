<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Http\Requests\StoreProductRequest;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Exports\ProductsExport;
use Maatwebsite\Excel\Facades\Excel;

class ProductController extends Controller
{
    protected $productRepository;

    public function __construct(ProductRepositoryInterface $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    public function index(Request $request)
    {
        $products = $this->productRepository->getAll($request->all());
        return ProductResource::collection($products);
    }

    public function show($id)
    {
        $product = $this->productRepository->findById($id);
        return new ProductResource($product);
    }

    public function store(StoreProductRequest $request)
    {
        $product = $this->productRepository->store($request->validated());

        return (new ProductResource($product))
            ->additional(['message' => 'Tạo sản phẩm thành công']);
    }


    public function update(Request $request, $id)
    {
        $product = $this->productRepository->update($id, $request->all());

        return (new ProductResource($product))
            ->additional(['message' => 'Cập nhật sản phẩm thành công']);
    }


    public function destroy($id): JsonResponse
    {
        $this->productRepository->delete($id);

        return response()->json(['message' => 'Sản phẩm đã được đưa vào thùng rác']);
    }


    public function restore($id): JsonResponse
    {
        $this->productRepository->restore($id);

        return response()->json(['message' => 'Phục hồi sản phẩm thành công']);
    }


    public function bulkaddTag(Request $request): JsonResponse
    {
        $request->validate([
            'product_ids' => 'required|array',
            'product_ids.*' => 'exists:products,id',
            'tag' => 'required|string'
        ]);

        $this->productRepository->addTags($request->product_ids, $request->tag);

        return response()->json(['message' => 'Gắn tag hàng loạt thành công']);
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt|max:2048'
        ]);

        $path = $request->file('file')->getRealPath();
        $this->productRepository->importCSV($path);

        return response()->json(['message' => 'Import dữ liệu thành công!']);
    }

    public function export(Request $request)
    {
        $fileName = 'products_export_' . now()->format('Ymd_His') . '.xlsx';

        return Excel::download(new ProductsExport($request), $fileName);
    }
}