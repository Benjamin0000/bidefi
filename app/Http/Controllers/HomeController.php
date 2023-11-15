<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; 
use App\Models\User;
use App\Models\Item; 
use App\Models\Message;
use App\Models\Blog; 
use App\Models\Faq; 

class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if($refID = request()->ref){
            if(!Auth::check()){
                $user = User::where('ref_id', $refID)->first(); 
                if($user)
                    session(['ref_by' => $user->address]);
            } 
        }
        $trendings = trendings(); 
        $live_auctions = live_auction(); 
        $top_bidders =  top_bidders(); 
        $starting_soon = starting_soon(); 
        $upcomings = upcoming(); 
        $completed = completed(); 
        $latest_winners = latest_winners(); 
        $user = Auth::user();  

        return view('home.index', compact(
            'trendings', 
            'live_auctions', 
            'top_bidders',
            'upcomings',
            'completed',
            'latest_winners',
            'user',
            'starting_soon'
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

    public function contact_us()
    {
        return view('home.contact'); 
    }

    public function save_message(Request $request)
    {
        $this->validate($request, [
            'name'=>['required', 'max:100'], 
            'email'=>['required', 'max:100'],
            'mobile'=>['required', 'max:100'],
            'message'=>['required', 'max:1000']
        ]); 
        $name = $request->input('name'); 
        $email = $request->input('email'); 
        $mobile = $request->input('mobile');
        $message = $request->input('message'); 
        
        Message::create([
            'name'=>$name,
            'email'=>$email,
            'mobile'=>$mobile,
            'message'=>$message
        ]);
        return back()->with('success', 'message created'); 
    }


    public function blog()
    {
        $blogs = Blog::where('publish', 1)->latest()->paginate(9); 
        return view('blog.index', compact('blogs')); 
    }

    public function show_blog($slug)
    {
        $blog = Blog::findBySlugOrFail($slug); 
        $blogs = Blog::where('id', '<>', $blog->id)->where('publish', 1)->latest()->take(10)->get(); 
        return view('blog.show', compact('blog', 'blogs')); 
    }

    public function increase_blog_views($id)
    {
        $item = Blog::find($id); 
        $item->views += 1; 
        $item->save(); 
    }

    public function faq()
    {
        $faqs = Faq::all(); 
        return view('faq', compact('faqs')); 
    }
}
 