<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

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

            $validator = Validator::make($request->all(), [
                'image' => 'mimes:png,jpg,jpeg,webp|max:2048'
            ]);
     
            if ($validator->fails()) {
                $errors = $validator->getMessageBag();
                return redirect(route('home'))->with('status', $errors);
            }

            //$user_email = Auth::user()->email;
            $user_id = Auth::user()->id;
            $old_user_image = Auth::user()->image;

            $file_type = $request->image->extension();
            $file_name = 'profile_'.time() . '.' . $file_type;
            
            Storage::disk('public')->delete('/profile/' . $old_user_image);
            $request->image->storeAs('profile', $file_name, 'public');

            DB::table('users')
                ->where('id', $user_id)
                ->update(['image' => $file_name]);

            return redirect(route('home'))->with('status', 'Image uploaded successfully!');
        }
        return redirect(route('home'))->with('status', 'File not found');
    }
}
