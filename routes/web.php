<?php

require "admin.php";

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AdminController; 


Route::get('/',[AdminController::class, 'login'])->name('admin.login');