<?php

namespace App\Http\Controllers;

use App\Models\SongVariant;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SongVariantController extends Controller
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
    public function show($song_id, $slug = null, $suffix = null, $version = null)
    {
        //dd($song_id, $suffix, $version);

        //$song = Song::with(['post', 'artists','videos'])->find($id);
        $song_variant = SongVariant::where('song_id', '=', $song_id)
        ->where('version', '=', $version)
        ->with('likeCounter')
        ->first();

        //$song = $song_variant->song;

        $comments = Comment::with('user', 'likeCounter')
            ->where('rateable_id', '=', $song_id)
            ->where('comment', '!=', "")
            ->latest()
            ->limit(10)
            ->get();

        $comments_featured = Comment::with('user', 'likeCounter')
            ->where('rateable_id', '=', $song_id)
            ->where('comment', '!=', "")
            ->get()
            ->sortByDesc('likeCount')
            ->take(3);


        if (Auth::check() == true && $song_variant->averageRating == true) {

            switch (Auth::user()->score_format) {
                case 'POINT_100':
                    $score = round($song_variant->averageRating);
                    break;

                case 'POINT_10_DECIMAL':
                    $score = round($song_variant->averageRating / 10, 1);
                    break;

                case 'POINT_10':
                    $score = round($song_variant->averageRating / 10);
                    break;

                case 'POINT_5':
                    $score = round($song_variant->averageRating / 20);
                    break;

                default:
                    $score = round($song_variant->averageRating / 10);
                    break;
            }
        } else {
            $score = null;
        }
        
        $song_variant->incrementViews();

        //dd($song_variant,$score,$comments,$comments_featured);

        return view('public.songs.variants.show', compact('song_variant', 'score', 'comments', 'comments_featured'));
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

    public function rate(Request $request, $song_id, $variant_id)
    {
        if (Auth::check()) {
            
            $songVariant = SongVariant::find($variant_id);
            
            $score_format = Auth::user()->score_format;

            $validator = Validator::make($request->all(), [
                'comment' => 'nullable|string|max:255',
                'score' => 'required|numeric'
            ]);

            if ($validator->fails()) {
                $messageBag = $validator->getMessageBag();
                return redirect()
                    ->back()
                    ->with('error', $messageBag);
            } else {
                $score = $request->score;
            }

            switch ($score_format) {
                case 'POINT_100':
                    if (($score >= 1) && ($score <= 100)) {
                        $songVariant->rateOnce($score);
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 100');
                    }
                    break;

                case 'POINT_10_DECIMAL':
                    if (($score >= 1) && ($score <= 10)) {
                        $songVariant->rateOnce(intval($score * 10));
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 10 (can use decimals)');
                    }
                    break;
                case 'POINT_10':
                    if (($score >= 1) && ($score <= 10)) {
                        $songVariant->rateOnce(intval($score * 10));
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
                        $songVariant->rateOnce($score, $request->comment);
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 100');
                    }
                    break;


                default:
                    if (($score >= 1) && ($score <= 100)) {
                        $songVariant->rateOnce($score * 10, $request->comment);
                        return redirect()->back()->with('success', 'Post rated Successfully');
                    } else {
                        return redirect()->back()->with('warning', 'Only values between 1 and 100');
                    }
                    break;
            }
        }
        return redirect()->route('login');
    }

    public function likeVariant($song_id,$variant_id)
    {
        if (Auth::check()) {
            SongVariant::find($variant_id)->like(Auth::user()->id);
            return redirect()->back()->with('success', 'Song Variant Like successfully!');
        }
        return redirect()->route('/')->with('warning', 'Please login');
    }

    public function unlikeVariant($song_id,$variant_id)
    {
        if (Auth::check()) {
            SongVariant::find($variant_id)->unlike(Auth::user()->id);
            return redirect()->back()->with('success', 'Song Variant Like undo successfully!');
        }
        return redirect()->route('/')->with('warning', 'Please login');
    }

}
