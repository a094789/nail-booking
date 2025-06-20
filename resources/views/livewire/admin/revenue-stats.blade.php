{{-- resources/views/livewire/admin/revenue-stats.blade.php --}}
<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <h1 class="text-2xl font-bold text-gray-900">營業額統計</h1>
            <p class="mt-1 text-sm text-gray-600">已完成訂單的營業額分析</p>
        </div>
    </div>

    <!-- 統計卡片 -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <!-- 總營業額 -->
            <div class="bg-gradient-to-br from-green-50 to-emerald-100 rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-500 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-green-800">總營業額</p>
                        <p class="text-2xl font-bold text-green-900">
                            ${{ number_format($stats['total_revenue']) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- 完成訂單數 -->
            <div class="bg-gradient-to-br from-blue-50 to-cyan-100 rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-500 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-blue-800">完成訂單</p>
                        <p class="text-2xl font-bold text-blue-900">{{ $stats['total_bookings'] }}</p>
                    </div>
                </div>
            </div>

            <!-- 平均金額 -->
            <div class="bg-gradient-to-br from-purple-50 to-indigo-100 rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-purple-500 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-purple-800">平均金額</p>
                        <p class="text-2xl font-bold text-purple-900">
                            ${{ number_format($stats['average_amount']) }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- 篩選期間 -->
            <div class="bg-gradient-to-br from-yellow-50 to-orange-100 rounded-xl shadow-md p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-500 rounded-full">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                        </svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-yellow-800">篩選期間</p>
                        <p class="text-lg font-bold text-yellow-900">
                            @switch($dateFilter)
                                @case('all') 全部 @break
                                @case('today') 今天 @break
                                @case('this_week') 本週 @break
                                @case('this_month') 本月 @break
                                @case('this_year') 今年 @break
                                @case('custom') 自訂期間 @break
                                @default 本月
                            @endswitch
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- 篩選器 -->
        <div class="bg-white rounded-xl shadow-md mb-8 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">篩選條件</h3>
            
            <!-- 快速篩選按鈕 -->
            <div class="mb-6">
                <label class="block text-sm font-medium text-gray-700 mb-2">快速篩選</label>
                <div class="flex flex-wrap gap-2">
                    <button wire:click="filterAll"
                        class="px-4 py-2 text-sm rounded-lg transition-colors {{ $dateFilter === 'all' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        全部
                    </button>
                    <button wire:click="filterToday"
                        class="px-4 py-2 text-sm rounded-lg transition-colors {{ $dateFilter === 'today' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        今天
                    </button>
                    <button wire:click="filterThisWeek"
                        class="px-4 py-2 text-sm rounded-lg transition-colors {{ $dateFilter === 'this_week' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        本週
                    </button>
                    <button wire:click="filterThisMonth"
                        class="px-4 py-2 text-sm rounded-lg transition-colors {{ $dateFilter === 'this_month' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        本月
                    </button>
                    <button wire:click="filterThisYear"
                        class="px-4 py-2 text-sm rounded-lg transition-colors {{ $dateFilter === 'this_year' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        今年
                    </button>
                    <button wire:click="filterCustom"
                        class="px-4 py-2 text-sm rounded-lg transition-colors {{ $dateFilter === 'custom' ? 'bg-blue-100 text-blue-700' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        自訂期間
                    </button>
                    <button wire:click="clearFilters"
                        class="px-4 py-2 text-sm rounded-lg bg-red-100 text-red-700 hover:bg-red-200">
                        清除篩選
                    </button>
                </div>
            </div>

            <!-- 詳細篩選 -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                @if($dateFilter === 'custom')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">開始日期</label>
                    <input type="date" wire:model.live="startDate"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">結束日期</label>
                    <input type="date" wire:model.live="endDate"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                </div>
                @endif

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">服務類型</label>
                    <select wire:model.live="styleFilter"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500">
                        <option value="all">全部類型</option>
                        <option value="single_color">單色</option>
                        <option value="design">造型</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- 沒有資料時的提示 -->
        @if($stats['total_bookings'] == 0)
        <div class="bg-white rounded-xl shadow-md p-12 text-center">
            <svg class="w-16 h-16 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
            </svg>
            <h3 class="text-lg font-medium text-gray-900 mb-2">沒有營業額資料</h3>
            <p class="text-gray-600">在選定的時間範圍內沒有已完成的訂單</p>
        </div>
        @else
        
        <!-- 服務類型分析 -->
        @if($stats['style_breakdown']->count() > 0)
        <div class="bg-white rounded-xl shadow-md mb-8 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">服務類型分析</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">服務類型</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">營業額</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">訂單數</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">平均金額</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($stats['style_breakdown'] as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->style_type === 'single_color' ? '單色' : '造型' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${{ number_format($item->revenue) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->bookings }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${{ number_format($item->average) }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- 每日營業額明細 -->
        @if($stats['daily_revenue']->count() > 0)
        <div class="bg-white rounded-xl shadow-md mb-8 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">每日營業額明細</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">日期</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">營業額</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">訂單數</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($stats['daily_revenue'] as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ \Carbon\Carbon::parse($item->date)->format('Y-m-d') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${{ number_format($item->revenue) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->bookings }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif

        <!-- 月度趨勢 -->
        @if($stats['monthly_trend']->count() > 0)
        <div class="bg-white rounded-xl shadow-md mb-8 p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">月度趨勢</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">月份</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">營業額</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">訂單數</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($stats['monthly_trend'] as $item)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->month_name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                ${{ number_format($item->revenue) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                {{ $item->bookings }}
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
        @endif
    </div>
</div> 