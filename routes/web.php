<?php



use App\Http\Controllers\JourneyController;
use App\Http\Controllers\StaffVisitController;
use Illuminate\Support\Facades\Route;




Route::get('/', function () {
    return redirect()->route('discover');
});

// Stages 01 & 02: Discover abroad, review proof/sample details, save to itinerary
Route::get('/discover', [JourneyController::class, 'discover'])->name('discover');
Route::post('/itinerary', [JourneyController::class, 'storeItinerary'])->name('itinerary.store');

// Stage 02 -> 03: Saved Accra itinerary & arrival view
Route::get('/itinerary/{token}', [JourneyController::class, 'showItinerary'])->name('itinerary.show');

// Stage 03: Staff lookup & visit confirmation in Accra
Route::get('/staff/visits', [StaffVisitController::class, 'index'])->name('staff.visits');
Route::post('/staff/visits/{visit}/confirm', [StaffVisitController::class, 'confirm'])->name('staff.visits.confirm');

// Stage 04: Post-visit referral & share-back loop
Route::get('/referral/{token}', [JourneyController::class, 'showReferral'])->name('referral.show');
Route::post('/referral/{token}/share', [JourneyController::class, 'shareReferral'])->name('referral.share');