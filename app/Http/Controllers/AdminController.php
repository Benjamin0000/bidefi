<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use App\Models\Image; 
use App\Models\Item; 
use App\Models\User;
use App\Models\Admins;
use App\Models\WHistory; 


class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $user = Auth::user(); 
        if($user && !$user->admin)
            return redirect('/'); 
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::latest()->paginate(10); 
        $total_users = (int)get_register('total_users'); 
        $total_items = (int)get_register('total_items'); 
        return view('admin.dashboard.index', compact('users', 'total_users', 'total_items')); 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function image()
    {
        $medias = Image::latest()->paginate(10); 
        return view('admin.image.index', compact('medias')); 
    }

    public function create_image(Request $request)
    { 
        $this->validate($request, [
            'image'=>['required']
        ]);
        $type = (int)$request->input('type'); 
        $file = $request->file('image'); 
        $path = $file->storePublicly('public/images');
        Image::create(['name'=>$path,'type'=>$type]);
        return back()->with('success', "Media uploaded"); 
    }

    public function delete_image($id)
    {
        $image = Image::findOrFail($id); 
        if(Storage::exists($image->name))
            Storage::delete($image->name);
        $image->delete(); 
        return back()->with('success', "Media deleted"); 
    }
    /**
     * Show the items page
     */
    public function items()
    {
        $items = Item::paginate(10); 
        return view('admin.item.index', compact('items')); 
    }

    public function create_item()
    {
        return view('admin.item.create'); 
    }

    public function save_item(Request $request)
    {
        $item = new Item(); 
        $item->name = $request->input('title');
        $item->description = $request->input('description');
        $item->image = $request->input('image'); 
        $item->image_type = $request->input('image_type');
        $item->url = $request->input('url'); 
        $item->price = $request->input('price');
        $item->start_price = $request->input('start_price');
        $item->start_time = now()->addMinutes($request->input('start_time')); 
        $item->free_bid = $request->input('free')?:0;
        $item->prize = $request->input('prize')?:0;
        $item->symbol = $request->input('symbol');
        $item->type = $request->input('type'); 
        $item->contract_address = $request->input('contract_address'); 
        $item->min_bid = $request->input('min_bid'); 
        $item->save(); 
        increase_items(); 
        return back()->with('success', 'item created'); 
    }
    /**
     * show the settings page
     */
    public function settings()
    {
        return view('admin.settings.index'); 
    }
    /**
     * Update the bid price
     */
    public function update_bid_price(Request $request)
    {
        $price = (float)$request->input('price'); 
        set_register('bid_price', $price); 
        return back()->with('success', "Bid price updated"); 
    }
    /**
     * Update the minimum bid purchase by user
    */
    public function update_min_bid_credit(Request $request)
    {
        $amt = (int)$request->input('amount'); 
        set_register('min_bid_purchase', $amt); 
        return back()->with('success', "Min bid purchase updated"); 
    }

    public function admins()
    {
        $admins = Admins::paginate(10); 
        return view('admin.admins.index', compact('admins')); 
    }

    public function create_admin(Request $request)
    {
        $address = $request->input('address'); 
        $check = $request->input('check');
        $name = $request->input('name'); 

        if($check){
            if( !User::where('address', $address)->exists() )
                return ['error'=>'User does not exist']; 
        }else{
            $admin = Admins::create([
                'name'=>$name,
                'address'=>$address
            ]);         
            if( $user = User::where('address',  $admin->address)->first() ){
                $user->admin = 1; 
                $user->save(); 
            }
            return back()->with('success', 'admin created'); 
        }
    }

    public function remove_admin($id)
    {
        $find = Admins::findOrFail($id); 
        if( $user = User::where('address', $find->address)->first() ){
            $user->admin = 0; 
            $user->save(); 
        }
        $find->delete(); 
        return back()->with('success', 'Admin removed'); 
    }

    public function withdrawal()
    {
        $histories = WHistory::latest()->paginate(10); 
        return view('admin.withdrawal.index', compact('histories')); 
    }

    public function create_withdrawal(Request $request)
    {
        $amt = $request->input('amt'); 
        WHistory::create(['amt'=>$amt]); 
        return back()->with('success', 'Withdrawal created'); 
    }

    
}
