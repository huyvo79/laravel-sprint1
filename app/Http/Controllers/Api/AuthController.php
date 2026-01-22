<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Services\Interfaces\AuthServiceInterface;
use Illuminate\Http\JsonResponse;

class AuthController extends Controller
{
    protected $authService;

    public function __construct(AuthServiceInterface $authService)
    {
        $this->authService = $authService;
    }
    public function register(RegisterRequest $request): JsonResponse
    {
        $data = $request->validated();

        $user = $this->authService->register($data);

        return response()->json([
            'status'  => 'success',
            'message' => 'Tạo tài khoản thành công. Vui lòng kiểm tra email để xác thực!',
            'data'    => $user
        ], 201);
    }
}
