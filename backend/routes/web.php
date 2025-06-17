<?php

use App\Enums\StatusCallLogEnum;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    // dd(StatusCallLogEnum::cases(), array_column(StatusCallLogEnum::cases(), 'value'));
    return view('welcome');
});
