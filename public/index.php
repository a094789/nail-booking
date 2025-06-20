<?php
ob_start(); // 添加這一行

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// 檢查維護模式...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// 註冊 Composer 自動載入器...
require __DIR__.'/../vendor/autoload.php';

// 啟動 Laravel 應用程式...
$app = require_once __DIR__.'/../bootstrap/app.php';

$app->handleRequest(Request::capture());