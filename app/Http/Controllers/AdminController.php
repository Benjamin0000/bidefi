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
use App\Models\Blog; 
use App\Models\Faq; 

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            $user = Auth::user();  
            if($user && !$user->admin)
                return redirect('/');  

            return $next($request);
        });
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
        $items = Item::latest()->paginate(10); 
        return view('admin.item.index', compact('items')); 
    }

    public function create_item($id=null)
    {
        $item = [];
         
        if($id) $item = Item::find($id);

        if( $last_item = Item::latest()->first() )
            $id = $last_item->id + 1; 
        else
            $id = 1; 

        return view('admin.item.create', compact('item', 'id')); 
    }

    public function edit($id)
    {
        $item = Item::findOrFail($id); 
        return view('admin.item.edit', compact('item')); 
    }

    public function save_item(Request $request)
    {
        $h = (int)$request->input('h');

        $item = new Item; 
        $item->name = $request->input('title');
        $item->description = $request->input('description');
        $item->image = $request->input('image'); 
        $item->image_type = $request->input('image_type');
        $item->url = $request->input('url'); 
        $item->price = $request->input('price');
        $item->start_price = $request->input('start_price');
        $item->ctd_timer = $request->input('start_time'); 
        $item->free_bid = $request->input('free')?:0;
        $item->prize = $request->input('prize')?:0;
        $item->symbol = $request->input('symbol');
        $item->type = $request->input('type'); 
        $item->contract_address = $request->input('contract_address'); 
        $item->min_bid = $request->input('min_bid'); 
        $item->start_points = $request->input('start_points'); 
        $item->network = $request->input('network'); 
        $item->decimal = $request->input('decimal') ?: 0;
        $item->h = $h; 
        $item->share = $request->input('share');
        $item->save(); 
        increase_items(); 
        return back()->with('success', 'item created'); 
    }

    public function update_item(Request $request, $id)
    {
        $item = Item::findOrFail($id);
        $h = (int)$request->input('h');
        $item->name = $request->input('title');
        $item->description = $request->input('description');
        $item->image = $request->input('image'); 
        $item->image_type = $request->input('image_type');
        $item->url = $request->input('url'); 
        $item->price = $request->input('price');
        $item->start_price = $request->input('start_price');
        // $item->ctd_timer = $request->input('start_time'); 
        $item->symbol = $request->input('symbol');
        $item->min_bid = $request->input('min_bid'); 
        //$item->start_points = $request->input('start_points'); 
        $item->h = $h;
        $item->save(); 
        return redirect(route('admin.items'))->with('success', 'Item updated');
    }

    public function delete_item($id)
    {
        $item = Item::findOrFail($id); 

        if($item->points > 0)
            return back()->with('error', 'This item cannot be deleted'); 

        $item->delete(); 
        return back()->with('success', "Item deleted");   
    }

    /**
     * show the settings page
     */
    public function settings()
    {
        return view('admin.settings.index'); 
    }
    /**
     * Update the minimum bid purchase by user
    */
    public function update_min_bid_credit(Request $request)
    {
        $amt = (int)$request->input('amount'); 
        $amt2 = (float)$request->input('fee_ref'); 
        $amt3 = (float)$request->input('min_fee_with'); 
        set_register('min_bid_purchase', $amt); 
        set_register('fee_ref', $amt2); 
        set_register('min_fee_with', $amt3); 

        return back()->with('success', "Min  updated"); 
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

    public function blog()
    { 
        $blogs = Blog::latest()->paginate(10); 
        return view('admin.blog.index', compact('blogs')); 
    }

    public function create_blog($id=null)
    {
        $blog = null; 
        if($id)
            $blog = Blog::findOrFail($id); 
        
        return view('admin.blog.create', compact('blog')); 
    }

    public function save_blog(Request $request, $id=null)
    {
        if($id)
            $blog = Blog::findOrFail($id); 
        else 
            $blog = new Blog; 

        $blog->poster = $request->input('poster'); 
        $blog->title = $request->input('title'); 
        $blog->body = $request->input('body'); 
        $blog->caption = $request->input('caption'); 
        $blog->save(); 

        if($id)
            $message = "Blog updated"; 
        else 
            $message = "Blog created"; 

        return redirect(route('admin.blog.index'))->with('success', $message); 
    }

    public function publish($id)
    {
        $blog = Blog::findOrFail($id);
        $blog->publish = !$blog->publish; 
        $blog->save(); 

        if($blog->publish)
            return back()->with('success', 'blog published'); 
        else
            return back()->with('success', 'blog is now hidden'); 
    }

    public function delete_blog($id)
    {
        Blog::findOrFail($id)->delete(); 
        return back()->with('success', 'Post deleted'); 
    }

    public function faq()
    {
        $faqs = Faq::latest()->paginate(10); 
        return view('admin.faq.index', compact('faqs')); 
    }

    public function create_faq($id=null)
    {
        $faq = null; 
        if($id)
            $faq = Faq::findOrFail($id); 
        
        return view('admin.faq.create', compact('faq')); 
    }

    public function store_faq(Request $request, $id=null)
    {
        if($id)
            $faq = Faq::findOrFail($id); 
        else 
            $faq = new Faq; 
        $faq->q = $request->input('q'); 
        $faq->a = $request->input('a'); 
        $faq->save(); 
        return redirect(route('admin.faq.index'))->with('success', 'FAQ saved'); 
    }

    public function delete_faq($id)
    {
        $faq = Faq::findOrFail($id); 
        $faq->delete(); 
        return back()->with('success', 'faq deleted'); 
    }

}
