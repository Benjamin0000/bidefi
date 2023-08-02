<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use Illuminate\Support\Facades\Storage; 
use App\Models\Bidder; 

class ProfileController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth'); 
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('profile.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function update_avater(Request $request)
    {
        $this->validate($request, [
            'profile' => ['required', 'mimes:jpeg,png,jpg', "max:200"]
        ]);
        $user = Auth::user();
        if ($user->avatar) {
            if (Storage::exists($user->avatar))
                Storage::delete($user->avatar);
        }
        $path = $request->file('profile')->storePublicly('public/avatars');
        $user->avatar = $path;
        $user->save();
        return ['success' => "Uploaded"];
    }
    /**
     * Delete profile image
     */
    public function delete_profile_image()
    {
        $user = Auth::user();
        if ($user->avatar) {
            if (Storage::exists($user->avatar))
                Storage::delete($user->avatar);
            $user->avatar = ""; 
            $user->save(); 
        }
    }
    /**
     * Store a newly created resource in storage.
     */
    public function update_name(Request $request)
    {
        $this->validate($request, [
            'fname' => ['required', 'max:100'],
            'lname' => ['required', 'max:100'],
            'email' => ['required', 'max:100']
        ]);
        $user = Auth::user(); 
        $user->fname = $request->input('fname'); 
        $user->lname = $request->input('lname');
        $user->email = $request->input('email');  
        $user->save(); 
        return back()->with('success', "Name saved"); 
    }

    public function activity()
    {   
        $user = Auth::user(); 
        $activities = Bidder::where('user_id', $user->id)->latest()->paginate(10); 
        return view('profile.activity', compact('activities')); 
    }
}
