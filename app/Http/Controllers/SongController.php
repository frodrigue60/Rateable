<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Song;
use App\Models\Artist;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;

class SongController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        
        $song = Song::with(['post','artist'])->find($id);
        //dd($song);
        $comments = Comment::with('user','likeCounter')
        ->where('rateable_id','=',$id)
        ->latest()
        ->limit(10)
        ->get();

        $comments_featured = Comment::with('user','likeCounter')
        ->where('rateable_id','=',$id)
        ->get()
        ->sortByDesc('likeCount')
        ->take(3);
        
        if (Auth::check()) {
            $score_format = Auth::user()->score_format;
        } else {
            $score_format = null;
        }
        if (isset($song->artist->id)) {
            $artist = Artist::find($song->artist->id);
        } else {
            $artist = null;
        }

        $this->count_views($song);

        //dd($artist);
        return view('public.songs.show', compact('song', 'score_format', 'artist','comments','comments_featured'));
    }

    public function likeSong($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            Song::find($id)->like($user->id);

            return redirect()->back()->with('success', 'Song Like successfully!');
        }
        return redirect()->route('/')->with('warning', 'Please login');
    }

    public function unlikeSong($id)
    {
        if (Auth::check()) {
            $user = Auth::user();
            Song::find($id)->unlike($user->id);

            return redirect()->back()->with('success', 'Song Like undo successfully!');
        }
        return redirect()->route('/')->with('warning', 'Please login');
    }

    public function count_views($song)
    {
        $key = 'post_'.$song->post->id.'_'.'song_'.$song->id;
       //dd($key);
        if (!Session::has($key)) {
            DB::table('songs')
                ->where('id', $song->id)
                ->increment('view_count');
            Session::put($key,true);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function rateSong(Request $request, $id)
    {
        if (Auth::check()) {
            //dd($request->all());
            $song = Song::find($id);
            $score = $request->score;
            $score_format = $request->score_format;

            $validator = Validator::make($request->all(), [
                'comment' => 'nullable|string|max:255',
                'score' => 'required'
            ]);
    
            if ($validator->fails()) {
                $messageBag = $validator->getMessageBag();
                return redirect()
                    ->back()
                    ->with('error', $messageBag);
            }

            if (blank($score)) {
                return redirect()->back()->with('warning', 'Score can not be null');
            }
            switch ($score_format) {
                case 'POINT_100':
                    settype($score, "integer");
                    if (($score >= 1) && ($score <= 100)) {
                        $song->rateOnce($score);
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 100');
                    }
                    break;

                case 'POINT_10_DECIMAL':
                    settype($score, "float");
                    if (($score >= 1) && ($score <= 10)) {
                        $int = intval($score * 10);
                        $song->rateOnce($int);
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 10 (can use decimals)');
                    }
                    break;
                case 'POINT_10':
                    settype($score, "integer");
                    if (($score >= 1) && ($score <= 10)) {
                        $int = intval($score * 10);
                        $song->rateOnce($int);
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 10 (only integer numbers)');
                    }
                    break;
                case 'POINT_5':
                    settype($score, "integer");

                    if (($score >= 1) && ($score <= 100)) {
                        if ($score <= 20) {
                            $score = 20;
                        }
                        if (($score > 20) && ($score <= 40)) {
                            $score = 40;
                        }
                        if (($score > 40) && ($score <= 60)) {
                            $score = 60;
                        }
                        if (($score > 60) && ($score <= 80)) {
                            $score = 80;
                        }
                        if ($score > 80) {
                            $score = 100;
                        }
                        $song->rateOnce($score,$request->comment);
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 100');
                    }
                    break;


                default:
                    settype($score, "integer");
                    if (($score >= 1) && ($score <= 100)) {
                        $song->rateOnce($score,$request->comment);
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 100');
                    }
                    break;
            }
        }
        return redirect()->route('login');
    }
}
