<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\User;
use Conner\Tagging\Model\Tag;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use PhpParser\Node\Expr\FuncCall;
use stdClass;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
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

    public function uploadAvatar(Request $request)
    {
        //return response()->json($request->all());
        $validated = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:512',
        ]);

        $user = auth()->user(); // O tu método para obtener el usuario

        $old_user_image = $user->image;

        try {
            // Generar nombre del archivo
            $extension = $request->image->extension();
            $file_name = $user->slug . '-' . time() . '.' . $extension; // Añadimos timestamp para evitar caché
            $path = 'profile';

            // Almacenar el archivo
            $storedPath = $request->file('image')->storeAs(
                $path,
                $file_name,
                'public'
            );

            // Verificación física del archivo
            if (!Storage::disk('public')->exists($storedPath)) {
                throw new \Exception('El archivo no se pudo guardar en el almacenamiento');
            }

            // Actualizar modelo de usuario si es necesario
            $user->image = $storedPath;
            $user->save();

            if (isset($old_user_image) && Storage::disk('public')->exists($old_user_image)) {
                Storage::disk('public')->delete($old_user_image);
            }

            return response()->json([
                'message' => 'Avatar actualizado correctamente',
                'avatar_url' => asset("storage/" . $storedPath),
                /* 'file_path' => $storedPath // Para depuración */
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al subir la imagen',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function uploadBanner(Request $request)
    {
        //return response()->json($request->all());
        $validated = $request->validate([
            'banner' => 'required|image|mimes:jpeg,png,jpg,webp|max:512',
        ]);

        $user = auth()->user(); // O tu método para obtener el usuario

        $old_banner_image = $user->banner;

        try {
            // Generar nombre del archivo
            $extension = $request->banner->extension();
            $file_name = $user->slug . '-' . time() . '.' . $extension;
            $path = 'banner';

            // Almacenar el archivo
            $storedPath = $request->file('banner')->storeAs(
                $path,
                $file_name,
                'public'
            );

            // Verificación física del archivo
            if (!Storage::disk('public')->exists($storedPath)) {
                throw new \Exception('El archivo no se pudo guardar en el almacenamiento');
            }

            // Actualizar modelo de usuario si es necesario
            $user->banner = $storedPath;
            $user->save();

            if (isset($old_banner_image) && Storage::disk('public')->exists($old_banner_image)) {
                Storage::disk('public')->delete($old_banner_image);
            }

            return response()->json([
                'message' => 'Avatar actualizado correctamente',
                'banner_url' => asset("storage/" . $storedPath),
                /* 'file_path' => $storedPath // Para depuración */
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al subir la imagen',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function setRatingSystem(Request $request)
    {
        $validated = $request->validate([
            'score_format' => 'required|in:POINT_100,POINT_10_DECIMAL,POINT_10,POINT_5'
        ]);

        $user = Auth::check() ? Auth::User() : null;

        $user = User::find($user->id);
        $user->score_format = $request->score_format;
        $user->update();

        $data = [
            'message' => 'User score format updated successfully',
            'user' => $user,
            'request' => $request->all()
        ];

        $status = 200;

        return response()->json($data, $status);
    }
}
