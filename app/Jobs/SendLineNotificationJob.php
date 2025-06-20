<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\LineNotificationService;
use App\Models\Booking;

class SendLineNotificationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $booking;
    protected $notificationType;

    /**
     * Create a new job instance.
     */
    public function __construct(Booking $booking, string $notificationType)
    {
        $this->booking = $booking;
        $this->notificationType = $notificationType;
    }

    /**
     * Execute the job.
     */
    public function handle(LineNotificationService $lineService): void
    {
        switch ($this->notificationType) {
            case 'booking_received':
                $lineService->sendBookingReceived($this->booking);
                break;
            case 'booking_approved':
                $lineService->sendBookingApproved($this->booking);
                break;
            case 'booking_rejected':
                $lineService->sendBookingRejected($this->booking);
                break;
            case 'booking_cancelled':
                $lineService->sendBookingCancelled($this->booking);
                break;
            case 'booking_self_cancelled':
                $lineService->sendBookingSelfCancelled($this->booking);
                break;
            case 'booking_completed':
                $lineService->sendBookingCompleted($this->booking);
                break;
            case 'cancellation_requested':
                $lineService->sendCancellationRequested($this->booking);
                break;
            case 'cancellation_approved':
                $lineService->sendCancellationApproved($this->booking);
                break;
            case 'cancellation_rejected':
                $lineService->sendCancellationRejected($this->booking);
                break;
            case 'booking_auto_cancelled':
                $lineService->sendBookingAutoCancelled($this->booking);
                break;
            case 'confirmation_request':
                $lineService->sendBookingConfirmationRequest($this->booking);
                break;
            case 'booking_confirmed':
                $lineService->sendBookingConfirmed($this->booking);
                break;
        }
    }
} 