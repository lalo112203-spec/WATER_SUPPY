<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\NotificationCheckController;

Route::get('/check-new-bills/{customer_id}', [NotificationCheckController::class, 'check']);
