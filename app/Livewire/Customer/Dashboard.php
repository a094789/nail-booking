<?php

namespace App\Livewire\Customer;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Booking;
use App\Models\AvailableTime;
use Carbon\Carbon;

class Dashboard extends Component
{
    public $user;
    public $recentBookings = [];
    public $availableTimes = [];
    public $monthlyBookingCount = 0;
    public $monthlyBookingLimit = 3;

    public function mount()
    {
        $this->user = Auth::user();
        $this->loadRecentBookings();
        $this->loadAvailableTimes();
        $this->loadMonthlyBookingStats();
    }

    public function loadRecentBookings()
    {
        $this->recentBookings = Booking::where('user_id', $this->user->id)
            ->orderBy('created_at', 'desc')
            ->limit(3)
            ->get();
    }

    public function loadAvailableTimes()
    {
        $this->availableTimes = AvailableTime::where('is_available', true)
            ->where('available_time', '>=', Carbon::now()->addDays(3))
            ->orderBy('available_time', 'asc')
            ->limit(6)
            ->get();
    }

    public function loadMonthlyBookingStats()
    {
        $currentMonth = Carbon::now()->format('Y-m');
        
        $this->monthlyBookingCount = Booking::where('user_id', $this->user->id)
            ->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', [$currentMonth])
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        // 從 user_profiles 表獲取限制
        $userProfile = $this->user->userProfile;
        if ($userProfile) {
            $this->monthlyBookingLimit = $userProfile->monthly_booking_limit;
        }
    }

    public function render()
    {
        return view('livewire.customer.dashboard')
        ->layout('layouts.app');
    }
}