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
        $score_formats = ['POINT_100', 'POINT_10_DECIMAL', 'POINT_10', 'POINT_5'];
        return view('home', compact('score_formats'));
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

            return redirect(route('home'))->with('status', 'Image uploaded successfully!');
        }
        return redirect(route('home'))->with('status', 'File not found');
    }

    public function scoreFormat(Request $request)
    {
        if ($request->score_format == 'null') {
            return redirect()->back()->with('status', 'score method not changed');
        }

        $validator = Validator::make($request->all(), [
            'score_format' => 'required|in:POINT_100,POINT_10_DECIMAL,POINT_10,POINT_5'
        ]);

        if ($validator->fails()) {
            return Redirect::back()->with('status', '¡Ooops!');
        }

        $user_id = Auth::user()->id;
        if ($user_id == Auth::user()->id) {
            $user = User::find($user_id);
            $user->score_format = $request->score_format;
            $user->save();

            return redirect()->back()->with('status', 'score method changed successfully');
        }
        return Redirect::back()->with('status', '¡Ooops!');
    }
}
