<!-- 優化後的導航欄 - nav.blade.php -->
<nav class="bg-white shadow-lg sticky top-0 z-50 border-b border-gray-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            <!-- Left side - Logo and main navigation -->
            <div class="flex items-center">
                <!-- Logo/Brand -->
                <div class="flex-shrink-0 mr-8">
                    @auth
                    @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-pink-500 to-purple-600 rounded-lg flex items-center justify-center mr-2">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-pink-600 to-purple-600 bg-clip-text text-transparent">
                            美甲預約後台
                        </span>
                    </a>
                    @else
                    <a href="{{ route('dashboard') }}" class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-pink-500 to-purple-600 rounded-lg flex items-center justify-center mr-2">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-pink-600 to-purple-600 bg-clip-text text-transparent">
                            美甲預約
                        </span>
                    </a>
                    @endif
                    @else
                    <!-- 未登入時的 Logo -->
                    <a href="{{ route('home') }}" class="flex items-center">
                        <div class="w-8 h-8 bg-gradient-to-br from-pink-500 to-purple-600 rounded-lg flex items-center justify-center mr-2">
                            <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z" />
                            </svg>
                        </div>
                        <span class="text-xl font-bold bg-gradient-to-r from-pink-600 to-purple-600 bg-clip-text text-transparent">
                            美甲預約系統
                        </span>
                    </a>
                    @endauth
                </div>

                <!-- Desktop Navigation Links -->
                <div class="hidden lg:flex space-x-6">
                    @auth
                    @if(Auth::user()->role === 'admin')
                    <!-- Admin Navigation -->
                                                    <a href="{{ route('admin.bookings.index') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-pink-50 hover:text-pink-600 {{ request()->routeIs('admin.bookings.index') ? 'bg-pink-50 text-pink-600 shadow-sm' : 'text-gray-700' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        預約管理
                    </a>
                    <a href="{{ route('admin.bookings.daily') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-pink-50 hover:text-pink-600 {{ request()->routeIs('admin.bookings.daily') ? 'bg-pink-50 text-pink-600 shadow-sm' : 'text-gray-700' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                        </svg>
                        每日清單
                    </a>
                    @else
                    <!-- Customer Navigation -->
                    <a href="{{ route('booking.create') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-pink-50 hover:text-pink-600 {{ request()->routeIs('booking.create') ? 'bg-pink-50 text-pink-600 shadow-sm' : 'text-gray-700' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        查看可預約時段
                    </a>
                    <a href="{{ route('booking.index') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-pink-50 hover:text-pink-600 {{ request()->routeIs('booking.index') ? 'bg-pink-50 text-pink-600 shadow-sm' : 'text-gray-700' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                        </svg>
                        預約管理
                    </a>
                    @endif
                    @else
                    <!-- 未登入時的 Navigation -->
                    <a href="{{ route('home') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-pink-50 hover:text-pink-600 {{ request()->routeIs('home') ? 'bg-pink-50 text-pink-600 shadow-sm' : 'text-gray-700' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                        </svg>
                        首頁
                    </a>
                    <a href="{{ route('booking.create') }}"
                        class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-pink-50 hover:text-pink-600 {{ request()->routeIs('booking.create') ? 'bg-pink-50 text-pink-600 shadow-sm' : 'text-gray-700' }}">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        查看可預約時段
                    </a>
                    <!-- 🔑 LINE 加好友按鈕 (未登入用戶) -->
                    <a href="{{ route('line.join') }}" 
                       class="flex items-center px-3 py-2 bg-green-500 text-white rounded-lg text-sm font-medium hover:bg-green-600 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                       title="加入官方 LINE">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/>
                        </svg>
                        <span class="hidden xl:inline">加入 LINE</span>
                    </a>
                    
                    <a href="{{ route('customer.login') }}"
                        class="flex items-center px-4 py-2 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg text-sm font-medium transition-all duration-200 hover:from-pink-600 hover:to-purple-700 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                        </svg>
                        登入預約
                    </a>
                    @endauth
                </div>
            </div>

            <!-- Right side - User menu and admin functions -->
            <div class="flex items-center space-x-4">
                @auth
                @if(Auth::user()->role === 'admin')
                <!-- Admin Right Navigation - Desktop -->
                <div class="hidden lg:flex items-center space-x-4">
                    <!-- 系統管理下拉選單 -->
                    <div class="relative" x-data="{ systemOpen: false }">
                        <button @click="systemOpen = !systemOpen" type="button"
                            class="flex items-center px-3 py-2 rounded-lg text-sm font-medium transition-all duration-200 hover:bg-pink-50 hover:text-pink-600 {{ request()->routeIs('admin.available-times.*', 'admin.users.*', 'admin.revenue.*', 'admin.line.*') ? 'bg-pink-50 text-pink-600 shadow-sm' : 'text-gray-700' }}">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            系統管理
                            <svg class="ml-1 h-4 w-4" :class="{ 'rotate-180': systemOpen }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>

                        <!-- 系統管理下拉選單 -->
                        <div x-show="systemOpen"
                            @click.away="systemOpen = false"
                            x-transition:enter="transition ease-out duration-200"
                            x-transition:enter-start="transform opacity-0 scale-95"
                            x-transition:enter-end="transform opacity-100 scale-100"
                            x-transition:leave="transition ease-in duration-150"
                            x-transition:leave-start="transform opacity-100 scale-100"
                            x-transition:leave-end="transform opacity-0 scale-95"
                            class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                            <div class="py-1">
                                <a href="{{ route('admin.available-times.index') }}"
                                    class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-colors duration-200 {{ request()->routeIs('admin.available-times.*') ? 'bg-pink-50 text-pink-600' : '' }}">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    時段管理
                                </a>
                                <a href="{{ route('admin.users.index') }}"
                                    class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-colors duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-pink-50 text-pink-600' : '' }}">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                    </svg>
                                    使用者管理
                                </a>
                                <a href="{{ route('admin.revenue.index') }}"
                                    class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-colors duration-200 {{ request()->routeIs('admin.revenue.*') ? 'bg-pink-50 text-pink-600' : '' }}">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"/>
                                    </svg>
                                    營業額統計
                                </a>
                                <div class="border-t border-gray-100 my-1"></div>
                                <a href="{{ route('admin.line.settings') }}"
                                    class="flex items-center px-4 py-3 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-colors duration-200 {{ request()->routeIs('admin.line.*') ? 'bg-pink-50 text-pink-600' : '' }}">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.343 12.343l1.414 1.414L8 11.414l2.243 2.243 1.414-1.414L9.414 10l2.243-2.243-1.414-1.414L8 8.586 5.757 6.343 4.343 7.757 6.586 10 4.343 12.343z"/>
                                    </svg>
                                    LINE 通知設定
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- 🔑 LINE 加好友按鈕 (桌面版) -->
                <div class="hidden lg:block">
                    <a href="{{ route('line.join') }}" 
                       class="flex items-center px-3 py-2 bg-green-500 text-white rounded-lg text-sm font-medium hover:bg-green-600 transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5"
                       title="加入官方 LINE 接收通知">
                        <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/>
                        </svg>
                        <span class="hidden xl:inline">加入 LINE</span>
                    </a>
                </div>

                <!-- 🔑 加上使用者下拉選單 -->
                <div class="relative" x-data="{ open: false }">
                    <div>
                        <button @click="open = !open" type="button"
                            class="max-w-xs bg-white rounded-full flex items-center text-sm focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-pink-500 lg:p-2 lg:rounded-md lg:hover:bg-gray-50"
                            id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                            @if(session('line_avatar'))
                            <img class="h-8 w-8 rounded-full" src="{{ session('line_avatar') }}" alt="使用者頭像">
                            @else
                            <div class="h-8 w-8 rounded-full bg-gradient-to-br from-pink-500 to-purple-600 flex items-center justify-center">
                                <span class="text-white text-sm font-semibold">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </span>
                            </div>
                            @endif
                            <div class="hidden lg:ml-3 lg:block">
                                <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                                <p class="text-xs text-gray-500">
                                    {{ Auth::user()->role === 'admin' ? '管理員' : '客戶' }}
                                </p>
                            </div>
                            <svg class="hidden lg:ml-1 lg:block h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>

                    <!-- Desktop Dropdown Menu -->
                    <div x-show="open"
                        @click.away="open = false"
                        x-transition:enter="transition ease-out duration-200"
                        x-transition:enter-start="transform opacity-0 scale-95"
                        x-transition:enter-end="transform opacity-100 scale-100"
                        x-transition:leave="transition ease-in duration-150"
                        x-transition:leave-start="transform opacity-100 scale-100"
                        x-transition:leave-end="transform opacity-0 scale-95"
                        class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg ring-1 ring-black ring-opacity-5 z-50">
                        <div class="py-1">
                            @if(Auth::user()->role === 'user')
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                個人資訊修改
                            </a>
                            
                            <a href="{{ route('line.join') }}" 
                               class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors duration-200">
                                <svg class="w-4 h-4 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/>
                                </svg>
                                加入官方 LINE
                            </a>
                            @endif

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-200">
                                    <svg class="w-4 h-4 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    登出
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endauth

                <!-- Mobile Menu Button -->
                <div class="lg:hidden" x-data="{ mobileOpen: false }">
                    <button @click="mobileOpen = !mobileOpen"
                        class="p-2 rounded-lg text-gray-700 hover:text-pink-600 hover:bg-pink-50 focus:outline-none focus:ring-2 focus:ring-pink-500 focus:ring-offset-2 transition-all duration-200">
                        <svg class="h-6 w-6" :class="{ 'hidden': mobileOpen, 'block': !mobileOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="h-6 w-6" :class="{ 'block': mobileOpen, 'hidden': !mobileOpen }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>

                    <!-- Mobile Menu Overlay -->
                    <div x-show="mobileOpen"
                        x-transition:enter="transition-opacity ease-linear duration-300"
                        x-transition:enter-start="opacity-0"
                        x-transition:enter-end="opacity-100"
                        x-transition:leave="transition-opacity ease-linear duration-300"
                        x-transition:leave-start="opacity-100"
                        x-transition:leave-end="opacity-0"
                        @click="mobileOpen = false"
                        class="fixed inset-0 bg-black bg-opacity-25 z-40 lg:hidden"></div>

                    <!-- Mobile Menu Panel -->
                    <div x-show="mobileOpen"
                        x-transition:enter="transition ease-out duration-300 transform"
                        x-transition:enter-start="translate-x-full"
                        x-transition:enter-end="translate-x-0"
                        x-transition:leave="transition ease-in duration-300 transform"
                        x-transition:leave-start="translate-x-0"
                        x-transition:leave-end="translate-x-full"
                        class="fixed top-0 right-0 bottom-0 w-80 max-w-sm bg-white shadow-xl z-50 lg:hidden overflow-y-auto">

                        <!-- Mobile Menu Header -->
                        <div class="px-6 py-4 bg-gradient-to-r from-pink-500 to-purple-600">
                            <div class="flex items-center justify-between">
                                @auth
                                <div class="flex items-center space-x-3">
                                    @if(session('line_avatar'))
                                    <img src="{{ session('line_avatar') }}" alt="頭像" class="h-12 w-12 rounded-full object-cover ring-2 ring-white">
                                    @else
                                    <div class="h-12 w-12 rounded-full bg-white bg-opacity-20 flex items-center justify-center ring-2 ring-white">
                                        <span class="text-white font-semibold text-lg">
                                            {{ substr(Auth::user()->name, 0, 1) }}
                                        </span>
                                    </div>
                                    @endif
                                    <div class="flex-1 min-w-0">
                                        <p class="text-white font-medium truncate">{{ Auth::user()->name }}</p>
                                    </div>
                                </div>
                                @else
                                <span class="text-white font-bold text-lg">美甲預約系統</span>
                                @endauth
                                <button @click="mobileOpen = false" class="p-2 text-white hover:bg-white hover:bg-opacity-20 rounded-lg">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <!-- Mobile Menu Items -->
                        <div class="py-4">
                            @auth
                            @if(Auth::user()->role === 'admin')
                            <!-- Admin Mobile Menu -->
                            <div class="px-4 mb-2">
                                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">預約管理</h3>
                            </div>
                            <a href="{{ route('admin.bookings.index') }}"
                                                                 class="flex items-center px-6 py-3 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-colors duration-200 {{ request()->routeIs('admin.bookings.index') ? 'bg-pink-50 text-pink-600 border-r-2 border-pink-600' : '' }}"
                                @click="mobileOpen = false">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                預約管理
                            </a>
                            <a href="{{ route('admin.bookings.daily') }}"
                                class="flex items-center px-6 py-3 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-colors duration-200 {{ request()->routeIs('admin.bookings.daily') ? 'bg-pink-50 text-pink-600 border-r-2 border-pink-600' : '' }}"
                                @click="mobileOpen = false">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                                </svg>
                                每日預約清單
                            </a>
                            
                            <div class="border-t border-gray-200 my-4"></div>
                            
                            <div class="px-4 mb-2">
                                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">系統管理</h3>
                            </div>
                            <a href="{{ route('admin.available-times.index') }}"
                                class="flex items-center px-6 py-3 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-colors duration-200 {{ request()->routeIs('admin.available-times.*') ? 'bg-pink-50 text-pink-600 border-r-2 border-pink-600' : '' }}"
                                @click="mobileOpen = false">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                時段管理
                            </a>
                            <a href="{{ route('admin.users.index') }}"
                                class="flex items-center px-6 py-3 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-colors duration-200 {{ request()->routeIs('admin.users.*') ? 'bg-pink-50 text-pink-600 border-r-2 border-pink-600' : '' }}"
                                @click="mobileOpen = false">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                                </svg>
                                使用者管理
                            </a>
                            <a href="{{ route('admin.revenue.index') }}"
                                class="flex items-center px-6 py-3 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-colors duration-200 {{ request()->routeIs('admin.revenue.*') ? 'bg-pink-50 text-pink-600 border-r-2 border-pink-600' : '' }}"
                                @click="mobileOpen = false">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                                </svg>
                                營業額統計
                            </a>
                            <a href="{{ route('admin.line.settings') }}"
                                class="flex items-center px-6 py-3 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-colors duration-200 {{ request()->routeIs('admin.line.*') ? 'bg-pink-50 text-pink-600 border-r-2 border-pink-600' : '' }}"
                                @click="mobileOpen = false">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-5 5v-5zM4.343 12.343l1.414 1.414L8 11.414l2.243 2.243 1.414-1.414L9.414 10l2.243-2.243-1.414-1.414L8 8.586 5.757 6.343 4.343 7.757 6.586 10 4.343 12.343z"/>
                                </svg>
                                LINE 通知設定
                            </a>

                            <!-- 暫時註解，待後續開發 -->
                            {{--
            <a href="{{ route('admin.revenue') }}"
                            class="flex items-center px-6 py-3 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-colors duration-200 {{ request()->routeIs('admin.revenue') ? 'bg-pink-50 text-pink-600 border-r-2 border-pink-600' : '' }}"
                            @click="mobileOpen = false">
                            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                            </svg>
                            營業額統計
                            </a>

                            <div class="border-t border-gray-200 my-4"></div>

                            <div class="px-4 mb-2">
                                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">系統管理</h3>
                            </div>
                            --}}
                            @else
                            <!-- Customer Mobile Menu 保持不變 -->
                            <div class="px-4 mb-2">
                                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">預約服務</h3>
                            </div>
                            <a href="{{ route('booking.create') }}"
                                class="flex items-center px-6 py-3 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-colors duration-200 {{ request()->routeIs('booking.create') ? 'bg-pink-50 text-pink-600 border-r-2 border-pink-600' : '' }}"
                                @click="mobileOpen = false">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                查看可預約時段
                            </a>
                            <a href="{{ route('booking.index') }}"
                                class="flex items-center px-6 py-3 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-colors duration-200 {{ request()->routeIs('booking.index') ? 'bg-pink-50 text-pink-600 border-r-2 border-pink-600' : '' }}"
                                @click="mobileOpen = false">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" />
                                </svg>
                                預約管理
                            </a>

                            <div class="border-t border-gray-200 my-4"></div>

                            <div class="px-4 mb-2">
                                <h3 class="text-xs font-semibold text-gray-500 uppercase tracking-wider">個人設定</h3>
                            </div>
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center px-6 py-3 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-colors duration-200"
                                @click="mobileOpen = false">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                                個人資訊修改
                            </a>
                            
                            <!-- 🔑 LINE 加好友選項 (手機版) -->
                            <a href="{{ route('line.join') }}" 
                               class="flex items-center px-6 py-3 text-gray-700 hover:bg-green-50 hover:text-green-600 transition-colors duration-200"
                               @click="mobileOpen = false">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/>
                                </svg>
                                加入官方 LINE
                            </a>
                            @endif

                            <!-- Logout 保持不變 -->
                            <div class="border-t border-gray-200 my-4"></div>

                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center w-full px-6 py-3 text-gray-700 hover:bg-red-50 hover:text-red-600 transition-colors duration-200"
                                    @click="mobileOpen = false">
                                    <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    登出
                                </button>
                            </form>
                            @else
                            <!-- 未登入用戶的手機選單 -->
                            <a href="{{ route('home') }}"
                                class="flex items-center px-6 py-3 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-colors duration-200"
                                @click="mobileOpen = false">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                首頁
                            </a>
                            <a href="{{ route('booking.create') }}"
                                class="flex items-center px-6 py-3 text-gray-700 hover:bg-pink-50 hover:text-pink-600 transition-colors duration-200"
                                @click="mobileOpen = false">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                查看可預約時段
                            </a>

                            <div class="border-t border-gray-200 my-4"></div>

                            <!-- 🔑 LINE 加好友選項 (未登入用戶手機版) -->
                            <a href="{{ route('line.join') }}" 
                               class="flex items-center mx-6 mb-3 py-3 px-4 bg-green-500 text-white rounded-lg font-medium transition-all duration-200 hover:bg-green-600"
                               @click="mobileOpen = false">
                                <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19.365 9.863c.349 0 .63.285.63.631 0 .345-.281.63-.63.63H17.61v1.125h1.755c.349 0 .63.283.63.63 0 .344-.281.629-.63.629h-2.386c-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63h2.386c.346 0 .627.285.627.63 0 .349-.281.63-.63.63H17.61v1.125h1.755zm-3.855 3.016c0 .27-.174.51-.432.596-.064.021-.133.031-.199.031-.211 0-.391-.09-.51-.25l-2.443-3.317v2.94c0 .344-.279.629-.631.629-.346 0-.626-.285-.626-.629V8.108c0-.27.173-.51.43-.595.06-.023.136-.033.194-.033.195 0 .375.104.495.254l2.462 3.33V8.108c0-.345.282-.63.63-.63.345 0 .63.285.63.63v4.771zm-5.741 0c0 .344-.282.629-.631.629-.345 0-.627-.285-.627-.629V8.108c0-.345.282-.63.63-.63.346 0 .628.285.628.63v4.771zm-2.466.629H4.917c-.345 0-.63-.285-.63-.629V8.108c0-.345.285-.63.63-.63.348 0 .63.285.63.63v4.141h1.756c.348 0 .629.283.629.63 0 .344-.282.629-.629.629M24 10.314C24 4.943 18.615.572 12 .572S0 4.943 0 10.314c0 4.811 4.27 8.842 10.035 9.608.391.082.923.258 1.058.59.12.301.079.766.038 1.08l-.164 1.02c-.045.301-.24 1.186 1.049.645 1.291-.539 6.916-4.078 9.436-6.975C23.176 14.393 24 12.458 24 10.314"/>
                                </svg>
                                加入官方 LINE
                            </a>

                            <a href="{{ route('customer.login') }}"
                                class="flex items-center mx-6 py-3 px-4 bg-gradient-to-r from-pink-500 to-purple-600 text-white rounded-lg font-medium transition-all duration-200 hover:from-pink-600 hover:to-purple-700"
                                @click="mobileOpen = false">
                                <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                </svg>
                                登入預約
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</nav>