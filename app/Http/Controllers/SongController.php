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
    public function show($id, $slug = null, $suffix = null)
    {

        $song = Song::with(['post', 'artists','videos'])->find($id);
        
        $comments = Comment::with('user', 'likeCounter')
            ->where('rateable_id', '=', $id)
            ->where('comment', '!=', "")
            ->latest()
            ->limit(10)
            ->get();

        $comments_featured = Comment::with('user', 'likeCounter')
            ->where('rateable_id', '=', $id)
            ->where('comment', '!=', "")
            ->get()
            ->sortByDesc('likeCount')
            ->take(3);


        if (Auth::check() == true && $song->averageRating == true) {

            switch (Auth::user()->score_format) {
                case 'POINT_100':
                    $score = round($song->averageRating);
                    break;

                case 'POINT_10_DECIMAL':
                    $score = round($song->averageRating / 10, 1);
                    break;

                case 'POINT_10':
                    $score = round($song->averageRating / 10);
                    break;

                case 'POINT_5':
                    $score = round($song->averageRating / 20);
                    break;

                default:
                    $score = round($song->averageRating / 10);
                    break;
            }
        } else {
            $score = null;
        }
        /* if (isset($song->artist->id)) {
            $artist = Artist::find($song->artist->id);
        } else {
            $artist = null;
        } */

        /* $this->count_views($song); */

        return view('public.songs.show', compact('song', 'score', 'artist', 'comments', 'comments_featured'));
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
        $key = 'post_' . $song->post->id . '_' . 'song_' . $song->id;
        if (!Session::has($key)) {
            DB::table('songs')
                ->where('id', $song->id)
                ->increment('view_count');
            Session::put($key, true);
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
            //$score = $request->score;
            $score_format = $request->score_format;

            $validator = Validator::make($request->all(), [
                'comment' => 'nullable|string|max:255',
                'score' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                $messageBag = $validator->getMessageBag();
                return redirect()
                    ->back()
                    ->with('error', $messageBag);
            }else{
                $score = $request->score;
            }

            switch ($score_format) {
                case 'POINT_100':
                    if (($score >= 1) && ($score <= 100)) {
                        $song->rateOnce($score);
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 100');
                    }
                    break;

                case 'POINT_10_DECIMAL':
                    if (($score >= 1) && ($score <= 10)) {
                        $song->rateOnce(intval($score * 10));
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 10 (can use decimals)');
                    }
                    break;
                case 'POINT_10':
                    if (($score >= 1) && ($score <= 10)) {
                        $song->rateOnce(intval($score * 10));
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 10 (only integer numbers)');
                    }
                    break;
                case 'POINT_5':
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
                        $song->rateOnce($score, $request->comment);
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 100');
                    }
                    break;


                default:
                    if (($score >= 1) && ($score <= 100)) {
                        $song->rateOnce($score*10, $request->comment);
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
