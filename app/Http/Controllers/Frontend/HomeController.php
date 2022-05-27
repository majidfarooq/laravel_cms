<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Experience;
use App\Models\Post;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('web');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $cat = Category::where('parent_id', null)->get();
        $cities = Experience::distinct()->get(['city']);
        $posts = Post::take(3)->orderBy('created_at','DESC')->get();
        return view('frontend.home.home', compact('cat', 'cities','posts'));
    }
}
