<?php
use Illuminate\Support\Facades\Route;
use Modules\Activos\Http\Controllers\ActivosController;
use Modules\Activos\Http\Controllers\CategoriesController;
use Modules\Activos\Http\Controllers\InstallController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::group(['middleware' => ['web', 'authh', 'auth', 'SetSessionData', 'language', 'timezone', 'AdminSidebarMenu', 'CheckUserLogin'],  'prefix' => 'activos'], function () {

    Route::get('install', [InstallController::class, 'index'])->name('activos_install');
    Route::post('install', [InstallController::class, 'install']);
    Route::get('install/uninstall', [InstallController::class, 'uninstall']);
    Route::get('install/update', [InstallController::class, 'update']);

    Route::get('/', [ActivosController::class, 'index'])->name('activos_dashboard.index');
    Route::resource('categories', CategoriesController::class);




});
