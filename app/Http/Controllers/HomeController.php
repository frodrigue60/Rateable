<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
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
        $score_formats = [
            ['name' => ' 100 Point (55/100)', 'value' => 'POINT_100'],
            ['name' => '10 Point Decimal (5.5/10)', 'value' => 'POINT_10_DECIMAL'],
            ['name' => '10 Point (5/10)', 'value' => 'POINT_10'],
            ['name' => '5 Star (3/5)', 'value' => 'POINT_5'],
        ];

        if (Auth::check()) {
            $user = Auth::user();
            return view('home', compact('score_formats','user'));
        }
        else {
            return redirect()->route('/')->with('warning', 'Please login');
        }
        

        
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
            $file_name = 'profile_' . time() . '.' . $file_type;

            Storage::disk('public')->delete('/profile/' . $old_user_image);
            $request->image->storeAs('profile', $file_name, 'public');

            DB::table('users')
                ->where('id', $user_id)
                ->update(['image' => $file_name]);

            return redirect(route('home'))->with('success', 'Image uploaded successfully!');
        }
        return redirect(route('home'))->with('warning', 'File not found');
    }
    public function uploadBanner(Request $request)
    {
        if ($request->hasFile('banner')) {

            $validator = Validator::make($request->all(), [
                'banner' => 'mimes:png,jpg,jpeg,webp|max:2048'
            ]);

            if ($validator->fails()) {
                $errors = $validator->getMessageBag();
                return redirect(route('home'))->with('error', $errors);
            }

            $user_id = Auth::user()->id;

            $file_type = $request->banner->extension();
            $file_name = 'banner_' . time() . '.' . $file_type;

            if (Auth::user()->banner != null) {
                Storage::disk('public')->delete('/banner/' . Auth::user()->banner);
            }

            $request->banner->storeAs('banner', $file_name, 'public');

            DB::table('users')
                ->where('id', $user_id)
                ->update(['banner' => $file_name]);

            return redirect(route('home'))->with('success', 'Image uploaded successfully!');
        }
        return redirect(route('home'))->with('warning', 'File not found');
    }
    public function scoreFormat(Request $request)
    {
        if ($request->score_format == 'null') {
            return redirect()->back()->with('warning', 'score method not changed');
        }

        $validator = Validator::make($request->all(), [
            'score_format' => 'required|in:POINT_100,POINT_10_DECIMAL,POINT_10,POINT_5'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->with('error', '¡Ooops!');
        }

        $user_id = Auth::user()->id;
        if ($user_id == Auth::user()->id) {
            $user = User::find($user_id);
            $user->score_format = $request->score_format;
            $user->save();

            return redirect()->back()->with('success', 'score method changed successfully');
        }
        //return Redirect::back()->with('error', '¡Ooops!');
    }

    public function welcome()
    {
        return view('welcome');
    }
}
