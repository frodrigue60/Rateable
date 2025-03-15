<?php

namespace App\Http\Controllers;

use App\Models\SongVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Favorite;

class FavoriteController extends Controller
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
        //
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

    public function toggle($songVariant_id)
    {
        $songVariant=SongVariant::find($songVariant_id);
        $user = Auth::user();

        // Verificar si el post ya está en favoritos
        $favorite = Favorite::where('user_id', $user->id)
            ->where('favoritable_id', $songVariant->id)
            ->where('favoritable_type', SongVariant::class)
            ->first();

        if ($favorite) {
            // Si ya está en favoritos, lo quitamos
            $favorite->delete();
            session()->flash('success', 'Post eliminado de favoritos.');
        } else {
            // Si no está en favoritos, lo agregamos
            Favorite::create([
                'user_id' => $user->id,
                'favoritable_id' => $songVariant->id,
                'favoritable_type' => SongVariant::class,
            ]);
            session()->flash('success', 'Post agregado a favoritos.');
        }

        return redirect()->back(); // Redirige de vuelta a la página anterior
    }
}
