<?php

namespace App\Http\Controllers;

use App\Models\SongVariant;
use Illuminate\Http\Request;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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
        $song_variant = SongVariant::where('song_id', '=', $song_id)
            ->where('version', '=', $version)
            ->with('likeCounter')
            ->first();

        $comments = $song_variant->commentsWithUser()
            ->get();

        $comments_featured = $song_variant->commentsWithUser()
            ->get()
            ->sortByDesc('likeCount')
            ->take(3);

        $score = null;

        if (Auth::check() && $song_variant->averageRating) {

            $user_rate = $this->user_rate($song_variant->id, Auth::user()->id);

            switch (Auth::user()->score_format) {
                case 'POINT_100':
                    $score = round($song_variant->averageRating);
                    $user_rate->format_rating = round($user_rate->rating);
                    break;

                case 'POINT_10_DECIMAL':
                    $score = round($song_variant->averageRating / 10, 1);
                    $user_rate->format_rating = round($user_rate->rating / 10, 1);
                    break;

                case 'POINT_10':
                    $score = round($song_variant->averageRating / 10);
                    $user_rate->format_rating = round($user_rate->rating / 10);
                    break;

                case 'POINT_5':
                    $score = round($song_variant->averageRating / 20);
                    $user_rate->format_rating = max(20, min(100, ceil($user_rate->rating / 20) * 20));
                    break;

                default:
                    $score = round($song_variant->averageRating / 10);
                    $user_rate->format_rating = max(20, min(100, ceil($user_rate->rating / 20) * 20));
                    break;
            }
        } else {
            $score = round($song_variant->averageRating / 10);
            $user_rate = null;
        }

        $song_variant->incrementViews();

        //dd($song_variant,$score,$comments,$comments_featured);

        return view('public.songs.variants.show', compact('song_variant', 'score', 'comments', 'comments_featured', 'user_rate'));
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

            /* switch ($score_format) {
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
            } */

            if ($score_format === 'POINT_5') {
                // Ajustar el score según las reglas específicas para POINT_5
                $score = max(20, min(100, ceil($score / 20) * 20));
            } else {
                // Ajustar el score según las reglas comunes para POINT_100, POINT_10_DECIMAL y POINT_10
                $score = max(1, min(100, ($score_format === 'POINT_10_DECIMAL') ? round($score * 10) : round($score)));
            }

            // Validar el rango del score
            if ($score >= 1 && $score <= 100) {
                // Utilizar el score ajustado
                $songVariant->rateOnce($score, $request->comment);
                return redirect()->back()->with('success', 'Post rated Successfully');
            } else {
                return redirect()->back()->with('warning', 'Only values between 1 and 100');
            }
        }
        return redirect()->route('login');
    }

    public function likeVariant($song_id, $variant_id)
    {
        if (Auth::check()) {
            SongVariant::find($variant_id)->like(Auth::user()->id);
            return redirect()->back()->with('success', 'Song Variant Like successfully!');
        }
        return redirect()->route('/')->with('warning', 'Please login');
    }

    public function unlikeVariant($song_id, $variant_id)
    {
        if (Auth::check()) {
            SongVariant::find($variant_id)->unlike(Auth::user()->id);
            return redirect()->back()->with('success', 'Song Variant Like undo successfully!');
        }
        return redirect()->route('/')->with('warning', 'Please login');
    }

    public function user_rate($song_variant_id, $user_id)
    {
        return DB::table('ratings')
            ->where('rateable_type', 'App\Models\SongVariant')
            ->where('rateable_id', $song_variant_id)
            ->where('user_id', $user_id)
            ->first(['comment', 'rating']);
    }
}
