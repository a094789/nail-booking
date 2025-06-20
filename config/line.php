<?php

return [
    /*
    |--------------------------------------------------------------------------
    | LINE 通知設定
    |--------------------------------------------------------------------------
    */
    
    // LINE 通知開關 - 可以透過管理員頁面控制
    'notification_enabled' => env('LINE_NOTIFICATION_ENABLED', true),
    
    // LINE Bot 設定
    'channel_access_token' => env('LINE_CHANNEL_ACCESS_TOKEN'),
    'channel_secret' => env('LINE_CHANNEL_SECRET'),
    
    // LINE Login 設定
    'client_id' => env('LINE_CLIENT_ID'),
    'client_secret' => env('LINE_CLIENT_SECRET'),
    'redirect_uri' => env('LINE_REDIRECT_URI'),
]; 