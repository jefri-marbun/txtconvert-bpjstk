<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ExcelController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::get('/convert', [ExcelController::class, 'showConverter']);
Route::post('/convert-to-txt', [ExcelController::class, 'convertToTxt'])->name('convertToTxt');

Route::get('/converter-bpjs-tk', [ExcelController::class, 'showConverter_bpjstk'])->name('showConverterBPJSTK');
Route::post('/convert-bpjs-tk-to-txt', [ExcelController::class, 'convert_bpjstk'])->name('convert_bpjstk');
Route::post('/log-button-click', [ExcelController::class, 'logButtonClick']);
