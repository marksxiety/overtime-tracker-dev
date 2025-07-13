<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::inertia('/', 'Main')->name('main');
Route::inertia('/about', 'About', ['name' => 'Mark'])->name('about');