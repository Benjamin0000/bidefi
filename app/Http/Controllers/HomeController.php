<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\User;


class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('home.index'); 
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
 