<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

use KHQR\BakongKHQR;
use KHQR\Helpers\KHQRData;
use KHQR\Models\IndividualInfo;
use KHQR\Models\SourceInfo;

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

Route::get('/', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/result', function () {
    return Inertia::render('Result');
})->middleware(['auth', 'verified'])->name('result');

Route::get('/matched', function () {
    return Inertia::render('Matched');
})->middleware(['auth', 'verified'])->name('matched');


Route::get('/order', function () {
    return Inertia::render('RestaurantOrder');
})->middleware(['auth', 'verified'])->name('order');


Route::get('/unmatched', function () {
    return Inertia::render('Unmatched');
})->middleware(['auth', 'verified'])->name('unmatched');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/test', function(){
    $individualInfo = new IndividualInfo(
        bakongAccountID: 'tepafril@aclb',
        merchantName: 'Ptas Admin',
        merchantCity: 'PHNOM PENH',
        currency: KHQRData::CURRENCY_KHR,
        amount: 500,
        expirationTimestamp: strval(floor(microtime(true) * 1000) + 60 * 10000), // Expire in 1 minute
        merchantCategoryCode: "5999" // optional, default value is 5999
    );
    
    $qr = (BakongKHQR::generateIndividual($individualInfo));
    echo $qr->data["qr"];
    // $sourceInfo = new SourceInfo(
    //     appIconUrl: 'https://bakong.nbc.gov.kh/images/logo.svg',
    //     appName: 'Bakong',
    //     appDeepLinkCallback: 'https://bakong.nbc.gov.kh'
    // );
    // $result = BakongKHQR::generateDeepLink($qr->data["qr"], $sourceInfo);
    
    // var_dump($result);
});

require __DIR__.'/auth.php';
