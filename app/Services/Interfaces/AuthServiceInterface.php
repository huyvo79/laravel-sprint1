<?php

namespace App\Services\Interfaces;

interface AuthServiceInterface
{
    public function register(array $data);
    public function login(array $data);
    public function logout($user);
    public function verifyEmail($id, $hash);
    public function forgotPassword(string $email);
    public function resetPassword(array $data);
}