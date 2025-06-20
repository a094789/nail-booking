<?php
// app/Livewire/Admin/AdminDashboard.php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Booking;
use App\Models\User;
use App\Models\AvailableTime;
use Carbon\Carbon;

class AdminDashboard extends Component
{
    public function getTodayBookingsProperty()
    {
        return Booking::whereDate('booking_time', today())->count();
    }

    public function getPendingBookingsProperty()
    {
        return Booking::where('status', 'pending')->count();
    }

    public function getMonthlyBookingsProperty()
    {
        return Booking::whereMonth('created_at', now()->month)->count();
    }

    public function getTotalUsersProperty()
    {
        return User::where('role', 'user')->count();
    }

    public function getAvailableSlotsProperty()
    {
        return AvailableTime::where('is_available', true)->count();
    }

    public function getActiveBookingsProperty()
    {
        return Booking::whereIn('status', ['pending', 'approved'])->count();
    }

    public function getCompletedThisMonthProperty()
    {
        return Booking::where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->count();
    }

    public function getRecentBookingsProperty()
    {
        return Booking::with('user')
            ->latest()
            ->limit(5)
            ->get();
    }

    public function render()
    {
        return view('livewire.admin.admin-dashboard')
            ->layout('layouts.app');
    }
}