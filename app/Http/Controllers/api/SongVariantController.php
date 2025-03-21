<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SongVariant;
use Illuminate\Http\Request;
use App\Models\Year;
use App\Models\Season;

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

    public function like(Request $request)
    {
        // Validar la solicitud
        /* $request->validate([
            'variant_id' => 'required|integer|exists:song_variants,id',
            'user_id' => 'required|integer|exists:users,id',
        ]); */

        // Obtener el video
        $songVariant = SongVariant::find($request->variant_id);

        // Incrementar el contador de likes
        $songVariant->like($request->user_id);

        // Devolver la respuesta en formato JSON
        return response()->json([
            'likes' => $songVariant->likeCount,
        ]);
    }

    public function seasonal()
    {
        $type_OP = 'OP';
        $type_ED = 'ED';
        $currentSeason = Season::where('current', true)->first();
        $currentYear = Year::where('current', true)->first();

        $openings = SongVariant::with(['song.post'])
            #SONG QUERY
            ->whereHas('song', function ($query) use ($type_OP) {
                $query->when($type_OP, function ($query, $type) {
                    $query->where('type', $type);
                });
            })

            ->whereHas('song.post', function ($query) use ($currentSeason, $currentYear) {
                #POST QUERY
                $query->where('status', 'published')
                    /* ->when($char, function ($query, $char) {
                        $query->where('title', 'LIKE', "{$char}%");
                    }) */
                    ->when($currentSeason, function ($query, $currentSeason) {
                        $query->where('season_id', $currentSeason->id);
                    })
                    ->when($currentYear, function ($query, $currentYear) {
                        $query->where('year_id', $currentYear->id);
                    });
            })
            #SONG VARIANT QUERY
            ->get();

        $endings = SongVariant::with(['song.post'])
            #SONG QUERY
            ->whereHas('song', function ($query) use ($type_ED) {
                $query->when($type_ED, function ($query, $type) {
                    $query->where('type', $type);
                });
            })
            ->whereHas('song.post', function ($query) use ($currentSeason, $currentYear) {
                #POST QUERY
                $query->where('status', 'published')
                    /* ->when($char, function ($query, $char) {
                        $query->where('title', 'LIKE', "{$char}%");
                    }) */
                    ->when($currentSeason, function ($query, $currentSeason) {
                        $query->where('season_id', $currentSeason->id);
                    })
                    ->when($currentYear, function ($query, $currentYear) {
                        $query->where('year_id', $currentYear->id);
                    });
            })
            #SONG VARIANT QUERY
            ->get();

        $song_variants = $openings;
        $renderOps = view('layouts.variant.cards', compact('song_variants'))->render();

        $song_variants = $endings;
        $renderEds = view('layouts.variant.cards', compact('song_variants'))->render();   

        $data = ['openings' => $renderOps, 'endings' => $renderEds];

        return response()->json($data);
    }
}
