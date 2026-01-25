<?php

namespace App\Services\Auth;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\Services\Interfaces\AuthServiceInterface;
use Illuminate\Support\Facades\Hash;
use App\Notifications\VerifyEmailNotification;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Support\Facades\Password;
class AuthService implements AuthServiceInterface
{
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function register(array $data)
    {
        $data['password'] = Hash::make($data['password']);
        $user = $this->userRepo->create($data);
        $user->notify(new VerifyEmailNotification());
        return $user;
    }

    public function login(array $data)
    {
        $user = $this->userRepo->findByEmail($data['email']);

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return null;
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        return [
            'user' => $user,
            'token' => $token
        ];
    }

    public function logout($user)
    {
        return $user->currentAccessToken()->delete();
    }

    public function verifyEmail($id, $hash)
    {
        $user = $this->userRepo->findById($id);

        if (!$user) {
            return ['status' => 'error', 'message' => 'Người dùng không tồn tại.'];
        }

        if (!hash_equals((string) $hash, sha1($user->getEmailForVerification()))) {
            return ['status' => 'error', 'message' => 'Liên kết xác thực không hợp lệ.'];
        }

        if ($user->hasVerifiedEmail()) {
            return ['status' => 'success', 'message' => 'Email đã được xác thực trước đó.'];
        }

        $user->markEmailAsVerified();

        return ['status' => 'success', 'message' => 'Xác thực email thành công!'];
    }

    public function forgotPassword(string $email)
    {
        $user = $this->userRepo->findByEmail($email);
        if (!$user)
            return ['status' => 'error', 'message' => 'Email không tồn tại!'];

        $token = Password::createToken($user);

        $user->notify(new ResetPasswordNotification($token));

        return ['status' => 'success', 'message' => 'Link đặt lại mật khẩu đã được gửi vào Email!'];
    }

    public function resetPassword(array $data)
    {
        $status = Password::reset($data, function ($user, $password) {
            $user->forceFill(['password' => Hash::make($password)])->save();
        });

        return $status === Password::PASSWORD_RESET
            ? ['status' => 'success', 'message' => 'Mật khẩu đã được cập nhật thành công!']
            : ['status' => 'error', 'message' => 'Mã xác thực không hợp lệ hoặc đã hết hạn!'];
    }
}