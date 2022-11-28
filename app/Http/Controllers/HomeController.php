<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function upload(Request $request)
    {
        if ($request->hasFile('image')) {
            $user_email = Auth::user()->email;
            $user_id = Auth::user()->id;
            $user_image = Auth::user()->image;

            $file_type = $request->image->extension();
            $file_name = $user_email . '.' . $file_type;
            $request->image->storeAs('profile', $file_name, 'public');

            DB::table('users')
                ->where('id', $user_id)
                ->update(['image' => $file_name]);

            return redirect(route('home'))->with('status', 'Image uploaded successfully!');
        }
        return redirect()->back();
    }
}
