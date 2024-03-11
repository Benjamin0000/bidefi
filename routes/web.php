<?php
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProfileController; 
use App\Http\Controllers\AuctionController;
use App\Http\Controllers\AdminController; 

Route::get('/', [HomeController::class, 'index']);
Route::get('/contact-us', [HomeController::class, 'contact_us']);
Route::post('/contact-us', [HomeController::class, 'save_message']);
//auth route
Route::post('/VgtFB', [HomeController::class, 'sign_in']); 
Route::get('/RVgtFB', [HomeController::class, 'sign_out']); 
Route::get('/ogNkV', [HomeController::class, 'check_auth']);
//end auth


Route::get('/profile', [ProfileController::class, 'index']);
Route::post('/aK0qQq62l', [ProfileController::class, 'update_avater']); 
Route::post('/pAMY', [ProfileController::class, 'delete_profile_image']); 
Route::post('/Ryi71', [ProfileController::class, 'update_name']); 
Route::get('/activity', [ProfileController::class, 'activity']); 

Route::get('/buy-credit', [AuctionController::class, 'buy_credit']);

Route::get('/live-auction', [AuctionController::class, 'live']);
Route::get('/upcoming-auction', [AuctionController::class, 'upcoming'])->name('auction.upcoming');
Route::get('/completed-auction', [AuctionController::class, 'completed'])->name('auction.completed');
Route::get('/auction/{item_id}', [AuctionController::class, 'show'])->name('auction.show');


Route::post('/PwbcHF5tYjZpghfV7O', [AuctionController::class, 'generate_bid_token']); 
Route::post('/ho8OJ92Bs9RyEW67', [AuctionController::class, 'credit_bid']); 


Route::post('/FapHqrwPfkewSHq', [AuctionController::class, 'claim_winner']);



Route::post('/IN31Wd5njhG', [AuctionController::class, 'like']); 
Route::post('/fAbAsLr7Zs', [AuctionController::class, 'add_views']); 
#load more
Route::get('/kSHhWd/{type}', [AuctionController::class, 'load_more']); 


Route::post('/NAQvfoLAo', [AuctionController::class, 'store_total_credit_bought']); 


Route::get('/blog', [HomeController::class, 'blog']);
Route::get('/blog/{slug}', [HomeController::class, 'show_blog']); 
Route::get('/adsfasd/{id}', [HomeController::class, 'increase_blog_views']); 

Route::get('/faq', [HomeController::class, 'faq']); 

Route::post('/dikkej', [HomeController::class, 'send_message']); 

//---admin start
Route::prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'index']); 

    //admin image
    Route::get('/media', [AdminController::class, 'image']); 
    Route::post('/media', [AdminController::class, 'create_image']); 
    Route::delete('/media/{id}', [AdminController::class, 'delete_image']); 

    //items
    Route::get('/items', [AdminController::class, 'items'])->name('admin.items'); 
    Route::get('/create-items/{id?}', [AdminController::class, 'create_item'])->name('item.create'); 
    Route::post('/items', [AdminController::class, 'save_item']); 
    Route::get('/items/{id}', [AdminController::class, 'edit'])->name('item.edit');
    Route::put('/items/{id}', [AdminController::class, 'update_item'])->name('item.update'); 
    Route::delete('/items/{id}', [AdminController::class, 'delete_item'])->name('item.delete'); 
    Route::put('/items/{id}/update-contract', [AdminController::class, 'update_contract'])->name('item.update_contract'); 

    //settings
    Route::get('/settings', [AdminController::class, 'settings']);
    Route::post('/update-min-bid', [AdminController::class, 'update_min_bid_credit']); 

    Route::get('/admins', [AdminController::class, 'admins']); 
    Route::post('/admins', [AdminController::class, 'create_admin']); 
    Route::delete('/admins/{id}', [AdminController::class, 'remove_admin']); 

    //withdrawal
    Route::get('/withdrawal', [AdminController::class, 'withdrawal']); 
    Route::post('/withdrawal', [AdminController::class, 'create_withdrawal']);


    Route::get('/blog', [AdminController::class, 'blog'])->name('admin.blog.index');
    Route::get('/blog/create/{id?}', [AdminController::class, 'create_blog'])->name('admin.blog.create');
    Route::post('/blog/{id?}', [AdminController::class, 'save_blog'])->name('admin.blog.save');
    Route::patch('/blog/publish/{id}', [AdminController::class, 'publish'])->name('admin.blog.publish');
    Route::delete('/blog/{id}', [AdminController::class, 'delete_blog'])->name('admin.blog.delete');


    Route::get('/faq', [AdminController::class, 'faq'])->name('admin.faq.index');
    Route::get('/faq/create/{id?}', [AdminController::class, 'create_faq'])->name('admin.faq.create');
    Route::post('/faq/{id?}', [AdminController::class, 'store_faq'])->name('admin.faq.save');
    Route::delete('/faq/{id}', [AdminController::class, 'delete_faq'])->name('admin.faq.delete'); 

    #points
    Route::get('/points', [AdminController::class, 'points'])->name('admin.points.index'); 
    Route::post('/points', [AdminController::class, 'create_points'])->name('admin.points.create_points'); 
    Route::delete('/points/{id}', [AdminController::class, 'delete_points'])->name('admin.points.delete_points'); 
});
//---end admin  