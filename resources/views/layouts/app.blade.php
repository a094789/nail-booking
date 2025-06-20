<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{-- 🔧 重要：確保 CSRF token meta 標籤存在 --}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $title ?? '美甲預約系統' }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Tailwind CSS -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- 🔧 確保 Livewire styles 在 head 中 --}}
    @livewireStyles
</head>

<body class="font-sans antialiased bg-gray-50 min-h-screen">
    <!-- Navigation -->
    @auth
    @include('layouts.nav')
    @endauth

    <!-- Page Content -->
    <main class="flex-1">
        {{ $slot }}
    </main>

    {{-- 🔧 確保 Livewire scripts 在 body 末尾 --}}
    @livewireScripts

    {{-- 🔧 在 Livewire scripts 之後設定 CSRF token --}}
    <script>
        // 🔧 確保 Livewire 正確獲得 CSRF token
        document.addEventListener('DOMContentLoaded', function() {
            // 檢查 CSRF token
            const csrfToken = document.querySelector('meta[name="csrf-token"]');
            if (csrfToken && typeof window.Livewire !== 'undefined') {
                // 強制設定 Livewire CSRF token
                window.Livewire.csrf = csrfToken.getAttribute('content');
                console.log('✅ Livewire CSRF token 已設定:', window.Livewire.csrf);
            } else {
                console.error('❌ CSRF token 或 Livewire 未正確載入');
                console.log('CSRF token 存在:', !!csrfToken);
                console.log('Livewire 存在:', typeof window.Livewire !== 'undefined');
            }
        });
    </script>

    <!-- Laravel Flash Messages -->
    @if(session('success'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 5000)"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-full"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-full"
         class="fixed bottom-4 right-4 z-50 bg-green-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center space-x-2">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('success') }}</span>
        <button @click="show = false" class="ml-2 text-white hover:text-gray-200">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div x-data="{ show: true }" 
         x-show="show" 
         x-init="setTimeout(() => show = false, 5000)"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform translate-y-full"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-300"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform translate-y-full"
         class="fixed bottom-4 right-4 z-50 bg-red-500 text-white px-4 py-2 rounded-lg shadow-lg flex items-center space-x-2">
        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
        </svg>
        <span>{{ session('error') }}</span>
        <button @click="show = false" class="ml-2 text-white hover:text-gray-200">
            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>
    @endif
    
    <footer class="text-center text-gray-500 text-sm py-4 border-t mt-10">
        &copy; {{ date('Y') }} 誠優電腦. All rights reserved.
    </footer>
</body>

</html>