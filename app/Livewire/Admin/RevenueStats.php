<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Booking;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class RevenueStats extends Component
{
    // 篩選條件
    public $dateFilter = 'this_month'; // all, today, this_week, this_month, this_year, custom
    public $startDate = '';
    public $endDate = '';
    public $styleFilter = 'all'; // all, single_color, design

    public function mount()
    {
        // 預設設定當月的開始和結束日期
        $this->setDefaultDates();
    }

    private function setDefaultDates()
    {
        $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
        $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
    }

    public function getRevenueStatsProperty()
    {
        $query = Booking::where('status', Booking::STATUS_COMPLETED)
            ->whereNotNull('amount');

        // 應用日期篩選
        $query = $this->applyDateFilter($query);

        // 應用款式篩選
        if ($this->styleFilter !== 'all') {
            $query->where('style_type', $this->styleFilter);
        }

        return [
            'total_revenue' => $query->sum('amount'),
            'total_bookings' => $query->count(),
            'average_amount' => $query->avg('amount'),
            'daily_revenue' => $this->getDailyRevenue(),
            'style_breakdown' => $this->getStyleBreakdown(),
            'monthly_trend' => $this->getMonthlyTrend(),
        ];
    }

    private function applyDateFilter($query)
    {
        switch ($this->dateFilter) {
            case 'all':
                // 不加任何日期限制，顯示全部資料
                return $query;
            case 'today':
                return $query->whereDate('booking_time', Carbon::today());
            case 'this_week':
                return $query->whereBetween('booking_time', [
                    Carbon::now()->startOfWeek(),
                    Carbon::now()->endOfWeek()
                ]);
            case 'this_month':
                return $query->whereBetween('booking_time', [
                    Carbon::now()->startOfMonth(),
                    Carbon::now()->endOfMonth()
                ]);
            case 'this_year':
                return $query->whereBetween('booking_time', [
                    Carbon::now()->startOfYear(),
                    Carbon::now()->endOfYear()
                ]);
            case 'custom':
                if ($this->startDate && $this->endDate) {
                    return $query->whereBetween('booking_time', [
                        Carbon::parse($this->startDate)->startOfDay(),
                        Carbon::parse($this->endDate)->endOfDay()
                    ]);
                }
                return $query;
            default:
                return $query;
        }
    }

    private function getDailyRevenue($baseQuery = null)
    {
        $query = Booking::where('status', Booking::STATUS_COMPLETED)
            ->whereNotNull('amount');

        // 應用日期篩選
        $query = $this->applyDateFilter($query);

        // 應用款式篩選
        if ($this->styleFilter !== 'all') {
            $query->where('style_type', $this->styleFilter);
        }

        return $query->select(
                DB::raw('DATE(booking_time) as date'),
                DB::raw('SUM(amount) as revenue'),
                DB::raw('COUNT(*) as bookings')
            )
            ->groupBy(DB::raw('DATE(booking_time)'))
            ->orderBy(DB::raw('DATE(booking_time)'))
            ->get();
    }

    private function getStyleBreakdown($baseQuery = null)
    {
        $query = Booking::where('status', Booking::STATUS_COMPLETED)
            ->whereNotNull('amount');

        // 應用日期篩選
        $query = $this->applyDateFilter($query);

        // 應用款式篩選
        if ($this->styleFilter !== 'all') {
            $query->where('style_type', $this->styleFilter);
        }

        return $query->select(
                'style_type',
                DB::raw('SUM(amount) as revenue'),
                DB::raw('COUNT(*) as bookings'),
                DB::raw('AVG(amount) as average')
            )
            ->groupBy('style_type')
            ->orderBy('style_type')
            ->get();
    }

    private function getMonthlyTrend()
    {
        return Booking::where('status', Booking::STATUS_COMPLETED)
            ->whereNotNull('amount')
            ->where('booking_time', '>=', Carbon::now()->subMonths(5)->startOfMonth())
            ->select(
                DB::raw('YEAR(booking_time) as year'),
                DB::raw('MONTH(booking_time) as month'),
                DB::raw('SUM(amount) as revenue'),
                DB::raw('COUNT(*) as bookings')
            )
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get()
            ->map(function ($item) {
                $item->month_name = Carbon::create($item->year, $item->month, 1)->format('Y年m月');
                return $item;
            });
    }

    public function filterAll()
    {
        $this->dateFilter = 'all';
    }

    public function filterToday()
    {
        $this->dateFilter = 'today';
    }

    public function filterThisWeek()
    {
        $this->dateFilter = 'this_week';
    }

    public function filterThisMonth()
    {
        $this->dateFilter = 'this_month';
    }

    public function filterThisYear()
    {
        $this->dateFilter = 'this_year';
    }

    public function filterCustom()
    {
        $this->dateFilter = 'custom';
    }

    public function clearFilters()
    {
        $this->dateFilter = 'this_month';
        $this->styleFilter = 'all';
        $this->setDefaultDates();
    }

    public function updatedDateFilter()
    {
        if ($this->dateFilter !== 'custom') {
            $this->setDefaultDates();
        }
    }

    public function render()
    {
        return view('livewire.admin.revenue-stats', [
            'stats' => $this->revenueStats,
        ])->layout('layouts.app');
    }
} 