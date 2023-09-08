<?php

use App\Http\Controllers\SubDomainController;
use Illuminate\Support\Facades\Route;
use Stancl\Tenancy\Middleware\InitializeTenancyByDomain;
use Stancl\Tenancy\Middleware\PreventAccessFromCentralDomains;

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
Route::middleware([
    'web',
    InitializeTenancyByDomain::class,
    PreventAccessFromCentralDomains::class,
])->group(function () {
    Route::get('create-subdomain', function () {
        return view('create-subdomain');
    });
    Route::post('store-subdomain', [SubDomainController::class, 'storeSubdomain'])->name('store-subdomain');
    Route::get('/{any}', function () {
        return view('welcome');
    })->where('any', '^(?!create-subdomain|store-subdomain|api).*$');
});
