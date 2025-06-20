<x-app-layout title="功能開發中">
    <div class="min-h-screen bg-gray-50 flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-xl shadow-lg p-8 text-center">
            <!-- Icon -->
            <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-gradient-to-br from-pink-100 to-purple-100 mb-6">
                <svg class="h-10 w-10 text-pink-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
                </svg>
            </div>
            
            <!-- Title -->
            <h1 class="text-3xl font-bold text-gray-900 mb-4">
                {{ $feature ?? '功能' }}開發中
            </h1>
            
            <!-- Description -->
            <p class="text-gray-600 text-lg mb-8 leading-relaxed">
                此功能正在開發中，敬請期待！<br>
                <span class="text-sm text-gray-500 mt-2 block">我們會盡快為您提供更好的服務體驗</span>
            </p>
            
            <!-- Buttons -->
            <div class="space-y-3 mb-8">
                @if(Auth::user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" 
                       class="w-full inline-flex justify-center items-center px-6 py-3 bg-gradient-to-r from-pink-600 to-purple-600 hover:from-pink-700 hover:to-purple-700 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        返回後台首頁
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" 
                       class="w-full inline-flex justify-center items-center px-6 py-3 bg-gradient-to-r from-pink-600 to-purple-600 hover:from-pink-700 hover:to-purple-700 text-white font-medium rounded-lg transition-all duration-200 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                        <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        返回首頁
                    </a>
                @endif
                
                <button onclick="window.history.back()" 
                        class="w-full inline-flex justify-center items-center px-6 py-3 bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium rounded-lg transition-colors duration-200">
                    <svg class="mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                    </svg>
                    返回上一頁
                </button>
            </div>
            
            <!-- Progress indicator -->
            <div class="pt-6 border-t border-gray-200">
                <div class="flex items-center justify-center space-x-3">
                    <div class="flex space-x-1">
                        <div class="w-2 h-2 bg-pink-500 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-pink-400 rounded-full animate-bounce" style="animation-delay: 0.1s"></div>
                        <div class="w-2 h-2 bg-pink-300 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                    </div>
                    <span class="text-sm text-gray-500 font-medium">開發進行中</span>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>