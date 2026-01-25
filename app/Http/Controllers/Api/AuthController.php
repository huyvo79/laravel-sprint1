<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Services\Interfaces\AuthServiceInterface;
use Illuminate\Http\JsonResponse;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use App\Http\Requests\Auth\ForgotPasswordRequest;
use App\Http\Requests\Auth\ResetPasswordRequest;

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
            'status' => 'success',
            'message' => 'Tạo tài khoản thành công. Vui lòng kiểm tra email để xác thực!',
            'data' => $user
        ], 201);
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->authService->login($request->validated());

        if (!$result) {
            return response()->json([
                'message' => 'Thông tin đăng nhập không chính xác.'
            ], 401);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Đăng nhập thành công!',
            'data' => $result
        ]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->authService->logout($request->user());

        return response()->json([
            'status' => 'success',
            'message' => 'Đã đăng xuất và vô hiệu hóa Token thành công.'
        ]);
    }

    public function verifyEmail(Request $request, $id, $hash)
    {
        $result = $this->authService->verifyEmail($id, $hash);

        return response()->json($result);
    }


    public function forgotPassword(ForgotPasswordRequest $request)
    {
        $result = $this->authService->forgotPassword($request->email);
        return response()->json($result);
    }

    public function resetPassword(ResetPasswordRequest $request)
    {
        $result = $this->authService->resetPassword($request->validated());
        return response()->json($result);
    }
}
