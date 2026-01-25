<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\VariantResource;
use App\Repositories\Interfaces\VariantRepositoryInterface;
use Illuminate\Http\Request;
use Exception;

class VariantController extends Controller
{
    protected $variantRepo;

    public function __construct(VariantRepositoryInterface $variantRepo)
    {
        $this->variantRepo = $variantRepo;
    }

    /**
     * Lấy danh sách variants của một sản phẩm cụ thể
     */
    public function index(Request $request, $productId)
    {
        $variants = $this->variantRepo->getByProduct($productId, $request->all());
        return VariantResource::collection($variants);
    }

    /**
     * Cập nhật một biến thể lẻ
     */
    public function update(Request $request, $id)
    {
        // Bạn nên tạo UpdateVariantRequest để validate dữ liệu ở đây
        $variant = $this->variantRepo->update($id, $request->all());
        return new VariantResource($variant);
    }

    /**
     * Xóa vĩnh viễn biến thể (Hard Delete)
     */
    public function destroy($id)
    {
        try {
            $this->variantRepo->destroy($id);
            return response()->json([
                'message' => 'Xóa biến thể thành công.'
            ], 200);
        } catch (Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 400);
        }
    }
}