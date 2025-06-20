<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Booking;
use App\Models\BookingImage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AdminBookingActions extends Component
{
    public $booking;

    // 模態窗狀態
    public $showDetailsModal = false;
    public $showEditModal = false;
    public $showCancellationModal = false;

    // 表單欄位
    public $amount = '';
    public $rejection_reason = '';

    // 編輯表單欄位
    public $edit_status = '';
    public $edit_booking_time = '';
    public $edit_style_type = '';
    public $edit_need_removal = false;
    public $edit_notes = '';
    public $edit_customer_name = '';
    public $edit_customer_line_name = '';
    public $edit_customer_line_id = '';
    public $edit_customer_phone = '';
    public $edit_amount = '';

    protected $rules = [
        'amount' => 'nullable|numeric|min:0',
        'rejection_reason' => 'required|string|min:5|max:500',
        'edit_status' => 'required|in:pending,approved,cancelled,completed',
        'edit_booking_time' => 'required|date',
        'edit_style_type' => 'required|in:single_color,design',
        'edit_customer_name' => 'required|string|max:50',
        'edit_customer_line_name' => 'required|string|max:50',
        'edit_customer_line_id' => 'nullable|string|max:50',
        'edit_customer_phone' => 'required|string|max:20',
        'edit_amount' => 'nullable|numeric|min:0',
        'edit_notes' => 'nullable|string|max:500',
    ];

    public function mount($booking)
    {
        if (!$booking) {
            throw new \Exception('Booking data is required');
        }
        
        $this->booking = $booking;
        
        Log::info('AdminBookingActions mounted', [
            'booking_id' => $this->booking->id ?? 'null',
            'booking_number' => $this->booking->booking_number ?? 'null'
        ]);
    }

    // 顯示預約詳情
    public function showDetails()
    {
        if (!$this->booking) {
            Log::error('AdminBookingActions: booking is null in showDetails');
            return;
        }
        
        Log::info('AdminBookingActions: showDetails called', [
            'booking_id' => $this->booking->id,
            'booking_number' => $this->booking->booking_number
        ]);
        
        $this->showDetailsModal = true;
    }

    // 顯示編輯模態窗
    public function showEdit()
    {
        if (!$this->booking) {
            Log::error('AdminBookingActions: booking is null in showEdit');
            return;
        }
        
        Log::info('AdminBookingActions: showEdit called', [
            'booking_id' => $this->booking->id,
            'booking_number' => $this->booking->booking_number
        ]);
        
        $this->loadEditData();
        $this->showEditModal = true;
    }

    // 顯示取消申請模態窗
    public function showCancellation()
    {
        $this->showCancellationModal = true;
    }

    // 關閉所有模態窗
    public function closeModal()
    {
        $this->showDetailsModal = false;
        $this->showEditModal = false;
        $this->showCancellationModal = false;
        $this->resetForm();
    }

    // 重置表單
    private function resetForm()
    {
        $this->amount = '';
        $this->rejection_reason = '';
        $this->resetEditForm();
    }

    // 重置編輯表單
    private function resetEditForm()
    {
        $this->edit_status = '';
        $this->edit_booking_time = '';
        $this->edit_style_type = '';
        $this->edit_need_removal = false;
        $this->edit_notes = '';
        $this->edit_customer_name = '';
        $this->edit_customer_line_name = '';
        $this->edit_customer_line_id = '';
        $this->edit_customer_phone = '';
        $this->edit_amount = '';
    }

    // 載入編輯資料
    private function loadEditData()
    {
        $this->edit_status = $this->booking->status;
        $this->edit_booking_time = $this->booking->booking_time->format('Y-m-d\TH:i');
        $this->edit_style_type = $this->booking->style_type;
        $this->edit_need_removal = $this->booking->need_removal;
        $this->edit_notes = $this->booking->notes;
        $this->edit_customer_name = $this->booking->customer_name;
        $this->edit_customer_line_name = $this->booking->customer_line_name;
        $this->edit_customer_line_id = $this->booking->customer_line_id;
        $this->edit_customer_phone = $this->booking->customer_phone;
        $this->edit_amount = $this->booking->amount;
    }

    // 快速批准
    public function quickApprove()
    {
        try {
            if ($this->booking->status !== 'pending') {
                session()->flash('error', '此預約無法批准');
                return;
            }

            DB::beginTransaction();

            $updateData = ['status' => 'approved'];
            if ($this->amount) {
                $updateData['amount'] = $this->amount;
            }

            $this->booking->update($updateData);

            DB::commit();

            session()->flash('success', '預約已快速批准！');
            $this->dispatch('booking-updated');
            $this->closeModal();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Quick approve error: ' . $e->getMessage());
            session()->flash('error', '批准失敗：' . $e->getMessage());
        }
    }

    // 確認批准（在詳情模態窗中）
    public function confirmApproval()
    {
        try {
            if ($this->booking->status !== 'pending') {
                session()->flash('error', '此預約無法批准');
                return;
            }

            DB::beginTransaction();

            $updateData = ['status' => 'approved'];
            if ($this->amount) {
                $updateData['amount'] = $this->amount;
            }

            $this->booking->update($updateData);

            DB::commit();

            session()->flash('success', '預約已批准！');
            $this->dispatch('booking-updated');
            $this->closeModal();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Confirm approval error: ' . $e->getMessage());
            session()->flash('error', '批准失敗：' . $e->getMessage());
        }
    }

    // 更新預約
    public function updateBooking()
    {
        Log::info('AdminBookingActions: updateBooking called', [
            'booking_id' => $this->booking->id,
            'edit_data' => [
                'status' => $this->edit_status,
                'booking_time' => $this->edit_booking_time,
                'style_type' => $this->edit_style_type,
                'customer_name' => $this->edit_customer_name,
            ]
        ]);

        // 驗證表單資料
        $validatedData = $this->validate([
            'edit_status' => 'required|in:pending,approved,cancelled,completed',
            'edit_booking_time' => 'required|date',
            'edit_style_type' => 'required|in:single_color,design',
            'edit_customer_name' => 'required|string|max:50',
            'edit_customer_line_name' => 'required|string|max:50',
            'edit_customer_line_id' => 'nullable|string|max:50',
            'edit_customer_phone' => 'required|string|max:20',
            'edit_amount' => 'nullable|numeric|min:0',
            'edit_notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            $updateData = [
                'status' => $this->edit_status,
                'booking_time' => $this->edit_booking_time,
                'style_type' => $this->edit_style_type,
                'need_removal' => $this->edit_need_removal,
                'notes' => $this->edit_notes,
                'customer_name' => $this->edit_customer_name,
                'customer_line_name' => $this->edit_customer_line_name,
                'customer_line_id' => $this->edit_customer_line_id,
                'customer_phone' => $this->edit_customer_phone,
                'amount' => $this->edit_amount,
            ];

            Log::info('AdminBookingActions: Updating booking with data', $updateData);

            $this->booking->update($updateData);

            DB::commit();

            Log::info('AdminBookingActions: Booking updated successfully', [
                'booking_id' => $this->booking->id
            ]);

            session()->flash('success', '預約已更新！');
            $this->dispatch('booking-updated');
            $this->closeModal();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('AdminBookingActions: Update booking error', [
                'booking_id' => $this->booking->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            session()->flash('error', '更新失敗：' . $e->getMessage());
        }
    }

    // 批准取消申請
    public function approveCancellation()
    {
        try {
            if (!$this->booking->cancellation_requested) {
                session()->flash('error', '此預約沒有取消申請');
                return;
            }

            DB::beginTransaction();

            $this->booking->update([
                'status' => 'cancelled',
                'cancellation_requested' => false,
                'cancellation_requested_at' => null,
            ]);

            DB::commit();

            session()->flash('success', '取消申請已批准！');
            $this->dispatch('booking-updated');
            $this->closeModal();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Approve cancellation error: ' . $e->getMessage());
            session()->flash('error', '批准取消失敗：' . $e->getMessage());
        }
    }

    // 拒絕取消申請
    public function rejectCancellation()
    {
        $this->validate(['rejection_reason' => 'required|string|min:5|max:500']);

        try {
            if (!$this->booking->cancellation_requested) {
                session()->flash('error', '此預約沒有取消申請');
                return;
            }

            DB::beginTransaction();

            $this->booking->update([
                'cancellation_requested' => false,
                'cancellation_requested_at' => null,
                'cancellation_reason' => null,
                'rejection_reason' => $this->rejection_reason,
            ]);

            DB::commit();

            session()->flash('success', '取消申請已拒絕！');
            $this->dispatch('booking-updated');
            $this->closeModal();

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Reject cancellation error: ' . $e->getMessage());
            session()->flash('error', '拒絕取消失敗：' . $e->getMessage());
        }
    }

    // 圖片預覽
    public function openImageViewer($imageId)
    {
        $this->dispatch('openImageViewer', imageId: $imageId);
    }

    public function render()
    {
        return view('livewire.admin.admin-booking-actions');
    }
} 