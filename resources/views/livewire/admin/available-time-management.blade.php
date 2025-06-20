{{-- resources/views/livewire/admin/available-time-management.blade.php --}}

<div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">時段管理</h1>
                    <p class="mt-1 text-sm text-gray-600">設定可預約的服務時段</p>
                </div>
                
                <div class="mt-4 sm:mt-0 flex space-x-3">
                    <button wire:click="openBulkModal" 
                            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        快速新增時段
                    </button>
                    <a href="{{ route('admin.bookings.index') }}" 
                       class="inline-flex items-center px-4 py-2 bg-purple-600 text-white rounded-lg hover:bg-purple-700 transition-colors">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                        </svg>
                        返回預約管理
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Month Navigation -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">
        <div class="bg-white rounded-lg shadow p-6 mb-6 border border-gray-300">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-4">
                <div class="flex items-center space-x-4">
                    <button wire:click="previousMonth" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                        </svg>
                    </button>
                    <h3 class="text-lg font-medium text-gray-900">
                        {{ \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->format('Y年m月') }}
                    </h3>
                    <button wire:click="nextMonth" class="p-2 text-gray-500 hover:text-gray-700 hover:bg-gray-100 rounded-lg">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                        </svg>
                    </button>
                </div>
                <div class="mt-4 sm:mt-0">
                    <input type="month" wire:model.live="selectedMonth" 
                           class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-pink-500 focus:border-pink-500">
                </div>
            </div>
        </div>

        <!-- Calendar View -->
        <div class="bg-white rounded-lg shadow border border-gray-300 overflow-hidden">
            <div class="p-6">
                <!-- Calendar Header -->
                <div class="grid grid-cols-7 gap-1 mb-4">
                    @foreach(['日', '一', '二', '三', '四', '五', '六'] as $day)
                    <div class="p-3 text-center text-sm font-medium text-gray-500 bg-gray-50 rounded">
                        {{ $day }}
                    </div>
                    @endforeach
                </div>

                <!-- Calendar Body -->
                <div class="grid grid-cols-7 gap-1">
                    @php
                        $monthStart = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->startOfMonth();
                        $monthEnd = \Carbon\Carbon::createFromFormat('Y-m', $selectedMonth)->endOfMonth();
                        $calendarStart = $monthStart->copy()->startOfWeek(0); // 從週日開始
                        $calendarEnd = $monthEnd->copy()->endOfWeek(6); // 到週六結束
                        $currentDate = $calendarStart->copy();
                    @endphp

                    @while($currentDate <= $calendarEnd)
                        @php
                            $isCurrentMonth = $currentDate->month === $monthStart->month;
                            $isToday = $currentDate->isToday();
                            $isPast = $currentDate->isPast() && !$currentDate->isToday();
                            $isWeekend = $currentDate->isWeekend();
                            $dateStr = $currentDate->format('Y-m-d');
                            $daySlots = $timeSlots[$dateStr] ?? [];
                            $availableCount = count(array_filter($daySlots, fn($slot) => ($slot['is_available'] ?? true)));
                            $bookedCount = count($daySlots) - $availableCount;
                        @endphp

                        <div class="min-h-[120px] border border-gray-200 rounded-lg p-2 
                                    {{ $isCurrentMonth ? 'bg-white' : 'bg-gray-50' }}
                                    {{ $isToday ? 'ring-2 ring-pink-500 bg-pink-50' : '' }}
                                    {{ $isWeekend && $isCurrentMonth ? 'bg-blue-50' : '' }}">
                            
                            <!-- Date Header -->
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-sm font-medium 
                                           {{ $isCurrentMonth ? 'text-gray-900' : 'text-gray-400' }}
                                           {{ $isToday ? 'text-pink-700' : '' }}">
                                    {{ $currentDate->format('d') }}
                                </span>
                                @if($isToday)
                                    <span class="text-xs bg-pink-100 text-pink-600 px-1 rounded">今天</span>
                                @endif
                            </div>

                            @if($isCurrentMonth && !$isPast)
                                <!-- Add Time Button -->
                                <button wire:click="openTimeModal('{{ $dateStr }}')" 
                                        class="w-full mb-2 px-2 py-1 text-xs bg-green-100 text-green-700 rounded hover:bg-green-200 transition-colors">
                                    <svg class="w-3 h-3 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    新增時段
                                </button>

                                <!-- Time Slots -->
                                <div class="space-y-1">
                                    @foreach(array_slice($daySlots, 0, 3) as $slot)
                                        @php
                                            $slotTime = \Carbon\Carbon::parse($slot['available_time']);
                                            $isAvailable = $slot['is_available'] ?? true;
                                        @endphp
                                        <div class="flex items-center justify-between p-1 rounded text-xs
                                                   {{ $isAvailable ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                                            <span>{{ $slotTime->format('H:i') }}</span>
                                            @if($isAvailable)
                                                <button wire:click="deleteTimeSlot({{ $slot['id'] }})"
                                                        class="text-red-500 hover:text-red-700">
                                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    @endforeach

                                    @if(count($daySlots) > 3)
                                        <button wire:click="showDayDetail('{{ $dateStr }}')"
                                                class="w-full text-xs text-blue-600 hover:text-blue-800">
                                            還有 {{ count($daySlots) - 3 }} 個...
                                        </button>
                                    @endif
                                </div>

                                <!-- Summary -->
                                @if(count($daySlots) > 0)
                                    <div class="mt-2 text-xs text-gray-500">
                                        可預約: {{ $availableCount }} | 已預約: {{ $bookedCount }}
                                    </div>
                                @endif
                            @elseif($isPast)
                                <div class="text-xs text-gray-400 text-center mt-4">已過期</div>
                            @else
                                <!-- Other month dates - show slot count only -->
                                @if(count($daySlots) > 0)
                                    <div class="text-xs text-gray-400 text-center mt-4">
                                        {{ count($daySlots) }} 個時段
                                    </div>
                                @endif
                            @endif
                        </div>

                        @php $currentDate->addDay(); @endphp
                    @endwhile
                </div>
            </div>
        </div>
    </div>

    <!-- 單日時段詳細模態窗 -->
    @if($showTimeModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full border border-gray-300">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-green-500 to-blue-600 text-white p-6 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-bold">
                        新增時段 - {{ \Carbon\Carbon::parse($selectedDate)->format('m月d日') }}
                        ({{ \Carbon\Carbon::parse($selectedDate)->locale('zh_TW')->dayName }})
                    </h3>
                    <button wire:click="closeTimeModal" class="text-white hover:text-gray-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <div class="p-6">
                <!-- 自定義時間 -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">自定義時間</label>
                    <div class="flex space-x-2">
                        <input type="time" wire:model="customTime" 
                               class="flex-1 px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                        <button wire:click="addCustomTime"
                                class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                            新增
                        </button>
                    </div>
                </div>

                <!-- 批量新增 -->
                <div class="mb-6 border-t pt-4">
                    <label class="block text-sm font-medium text-gray-700 mb-3">批量新增</label>
                    <div class="grid grid-cols-3 gap-4 mb-3">
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">開始時間</label>
                            <input type="time" wire:model="batchStartTime" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">結束時間</label>
                            <input type="time" wire:model="batchEndTime" 
                                   class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-xs text-gray-500 mb-1">間隔(分鐘)</label>
                            <input type="number" wire:model="batchInterval" min="15" max="180" step="15"
                                   class="w-full px-3 py-2 border border-gray-300 rounded focus:ring-1 focus:ring-green-500">
                        </div>
                    </div>
                    <button wire:click="addBatchTimeSlots"
                            class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">
                        批量新增時段
                    </button>
                </div>

                <!-- 當日已有時段 -->
                @if(!empty($dayTimeSlots))
                <div class="border-t pt-4">
                    <h4 class="text-sm font-medium text-gray-700 mb-3">當日時段</h4>
                    <div class="max-h-40 overflow-y-auto space-y-2">
                        @foreach($dayTimeSlots as $slot)
                            @php
                                $slotTime = \Carbon\Carbon::parse($slot['available_time']);
                                $isAvailable = $slot['is_available'] ?? true;
                            @endphp
                            <div class="flex items-center justify-between p-2 rounded
                                       {{ $isAvailable ? 'bg-green-50 border border-green-200' : 'bg-red-50 border border-red-200' }}">
                                <span class="text-sm {{ $isAvailable ? 'text-green-700' : 'text-red-700' }}">
                                    {{ $slotTime->format('H:i') }} 
                                    {{ $isAvailable ? '(可預約)' : '(已預約)' }}
                                </span>
                                @if($isAvailable)
                                    <button wire:click="deleteTimeSlot({{ $slot['id'] }})"
                                            class="text-red-500 hover:text-red-700">
                                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif

    <!-- 快速新增模態窗 (原批量新增，簡化後) -->
    @if($showBulkModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto border border-gray-300">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-green-500 to-blue-600 text-white p-6 rounded-t-xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold">快速新增時段</h3>
                    <button wire:click="closeBulkModal" class="text-white hover:text-gray-200">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body -->
            <form wire:submit.prevent="generateTimeSlots" class="p-6">
                <div class="space-y-6">
                    <!-- 日期範圍 -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">開始日期 *</label>
                            <input type="date" wire:model="startDate" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('startDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">結束日期 *</label>
                            <input type="date" wire:model="endDate" required
                                   class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            @error('endDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- 選擇星期 -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-3">選擇星期 *</label>
                        <div class="grid grid-cols-7 gap-2">
                            @php
                                $weekdays = [
                                    0 => '日', 1 => '一', 2 => '二', 3 => '三', 
                                    4 => '四', 5 => '五', 6 => '六'
                                ];
                            @endphp
                            @foreach($weekdays as $day => $label)
                                <label class="flex items-center justify-center p-3 border rounded-lg cursor-pointer hover:bg-gray-50 transition-colors duration-200
                                    {{ in_array($day, $selectedDays) ? 'border-green-500 bg-green-50 text-green-700' : 'border-gray-300' }}">
                                    <input type="checkbox" wire:model.live="selectedDays" value="{{ $day }}" class="sr-only">
                                    <span class="font-medium">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                        @error('selectedDays') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>

                    <!-- 時段設定 -->
                    <div>
                        <div class="flex items-center justify-between mb-3">
                            <label class="block text-sm font-medium text-gray-700">時段設定</label>
                            <button type="button" wire:click="addNewTimeSlot"
                                    class="text-sm px-3 py-1 bg-blue-600 text-white rounded hover:bg-blue-700">
                                新增時段
                            </button>
                        </div>
                        
                        <div class="space-y-2 max-h-40 overflow-y-auto">
                            @forelse($selectedTimes as $index => $time)
                                <div class="flex items-center space-x-2 p-2 border border-gray-200 rounded">
                                    <input type="time" wire:model.live="selectedTimes.{{ $index }}"
                                           class="flex-1 px-2 py-1 text-sm border border-gray-300 rounded focus:ring-1 focus:ring-green-500">
                                    <button type="button" wire:click="removeTimeSlot({{ $index }})"
                                            class="px-2 py-1 text-sm bg-red-500 text-white rounded hover:bg-red-600">
                                        刪除
                                    </button>
                                </div>
                            @empty
                                <div class="text-center py-4 text-gray-500">
                                    <p class="text-sm">尚未新增任何時段</p>
                                    <button type="button" wire:click="addNewTimeSlot"
                                            class="mt-2 text-sm text-blue-600 hover:text-blue-800">
                                        點擊新增第一個時段
                                    </button>
                                </div>
                            @endforelse
                        </div>
                        @error('selectedTimes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-200 mt-6">
                    <button type="button" wire:click="closeBulkModal"
                            class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300">
                        取消
                    </button>
                    <button type="submit"
                            class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">
                        建立時段
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>