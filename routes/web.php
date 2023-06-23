<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\AdminController; 

Route::get('/', [HomeController::class, 'index']);


Route::get('/live-auction', [AuctionController::class, 'live']);
Route::get('/upcoming-auction', [AuctionController::class, 'upcoming'])->name('auction.upcoming');
Route::get('/completed-auction', [AuctionController::class, 'completed'])->name('auction.completed');
Route::get('/auction/{item_id}', [AuctionController::class, 'show'])->name('auction.show');
Route::post('/ho8OJ92Bs9RyEW67', [AuctionController::class, 'place_bid']); 

Route::get('/buy-credit', [AuctionController::class, 'buy_credit']);
Route::post('/LETBOrwenhvqRifu7Lu', [AuctionController::class, 'credit_point']); 


//auth route
Route::post('/VgtFB', [HomeController::class, 'sign_in']); 
Route::get('/RVgtFB', [HomeController::class, 'sign_out']); 
Route::get('/ogNkV', [HomeController::class, 'check_auth']);
//end auth





//---admin start

Route::prefix('admin')->group(function () {

    Route::get('/create-item', [AuctionController::class, 'create']); 
    Route::get('/dashboard', [AdminController::class, 'index']); 

    //admin image
    Route::get('/media', [AdminController::class, 'image']); 
    Route::post('/media', [AdminController::class, 'create_image']); 
    Route::delete('/media/{id}', [AdminController::class, 'delete_image']); 

    //items
    Route::get('/items', [AdminController::class, 'items']); 
    Route::get('/create-items', [AdminController::class, 'create_item']); 
    Route::post('/items', [AdminController::class, 'save_item']); 


    //settings
    Route::get('/settings', [AdminController::class, 'settings']);
    Route::post('/update-bid-price', [AdminController::class, 'update_bid_price']); 
    Route::post('/update-min-bid', [AdminController::class, 'update_min_bid_credit']); 


    Route::get('/admins', [AdminController::class, 'admins']); 
    Route::post('/admins', [AdminController::class, 'create_admin']); 
    Route::delete('/admins/{id}', [AdminController::class, 'remove_admin']); 

});
//---end admin