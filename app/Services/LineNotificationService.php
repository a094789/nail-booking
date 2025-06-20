<?php

namespace App\Services;

use GuzzleHttp\Client;
use LINE\Clients\MessagingApi\Configuration;
use LINE\Clients\MessagingApi\Api\MessagingApiApi;
use LINE\Clients\MessagingApi\Model\TextMessage;
use LINE\Clients\MessagingApi\Model\FlexMessage;
use LINE\Clients\MessagingApi\Model\FlexContainer;
use LINE\Clients\MessagingApi\Model\PushMessageRequest;
use LINE\Constants\MessageType;
use App\Models\Booking;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class LineNotificationService
{
    private $messagingApi;

    public function __construct()
    {
        $client = new Client();
        $config = new Configuration();
        $config->setAccessToken(config('services.line.channel_access_token'));
        
        $this->messagingApi = new MessagingApiApi(
            client: $client,
            config: $config
        );
    }

    /**
     * 檢查 LINE 通知是否啟用
     */
    private function isNotificationEnabled()
    {
        return Cache::get('line_notification_enabled', config('line.notification_enabled', true));
    }

    /**
     * 發送預約已收到通知
     */
    public function sendBookingReceived(Booking $booking)
    {
        $user = $booking->user;
        
        if (!$user->line_id) {
            Log::warning('用戶沒有 LINE ID，無法發送通知', ['user_id' => $user->id]);
            return false;
        }

        $message = $this->buildBookingReceivedMessage($booking);
        return $this->sendMessage($user->line_id, $message);
    }

    /**
     * 發送預約審核通過通知
     */
    public function sendBookingApproved(Booking $booking)
    {
        $user = $booking->user;
        
        if (!$user->line_id) {
            Log::warning('用戶沒有 LINE ID，無法發送通知', ['user_id' => $user->id]);
            return false;
        }

        $message = $this->buildBookingApprovedMessage($booking);
        return $this->sendMessage($user->line_id, $message);
    }

    /**
     * 發送預約審核未通過通知
     */
    public function sendBookingRejected(Booking $booking)
    {
        $user = $booking->user;
        
        if (!$user->line_id) {
            Log::warning('用戶沒有 LINE ID，無法發送通知', ['user_id' => $user->id]);
            return false;
        }

        $message = $this->buildBookingRejectedMessage($booking);
        return $this->sendMessage($user->line_id, $message);
    }

    /**
     * 發送預約取消通知（管理員取消）
     */
    public function sendBookingCancelled(Booking $booking)
    {
        if (!$booking->user || !$booking->user->line_id) {
            Log::warning('無法發送預約取消通知：用戶無 LINE ID', [
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id,
            ]);
            return false;
        }

        $message = $this->buildBookingCancelledMessage($booking);
        return $this->sendMessage($booking->user->line_id, $message);
    }

    /**
     * 發送使用者自行取消預約通知
     */
    public function sendBookingSelfCancelled(Booking $booking)
    {
        if (!$booking->user || !$booking->user->line_id) {
            Log::warning('無法發送自行取消預約通知：用戶無 LINE ID', [
                'booking_id' => $booking->id,
                'user_id' => $booking->user_id
            ]);
            return false;
        }

        // 使用統一的取消訊息建構方法
        $message = $this->buildBookingCancelledMessage($booking);
        return $this->sendMessage($booking->user->line_id, $message);
    }

    /**
     * 發送預約完成通知
     */
    public function sendBookingCompleted(Booking $booking)
    {
        $user = $booking->user;
        
        if (!$user->line_id) {
            Log::warning('用戶沒有 LINE ID，無法發送通知', ['user_id' => $user->id]);
            return false;
        }

        $message = $this->buildBookingCompletedMessage($booking);
        return $this->sendMessage($user->line_id, $message);
    }

    /**
     * 發送取消申請通知
     */
    public function sendCancellationRequested(Booking $booking)
    {
        $user = $booking->user;
        
        if (!$user->line_id) {
            Log::warning('用戶沒有 LINE ID，無法發送通知', ['user_id' => $user->id]);
            return false;
        }

        $message = $this->buildCancellationRequestedMessage($booking);
        return $this->sendMessage($user->line_id, $message);
    }

    /**
     * 發送取消申請審核通過通知
     */
    public function sendCancellationApproved(Booking $booking)
    {
        $user = $booking->user;
        
        if (!$user->line_id) {
            Log::warning('用戶沒有 LINE ID，無法發送取消申請審核通過通知', ['user_id' => $user->id]);
            return false;
        }

        $message = $this->buildCancellationApprovedMessage($booking);
        return $this->sendMessage($user->line_id, $message);
    }

    /**
     * 發送取消申請審核未通過通知
     */
    public function sendCancellationRejected(Booking $booking)
    {
        $user = $booking->user;
        
        if (!$user->line_id) {
            Log::warning('用戶沒有 LINE ID，無法發送取消申請審核未通過通知', ['user_id' => $user->id]);
            return false;
        }

        $message = $this->buildCancellationRejectedMessage($booking);
        return $this->sendMessage($user->line_id, $message);
    }

    /**
     * 發送系統自動取消通知
     */
    public function sendBookingAutoCancelled(Booking $booking)
    {
        $user = $booking->user;
        
        if (!$user->line_id) {
            Log::warning('用戶沒有 LINE ID，無法發送通知', ['user_id' => $user->id]);
            return false;
        }

        $message = $this->buildBookingAutoCancelledMessage($booking);
        return $this->sendMessage($user->line_id, $message);
    }

    /**
     * 發送行前確認通知
     */
    public function sendBookingConfirmationRequest(Booking $booking)
    {
        $user = $booking->user;
        
        if (!$user->line_id) {
            Log::warning('用戶沒有 LINE ID，無法發送通知', ['user_id' => $user->id]);
            return false;
        }

        $message = $this->buildBookingConfirmationRequestMessage($booking);
        return $this->sendMessage($user->line_id, $message);
    }

    /**
     * 發送行前確認成功通知
     */
    public function sendBookingConfirmed(Booking $booking)
    {
        $user = $booking->user;
        
        if (!$user->line_id) {
            Log::warning('用戶沒有 LINE ID，無法發送確認成功通知', ['user_id' => $user->id]);
            return false;
        }

        $message = $this->buildBookingConfirmedMessage($booking);
        return $this->sendMessage($user->line_id, $message);
    }

    /**
     * 建立預約已收到訊息
     */
    private function buildBookingReceivedMessage(Booking $booking)
    {
        $text = "📋 預約已收到\n\n";
        $text .= "親愛的 {$booking->customer_name}，\n";
        $text .= "您的預約已收到，待管理員審核\n\n";
        $text .= "預約單號：{$booking->booking_number}\n";
        
        if ($booking->booking_time) {
            $text .= "預約日期：" . $booking->booking_time->format('Y/m/d') . "\n";
            $text .= "預約時間：" . $booking->booking_time->format('H:i') . "\n";
        }
        
        if ($booking->style_type) {
            $styleTypes = [
                'single_color' => '單色美甲',
                'design' => '造型美甲'
            ];
            $serviceType = $styleTypes[$booking->style_type] ?? $booking->style_type;
            $text .= "服務項目：{$serviceType}\n";
        }
        
        if ($booking->need_removal) {
            $text .= "卸甲服務：是\n";
        }
        
        $text .= "\n我們會盡快審核您的預約，請耐心等候。";

        return new TextMessage([
            'type' => MessageType::TEXT,
            'text' => $text
        ]);
    }

    /**
     * 建立預約審核通過訊息
     */
    private function buildBookingApprovedMessage(Booking $booking)
    {
        $text = "✅ 預約審核通過\n\n";
        $text .= "親愛的 {$booking->customer_name}，\n";
        $text .= "恭喜！您的預約已審核通過\n\n";
        $text .= "預約單號：{$booking->booking_number}\n";
        
        if ($booking->booking_time) {
            $text .= "預約日期：" . $booking->booking_time->format('Y/m/d') . "\n";
            $text .= "預約時間：" . $booking->booking_time->format('H:i') . "\n";
        }
        
        if ($booking->style_type) {
            $styleTypes = [
                'single_color' => '單色美甲',
                'design' => '造型美甲'
            ];
            $serviceType = $styleTypes[$booking->style_type] ?? $booking->style_type;
            $text .= "服務項目：{$serviceType}\n";
        }
        
        if ($booking->need_removal) {
            $text .= "卸甲服務：是\n";
        }
        
        $text .= "\n請準時前往，期待為您服務！";

        return new TextMessage([
            'type' => MessageType::TEXT,
            'text' => $text
        ]);
    }

    /**
     * 建立預約審核未通過訊息
     */
    private function buildBookingRejectedMessage(Booking $booking)
    {
        $text = "❌ 預約審核未通過\n\n";
        $text .= "親愛的 {$booking->customer_name}，\n";
        $text .= "很抱歉，您的預約未能通過審核\n\n";
        $text .= "預約單號：{$booking->booking_number}\n";
        $text .= "原因：" . ($booking->rejection_reason ?? '時間不可用') . "\n\n";
        $text .= "歡迎您重新選擇其他時間預約。";

        return new TextMessage([
            'type' => MessageType::TEXT,
            'text' => $text
        ]);
    }

    /**
     * 建立預約取消訊息
     */
    private function buildBookingCancelledMessage(Booking $booking)
    {
        // 🔑 根據 cancelled_by 欄位決定訊息內容
        switch ($booking->cancelled_by) {
            case 'user':
                // 用戶自行取消預約
                $text = "❌ 預約已自行取消\n\n";
                $text .= "親愛的 {$booking->customer_name}，\n";
                $text .= "您已自行取消預約\n\n";
                $text .= "預約單號：{$booking->booking_number}\n\n";
                $text .= "如有需要，歡迎您重新預約。";
                break;
                
            case 'admin':
                // 管理員取消/拒絕預約
                $text = "❌ 預約審核未通過\n\n";
                $text .= "親愛的 {$booking->customer_name}，\n";
                $text .= "很抱歉，您的預約未能通過審核\n\n";
                $text .= "預約單號：{$booking->booking_number}\n";
                $text .= "原因：" . ($booking->cancellation_reason ?? '時間不可用') . "\n\n";
                $text .= "歡迎您重新選擇其他時間預約。";
                break;
                
            case 'system':
                // 系統自動取消
                $text = "⏰ 系統自動取消\n\n";
                $text .= "親愛的 {$booking->customer_name}，\n";
                $text .= "因未確認行程，預約已自動取消\n\n";
                $text .= "預約單號：{$booking->booking_number}\n";
                
                if ($booking->booking_time) {
                    $text .= "原預約日期：" . $booking->booking_time->format('Y/m/d') . "\n";
                    $text .= "原預約時間：" . $booking->booking_time->format('H:i') . "\n";
                }
                
                $text .= "\n如需重新預約，歡迎您再次預約。";
                break;
                
            default:
                // 預設情況（向後兼容）
                $text = "🚫 預約已取消\n\n";
                $text .= "親愛的 {$booking->customer_name}，\n";
                $text .= "您的預約已取消\n\n";
                $text .= "預約單號：{$booking->booking_number}\n";
                $text .= "取消原因：" . ($booking->cancellation_reason ?? '用戶取消') . "\n\n";
                $text .= "如有需要，歡迎您重新預約。";
                break;
        }

        return new TextMessage([
            'type' => MessageType::TEXT,
            'text' => $text
        ]);
    }

    /**
     * 建立預約完成訊息
     */
    private function buildBookingCompletedMessage(Booking $booking)
    {
        $text = "🎉 服務完成\n\n";
        $text .= "親愛的 {$booking->customer_name}，\n";
        $text .= "感謝您的光臨！服務已完成\n\n";
        $text .= "訂單編號：{$booking->booking_number}\n";
        
        if ($booking->booking_time) {
            $text .= "服務日期：" . $booking->booking_time->format('Y/m/d') . "\n";
            $text .= "服務時間：" . $booking->booking_time->format('H:i') . "\n";
        }
        
        if ($booking->style_type) {
            $styleTypes = [
                'single_color' => '單色美甲',
                'design' => '造型美甲'
            ];
            $serviceType = $styleTypes[$booking->style_type] ?? $booking->style_type;
            $text .= "服務項目：{$serviceType}\n";
        }
        
        if ($booking->amount) {
            $text .= "消費金額：NT$ " . number_format($booking->amount) . "\n";
        }
        
        $text .= "\n期待下次再為您服務！";

        return new TextMessage([
            'type' => MessageType::TEXT,
            'text' => $text
        ]);
    }

    /**
     * 建立取消申請訊息
     */
    private function buildCancellationRequestedMessage(Booking $booking)
    {
        $text = "📝 取消申請已送出\n\n";
        $text .= "親愛的 {$booking->customer_name}，\n";
        $text .= "您的取消申請已送出，待審核\n\n";
        $text .= "預約單號：{$booking->booking_number}\n";
        
        if ($booking->booking_time) {
            $text .= "預約日期：" . $booking->booking_time->format('Y/m/d') . "\n";
            $text .= "預約時間：" . $booking->booking_time->format('H:i') . "\n";
        }
        
        $text .= "\n我們會盡快處理您的取消申請。";

        return new TextMessage([
            'type' => MessageType::TEXT,
            'text' => $text
        ]);
    }

    /**
     * 建立取消申請審核通過訊息
     */
    private function buildCancellationApprovedMessage(Booking $booking)
    {
        $text = "✅ 取消申請審核通過\n\n";
        $text .= "親愛的 {$booking->customer_name}，\n";
        $text .= "您的取消申請已審核通過\n\n";
        $text .= "預約單號：{$booking->booking_number}\n";
        
        if ($booking->booking_time) {
            $text .= "原預約日期：" . $booking->booking_time->format('Y/m/d') . "\n";
            $text .= "原預約時間：" . $booking->booking_time->format('H:i') . "\n";
        }
        
        if ($booking->cancellation_reason) {
            $text .= "取消原因：" . $booking->cancellation_reason . "\n";
        }
        
        $text .= "\n如有需要，歡迎您重新預約。";

        return new TextMessage([
            'type' => MessageType::TEXT,
            'text' => $text
        ]);
    }

    /**
     * 建立取消申請審核未通過訊息
     */
    private function buildCancellationRejectedMessage(Booking $booking)
    {
        $text = "❌ 取消申請審核未通過\n\n";
        $text .= "親愛的 {$booking->customer_name}，\n";
        $text .= "很抱歉，您的取消申請未能通過審核\n\n";
        $text .= "預約單號：{$booking->booking_number}\n";
        
        if ($booking->booking_time) {
            $text .= "預約日期：" . $booking->booking_time->format('Y/m/d') . "\n";
            $text .= "預約時間：" . $booking->booking_time->format('H:i') . "\n";
        }
        
        if ($booking->rejection_reason) {
            $text .= "拒絕原因：" . $booking->rejection_reason . "\n";
        }
        
        $text .= "\n請準時赴約，期待為您服務！";

        return new TextMessage([
            'type' => MessageType::TEXT,
            'text' => $text
        ]);
    }

    /**
     * 建立系統自動取消訊息
     */
    private function buildBookingAutoCancelledMessage(Booking $booking)
    {
        $text = "⏰ 系統自動取消\n\n";
        $text .= "親愛的 {$booking->customer_name}，\n";
        $text .= "因未確認行程，預約已自動取消\n\n";
        $text .= "預約單號：{$booking->booking_number}\n";
        
        if ($booking->booking_time) {
            $text .= "原預約日期：" . $booking->booking_time->format('Y/m/d') . "\n";
            $text .= "原預約時間：" . $booking->booking_time->format('H:i') . "\n";
        }
        
        $text .= "\n如需重新預約，歡迎您再次預約。";

        return new TextMessage([
            'type' => MessageType::TEXT,
            'text' => $text
        ]);
    }

    /**
     * 建立行前確認請求訊息
     */
    private function buildBookingConfirmationRequestMessage(Booking $booking)
    {
        // 🔑 使用安全的Token連結，如果沒有Token則不發送
        if (!$booking->confirmation_token) {
            Log::error('預約沒有確認Token，無法發送確認請求', [
                'booking_id' => $booking->id,
                'booking_number' => $booking->booking_number
            ]);
            return null;
        }
        
        $confirmUrl = "https://nn.wwcyu.com/booking/confirm/{$booking->confirmation_token}";
        $deadline = $booking->confirmation_deadline ? $booking->confirmation_deadline->format('Y/m/d H:i') : '今日 23:59';
        
        $text = "🔔 行前確認請求\n\n";
        $text .= "親愛的 {$booking->customer_name}，\n";
        $text .= "您明天有預約服務，請確認行程\n\n";
        $text .= "預約單號：{$booking->booking_number}\n";
        
        if ($booking->booking_time) {
            $text .= "預約日期：" . $booking->booking_time->format('Y/m/d') . "\n";
            $text .= "預約時間：" . $booking->booking_time->format('H:i') . "\n";
        }
        
        if ($booking->style_type) {
            $styleTypes = [
                'single_color' => '單色美甲',
                'design' => '造型美甲'
            ];
            $serviceType = $styleTypes[$booking->style_type] ?? $booking->style_type;
            $text .= "服務項目：{$serviceType}\n";
        }
        
        $text .= "\n🔒 請點擊以下安全連結確認：\n";
        $text .= $confirmUrl . "\n\n";
        $text .= "⚠️ 逾期未確認將自動取消預約\n";
        $text .= "📱 此連結僅供您本人使用，請勿轉發";

        return new TextMessage([
            'type' => MessageType::TEXT,
            'text' => $text
        ]);
    }

    /**
     * 建立行前確認成功訊息
     */
    private function buildBookingConfirmedMessage(Booking $booking)
    {
        $text = "✅ 行前確認成功\n\n";
        $text .= "親愛的 {$booking->customer_name}，\n";
        $text .= "感謝您完成行前確認！\n\n";
        $text .= "預約單號：{$booking->booking_number}\n";
        
        if ($booking->booking_time) {
            $text .= "預約日期：" . $booking->booking_time->format('Y/m/d') . "\n";
            $text .= "預約時間：" . $booking->booking_time->format('H:i') . "\n";
        }
        
        if ($booking->style_type) {
            $styleTypes = [
                'single_color' => '單色美甲',
                'design' => '造型美甲'
            ];
            $serviceType = $styleTypes[$booking->style_type] ?? $booking->style_type;
            $text .= "服務項目：{$serviceType}\n";
        }
        
        if ($booking->need_removal) {
            $text .= "卸甲服務：是\n";
        }
        
        $text .= "\n✨ 預約已確認，請準時前往\n";
        $text .= "期待為您提供優質服務！";

        return new TextMessage([
            'type' => MessageType::TEXT,
            'text' => $text
        ]);
    }



    /**
     * 測試方法：檢查訊息格式
     */
    public function testMessageFormat(Booking $booking)
    {
        $message = $this->buildBookingReceivedMessage($booking);
        
        // 記錄到日誌中查看
        Log::info('測試 LINE 通知格式', [
            'booking_id' => $booking->id,
            'style_type' => $booking->style_type,
            'need_removal' => $booking->need_removal,
            'message_text' => $message->getText()
        ]);
        
        return $message->getText();
    }

    /**
     * 發送訊息
     */
    private function sendMessage($lineId, $message)
    {
        // 檢查通知開關
        if (!$this->isNotificationEnabled()) {
            Log::info('LINE 通知已關閉，跳過發送', [
                'line_id' => $lineId,
                'message_type' => get_class($message)
            ]);
            return true; // 返回 true 表示「處理成功」，只是沒有實際發送
        }

        try {
            $request = new PushMessageRequest([
                'to' => $lineId,
                'messages' => [$message]
            ]);

            $response = $this->messagingApi->pushMessage($request);
            
            Log::info('LINE 通知發送成功', [
                'line_id' => $lineId,
                'message_type' => get_class($message)
            ]);
            
            return true;
        } catch (\Exception $e) {
            Log::error('LINE 通知發送失敗', [
                'line_id' => $lineId,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return false;
        }
    }
} 