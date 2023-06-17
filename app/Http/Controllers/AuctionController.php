<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
      
    }
    /**
     * Show the form for creating the acution by the admin
     */
    public function create()
    {
        return view('admin.create_item'); 
    }
}
