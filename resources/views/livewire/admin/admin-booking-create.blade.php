{{-- resources/views/livewire/admin/admin-booking-create.blade.php --}}

<div>
    <!-- 建立預約按鈕 -->
    <button wire:click="openCreateModal"
            class="inline-flex items-center px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        替使用者建立預約
    </button>

    <!-- 建立預約模態窗 -->
    @if($showCreateModal)
    <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl max-w-4xl w-full max-h-[90vh] flex flex-col border border-gray-300">
            <!-- Modal Header - 固定不滾動 -->
            <div class="bg-gradient-to-r from-green-500 to-blue-600 text-white p-6 rounded-t-xl flex-shrink-0">
                <div class="flex items-center justify-between">
                    <h3 class="text-xl font-bold">建立新預約</h3>
                    <button wire:click="closeCreateModal" class="text-white hover:text-gray-200 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Modal Body - 可滾動區域 -->
            <div class="flex-1 overflow-y-auto">
                <form wire:submit.prevent="createBooking" id="admin-booking-form" class="p-6">
                    <div class="space-y-6">
                        <!-- 使用者選擇 -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">選擇使用者 *</label>
                            <div class="relative">
                                <input type="text" wire:model.live="userSearch"
                                    placeholder="搜尋使用者（姓名、Email、LINE名稱、電話）"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                
                                @if($users->count() > 0 && $userSearch && !$selectedUser)
                                <div class="absolute top-full left-0 right-0 bg-white border border-gray-300 rounded-lg shadow-lg z-10 max-h-60 overflow-y-auto">
                                    @foreach($users as $user)
                                    <div wire:click="selectUser({{ $user->id }})"
                                        class="p-3 hover:bg-gray-50 cursor-pointer border-b border-gray-100 last:border-b-0">
                                        <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-600">{{ $user->email }}</div>
                                        <div class="text-sm text-gray-500">LINE: {{ $user->line_name ?? '未設定' }}</div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                            @error('selectedUser') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <!-- 預約時間選擇 - 改為可用時段選擇 -->
                        <div class="space-y-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">預約時間 *</label>
                            
                            <!-- 月份選擇 -->
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-2">選擇月份</label>
                                <select wire:model.live="selectedMonth" 
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    <option value="">請選擇月份</option>
                                    @foreach($monthOptions as $month)
                                    <option value="{{ $month['value'] }}">{{ $month['label'] }}</option>
                                    @endforeach
                                </select>
                                @error('selectedMonth') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <!-- 日期選擇 -->
                            @if($selectedMonth && count($availableDates) > 0)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-2">選擇日期</label>
                                <div class="grid grid-cols-3 md:grid-cols-5 gap-2 max-h-32 overflow-y-auto">
                                    @foreach($availableDates as $date)
                                    <button type="button"
                                        wire:click="selectDate('{{ $date['date'] }}')"
                                        class="p-2 text-sm rounded-lg border-2 transition-all duration-200 {{ $selectedDate === $date['date'] ? 'border-green-500 bg-green-50 text-green-700' : 'border-gray-200 text-gray-700 hover:border-green-300' }}">
                                        <div class="font-medium">{{ $date['day'] }}</div>
                                        <div class="text-xs text-gray-500">{{ $date['day_of_week'] }}</div>
                                    </button>
                                    @endforeach
                                </div>
                                @error('selectedDate') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            @endif

                            <!-- 時段選擇 -->
                            @if($selectedDate && count($availableTimes) > 0)
                            <div>
                                <label class="block text-sm font-medium text-gray-600 mb-2">選擇時段</label>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-2 max-h-32 overflow-y-auto">
                                    @foreach($availableTimes as $time)
                                    <button type="button"
                                        wire:click="selectTimeSlot({{ $time['id'] }})"
                                        class="p-2 text-sm rounded-lg border-2 transition-all duration-200 {{ $selectedTimeSlot === $time['id'] ? 'border-green-500 bg-green-50 text-green-700' : 'border-gray-200 text-gray-700 hover:border-green-300' }}">
                                        {{ $time['time'] }}
                                    </button>
                                    @endforeach
                                </div>
                                @error('selectedTimeSlot') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>
                            @endif

                            @if($selectedMonth && count($availableDates) === 0)
                            <div class="text-center py-4 text-gray-500 text-sm">
                                該月份暫無可預約日期
                            </div>
                            @endif

                            @if($selectedDate && count($availableTimes) === 0)
                            <div class="text-center py-4 text-gray-500 text-sm">
                                該日期暫無可預約時段
                            </div>
                            @endif
                        </div>

                        <!-- 客戶資訊 -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">客戶資訊</h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">姓名 *</label>
                                    <input type="text" wire:model="customer_name"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    @error('customer_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">LINE名稱 *</label>
                                    <input type="text" wire:model="customer_line_name"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    @error('customer_line_name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">LINE ID *</label>
                                    <input type="text" wire:model="customer_line_id"
                                        placeholder="例：@john123（客戶的自訂LINE ID）"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    @error('customer_line_id') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                    <p class="text-xs text-gray-500 mt-1">填寫客戶的自訂 LINE ID，用於聯繫</p>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">電話 *</label>
                                    <input type="tel" wire:model="customer_phone"
                                        placeholder="09xxxxxxxx"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                    @error('customer_phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>

                        <!-- 服務需求 -->
                        <div class="space-y-4">
                            <h4 class="text-lg font-medium text-gray-900 border-b border-gray-200 pb-2">服務需求</h4>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="flex items-center">
                                        <input type="checkbox" wire:model="need_removal"
                                            class="h-4 w-4 text-green-600 focus:ring-green-500 border-gray-300 rounded">
                                        <span class="ml-2 text-sm text-gray-700">需要卸甲服務</span>
                                    </label>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">預約狀態 *</label>
                                    <select wire:model="status"
                                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500">
                                        <option value="pending">待審核</option>
                                        <option value="approved">直接核准</option>
                                    </select>
                                </div>
                            </div>

                            <!-- 款式選擇 -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-3">款式選擇 *</label>
                                <div class="grid grid-cols-2 gap-4">
                                    <div wire:click="$set('style_type', 'single_color')" class="relative cursor-pointer">
                                        <div class="p-4 border-2 rounded-lg transition-all duration-200 
                                            {{ $style_type === 'single_color' ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-green-300' }}">
                                            <div class="text-center">
                                                <div class="flex items-center justify-center mb-2">
                                                    <div class="w-4 h-4 rounded-full border-2 border-gray-400 flex items-center justify-center
                                                        {{ $style_type === 'single_color' ? 'border-green-500' : '' }}">
                                                        @if($style_type === 'single_color')
                                                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="font-medium text-gray-900">單色</div>
                                                <div class="text-sm text-gray-500 mt-1">簡約純色設計</div>
                                            </div>
                                        </div>
                                    </div>

                                    <div wire:click="$set('style_type', 'design')" class="relative cursor-pointer">
                                        <div class="p-4 border-2 rounded-lg transition-all duration-200 
                                            {{ $style_type === 'design' ? 'border-green-500 bg-green-50' : 'border-gray-200 hover:border-green-300' }}">
                                            <div class="text-center">
                                                <div class="flex items-center justify-center mb-2">
                                                    <div class="w-4 h-4 rounded-full border-2 border-gray-400 flex items-center justify-center
                                                        {{ $style_type === 'design' ? 'border-green-500' : '' }}">
                                                        @if($style_type === 'design')
                                                            <div class="w-2 h-2 rounded-full bg-green-500"></div>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="font-medium text-gray-900">造型</div>
                                                <div class="text-sm text-gray-500 mt-1">創意圖案設計</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @error('style_type') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div x-data="{ count: $refs.textarea ? $refs.textarea.value.length : 0 }">
                                <label class="block text-sm font-medium text-gray-700 mb-1">備註</label>
                                <textarea
                                    wire:model="notes"
                                    x-ref="textarea"
                                    maxlength="100"
                                    rows="4"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-green-500 focus:border-green-500"
                                    placeholder="特殊需求或備註事項..."
                                    @input="count = $refs.textarea.value.length"></textarea>
                                @error('notes') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                <div class="text-sm text-gray-500 mt-1">
                                    <span x-text="count"></span>/100 字元
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <!-- Modal Footer - 固定在底部 -->
            <div class="flex items-center justify-end space-x-4 p-6 border-t border-gray-200 bg-white rounded-b-xl flex-shrink-0">
                <button type="button" wire:click="closeCreateModal"
                    class="px-4 py-2 text-gray-700 bg-gray-200 rounded-lg hover:bg-gray-300 transition-colors duration-200">
                    取消
                </button>
                <button type="submit" form="admin-booking-form"
                    class="px-6 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors duration-200">
                    建立預約
                </button>
            </div>
        </div>
    </div>
    @endif
</div>