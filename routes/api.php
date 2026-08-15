<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WebhookController;

Route::post('/midtrans/notification', [WebhookController::class, 'handle']);