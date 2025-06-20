<?php
// app/Livewire/Admin/DailyBookingList.php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Booking;
use Carbon\Carbon;

class DailyBookingList extends Component
{
    public $selectedDate;
    public $viewMode = 'day'; // day, week, month
    public $currentWeekStart;
    public $currentMonth;
    
    public function mount()
    {
        $this->selectedDate = Carbon::now()->format('Y-m-d');
        $this->currentWeekStart = Carbon::now()->startOfWeek();
        $this->currentMonth = Carbon::now()->format('Y-m');
    }

    public function getTodayBookingsProperty()
    {
        return Booking::with(['user'])
            ->whereDate('booking_time', $this->selectedDate)
            ->whereIn('status', ['approved', 'pending'])
            ->orderBy('booking_time')
            ->get();
    }

    public function getWeekBookingsProperty()
    {
        $weekStart = Carbon::parse($this->currentWeekStart);
        $weekEnd = $weekStart->copy()->endOfWeek();
        
        return Booking::with(['user'])
            ->whereBetween('booking_time', [$weekStart, $weekEnd])
            ->whereIn('status', ['approved', 'pending'])
            ->orderBy('booking_time')
            ->get()
            ->groupBy(function ($booking) {
                return $booking->booking_time->format('Y-m-d');
            });
    }

    public function getMonthBookingsProperty()
    {
        $monthStart = Carbon::createFromFormat('Y-m', $this->currentMonth)->startOfMonth();
        $monthEnd = Carbon::createFromFormat('Y-m', $this->currentMonth)->endOfMonth();
        
        return Booking::with(['user'])
            ->whereBetween('booking_time', [$monthStart, $monthEnd])
            ->whereIn('status', ['approved', 'pending'])
            ->orderBy('booking_time')
            ->get()
            ->groupBy(function ($booking) {
                return $booking->booking_time->format('Y-m-d');
            });
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->viewMode = 'day';
    }

    public function previousPeriod()
    {
        switch ($this->viewMode) {
            case 'day':
                $this->selectedDate = Carbon::parse($this->selectedDate)->subDay()->format('Y-m-d');
                break;
            case 'week':
                $this->currentWeekStart = Carbon::parse($this->currentWeekStart)->subWeek();
                break;
            case 'month':
                $this->currentMonth = Carbon::createFromFormat('Y-m', $this->currentMonth)->subMonth()->format('Y-m');
                break;
        }
    }

    public function nextPeriod()
    {
        switch ($this->viewMode) {
            case 'day':
                $this->selectedDate = Carbon::parse($this->selectedDate)->addDay()->format('Y-m-d');
                break;
            case 'week':
                $this->currentWeekStart = Carbon::parse($this->currentWeekStart)->addWeek();
                break;
            case 'month':
                $this->currentMonth = Carbon::createFromFormat('Y-m', $this->currentMonth)->addMonth()->format('Y-m');
                break;
        }
    }

    public function setViewMode($mode)
    {
        $this->viewMode = $mode;
    }

    public function render()
    {
        $data = [];
        
        switch ($this->viewMode) {
            case 'day':
                $data['bookings'] = $this->todayBookings;
                break;
            case 'week':
                $data['bookings'] = $this->weekBookings;
                break;
            case 'month':
                $data['bookings'] = $this->monthBookings;
                break;
        }

        return view('livewire.admin.daily-booking-list', $data)
            ->layout('layouts.app');
    }
}