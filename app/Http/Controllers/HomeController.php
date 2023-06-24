<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\User;
use App\Models\Item; 

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $trendings = trendings(); 
        $live_auctions = live_auction(); 
        $top_bidders =  top_bidders(); 
        $upcomings = upcoming(); 
        $completed = completed(); 
        $latest_winners = latest_winners(); 

        return view('home.index', compact(
            'trendings', 
            'live_auctions', 
            'top_bidders',
            'upcomings',
            'completed',
            'latest_winners'
        )); 
    }

    public function profile()
    {
        return view('home.profile'); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function sign_in(Request $request)
    {
        $address = $request->input('address');
        $sig = $request->input('sig');
        $message = $request->input('message');
        if(!$address || !$sig || !$message) return;
        $user = User::findUserByAddress($address); 
        if (verifySignature($message, $sig, $address)) {
            Auth::login($user, true);
            return ['auth'=>true];
        }
    }

    public function check_auth()
    {
        $user = Auth::user(); 
        return [
            'auth'=>$user ? true : false ,
            'address'=>$user ? $user->address : ''
        ]; 
    }

    public function sign_out()
    {
        Auth::logout(); 
        return ['done'=>true]; 
    }

}
 