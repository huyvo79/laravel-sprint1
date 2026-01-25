<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notification gửi Email đặt lại mật khẩu cho dự án Phước Hải Tourism.
 * implements ShouldQueue giúp gửi mail chạy ngầm qua hàng đợi (Queue).
 */
class ResetPasswordNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $token;

    /**
     * Khởi tạo Notification với Token bảo mật từ Laravel.
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * KHAI BÁO KÊNH GỬI (Hàm này cực kỳ quan trọng để hết lỗi 500).
     * * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable): array
    {
        return ['mail']; 
    }

    /**
     * Xây dựng nội dung Email gửi cho người dùng.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable): MailMessage
    {
        $frontendUrl = env('FRONTEND_URL', 'http://localhost:3000');

        $url = $frontendUrl . '/reset-password?token=' . $this->token . '&email=' . $notifiable->email;

        return (new MailMessage)
            ->subject('Đặt lại mật khẩu - Laravel Sprint 1')
            ->greeting('Chào ' . $notifiable->name . '!')
            ->line('Chúng tôi nhận được yêu cầu đặt lại mật khẩu cho tài khoản của bạn.')
            ->action('Đặt lại mật khẩu', $url) // Nút bấm chứa link reset.
            ->line('Nếu bạn không yêu cầu đặt lại mật khẩu, vui lòng bỏ qua thư này.')
            ->line('Lưu ý: Liên kết này sẽ hết hạn trong vòng 60 phút.');
    }

    public function toArray(object $notifiable): array
    {
        return [];
    }
}