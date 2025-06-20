<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            @if($isFirstTime)
                {{ __('Complete Registration') }}
            @else
                {{ __('Profile') }}
            @endif
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-md mx-auto sm:px-6 lg:px-8">
        <!-- 標題 -->
        <div class="text-center mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">
                @if($isFirstTime)
                    完成註冊
                @else
                    個人資料
                @endif
            </h1>
            @if($isFirstTime)
                <p class="text-gray-600 text-sm">請填寫基本資料以完成註冊</p>
            @endif
        </div>

        <!-- 成功訊息 -->
        @if(session('success'))
            <div x-data="{ show: true }" 
                 x-show="show" 
                 x-init="setTimeout(() => show = false, 5000)"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 transform -translate-y-2"
                 x-transition:enter-end="opacity-100 transform translate-y-0"
                 x-transition:leave="transition ease-in duration-300"
                 x-transition:leave-start="opacity-100 transform translate-y-0"
                 x-transition:leave-end="opacity-0 transform -translate-y-2"
                 class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                <button @click="show = false" class="text-green-700 hover:text-green-800">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                    </svg>
                </button>
            </div>
        @endif

            <!-- 表單 -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PUT')

                <!-- LINE 資訊 -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">LINE 帳號資訊</h3>
                    
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">LINE 名稱</label>
                        <div class="bg-gray-100 px-3 py-2 rounded border text-gray-600">
                            {{ $user->line_name ?? '未提供' }}
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            <span class="inline-flex items-center">
                                <svg class="w-3 h-3 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                系統自動同步，無法修改
                            </span>
                        </p>
                    </div>

                    <!-- 系統通知 ID（不可編輯） -->
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">LINE 通知ID</label>
                        <div class="bg-gray-100 px-3 py-2 rounded border text-gray-600 font-mono text-xs">
                            {{ $user->line_id ? Str::limit($user->line_id, 40) . '...' : '未提供' }}
                        </div>
                        <p class="text-xs text-gray-500 mt-1">
                            <span class="inline-flex items-center">
                                <svg class="w-3 h-3 text-green-500 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                系統自動通知專用，無法修改
                            </span>
                        </p>
                    </div>

                    <!-- 聯繫用 LINE ID（可編輯） -->
                    <div class="mb-4">
                        <label for="line_contact_id" class="block text-sm font-medium text-gray-700 mb-2">
                            LINE ID（聯繫用）
                            @if($isFirstTime)
                                <span class="text-blue-600 text-xs ml-1">建議填寫</span>
                            @endif
                        </label>
                        <input type="text" 
                               id="line_contact_id" 
                               name="line_contact_id" 
                               value="{{ old('line_contact_id', $user->line_contact_id) }}"
                               placeholder="例：@john123"
                               class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('line_contact_id') border-red-500 @enderror">
                        @error('line_contact_id')
                            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                        <p class="text-xs text-gray-500 mt-1">供客服聯繫使用，可隨時修改</p>
                    </div>
                </div>

                <!-- 個人基本資料 -->
                <div class="mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-3">個人基本資料</h3>

                    <!-- 姓名 -->
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                            姓名 <span class="text-red-500">*</span>
                        </label>
                        @if($isFirstTime || $canEditName)
                            <input type="text" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}"
                                   placeholder="請輸入您的真實姓名"
                                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('name') border-red-500 @enderror"
                                   {{ $isFirstTime ? 'required' : '' }}>
                            @error('name')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        @else
                            <div class="bg-gray-100 px-3 py-2 rounded border text-gray-600">
                                {{ $user->name }}
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                下次可編輯時間：{{ (new App\Http\Controllers\UserProfileController())->getNextEditableDate($user->last_name_update)?->format('Y-m-d') }}
                            </p>
                        @endif
                    </div>

                    <!-- 電話 -->
                    <div class="mb-4">
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                            電話 <span class="text-red-500">*</span>
                        </label>
                        @if($isFirstTime || $canEditPhone)
                            <input type="tel" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}"
                                   placeholder="例：0912345678"
                                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('phone') border-red-500 @enderror"
                                   {{ $isFirstTime ? 'required' : '' }}>
                            @error('phone')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        @else
                            <div class="bg-gray-100 px-3 py-2 rounded border text-gray-600">
                                {{ $user->phone }}
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                下次可編輯時間：{{ (new App\Http\Controllers\UserProfileController())->getNextEditableDate($user->last_phone_update)?->format('Y-m-d') }}
                            </p>
                        @endif
                    </div>

                    <!-- Email -->
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                            Email <span class="text-red-500">*</span>
                        </label>
                        @if($isFirstTime || $canEditEmail)
                            <input type="email" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}"
                                   placeholder="example@email.com"
                                   class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:ring-2 focus:ring-pink-500 focus:border-transparent @error('email') border-red-500 @enderror"
                                   {{ $isFirstTime ? 'required' : '' }}>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        @else
                            <div class="bg-gray-100 px-3 py-2 rounded border text-gray-600">
                                {{ $user->email }}
                            </div>
                            <p class="text-xs text-gray-500 mt-1">
                                下次可編輯時間：{{ (new App\Http\Controllers\UserProfileController())->getNextEditableDate($user->last_email_update)?->format('Y-m-d') }}
                            </p>
                        @endif
                    </div>
                </div>

                <!-- 提交按鈕 -->
                @if($isFirstTime || $canEditName || $canEditPhone || $canEditEmail)
                    <button type="submit" 
                            class="w-full bg-pink-500 text-white py-3 px-4 rounded font-medium hover:bg-pink-600 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 transition duration-200">
                        @if($isFirstTime)
                            完成註冊
                        @else
                            更新資料
                        @endif
                    </button>
                @endif

                <!-- 返回按鈕 -->
                @if(!$isFirstTime)
                    <div class="mt-4">
                        <a href="{{ route('dashboard') }}" 
                           class="w-full bg-gray-500 text-white py-3 px-4 rounded font-medium hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition duration-200 block text-center">
                            返回首頁
                        </a>
                    </div>
                @endif
                </div>
            </div>

            <!-- 編輯限制說明 -->
            @if(!$isFirstTime)
                <div class="mt-6 bg-amber-50 border border-amber-200 rounded-lg p-6">
                    <div class="flex items-start">
                        <div class="flex-shrink-0">
                            <svg class="w-5 h-5 text-amber-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <h4 class="text-sm font-medium text-amber-800">編輯限制說明</h4>
                            <p class="text-sm text-amber-700 mt-1">
                                為確保資料正確性，姓名、電話及Email每3個月僅能編輯一次。
                            </p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>

    @if($isFirstTime)
    <script>
    // 首次註冊時防止返回
    history.pushState(null, null, location.href);
    window.onpopstate = function () {
        history.go(1);
    };
    </script>
    @endif
</x-app-layout>