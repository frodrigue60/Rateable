<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Comment;
use App\Models\User;
use App\Models\Reaction;

class CommentController extends Controller
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
        $comment = Comment::findOrFail($id);
        try {
            $user = Auth::check() ? Auth::user() : null;

            if (($comment->user_id == $user->id) || $user->isAdmin()) {
                $comment->delete();
            }

            return response()->json([
                'message' => 'Comment deleted successfully',
                'success' => true,
                /* 'comment' => $comment, */
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Ah error has been occurred',
                'success' => false,
                /* 'comment' => $comment, */
                'th' => $th
            ]);
        }
    }

    public function like($comment_id)
    {
        if (Auth::check()) {
            $comment = Comment::find($comment_id);
            $this->handleReaction($comment, 1); // 1 para like
            $comment->updateReactionCounters(); // Actualiza los contadores manualmente
            return redirect()->back(); // Redirige de vuelta a la página anterior
        }
        return redirect()->route('/')->with('warning', 'Please login');
    }

    public function dislike($comment_id)
    {
        if (Auth::check()) {
            $comment = Comment::find($comment_id);
            $this->handleReaction($comment, -1); // 1 para like
            $comment->updateReactionCounters(); // Actualiza los contadores manualmente
            return redirect()->back(); // Redirige de vuelta a la página anterior
        }
        return redirect()->route('/')->with('warning', 'Please login');
    }

    private function handleReaction($comment, $type)
    {
        $user = Auth::user();

        // Buscar si ya existe una reacción del usuario para este post
        $reaction = Reaction::where('user_id', $user->id)
            ->where('reactable_id', $comment->id)
            ->where('reactable_type', Comment::class)
            ->first();

        if ($reaction) {
            if ($reaction->type === $type) {
                // Si la reacción es la misma, eliminarla (toggle)
                $reaction->delete();
            } else {
                // Si la reacción es diferente, actualizarla
                $reaction->update(['type' => $type]);
            }
        } else {
            // Si no existe una reacción, crear una nueva
            Reaction::create([
                'user_id' => $user->id,
                'reactable_id' => $comment->id,
                'reactable_type' => Comment::class,
                'type' => $type,
            ]);
        }
    }
}
