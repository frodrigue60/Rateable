<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Song;
use App\Models\Video;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class VideoController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $song_id
     * @return \Illuminate\Http\Response
     */
    public function index($song_id)
    {
        $song = Song::findOrFail($song_id);

        return view('admin.videos.index', compact('song'));
    }

    /**
     * Show the form for creating a new resource.
     * @param  int  $song_id
     * @return \Illuminate\Http\Response
     */
    public function create($song_id)
    {
        $song = Song::findOrFail($song_id);
        return view('admin.videos.create', compact('song'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $song_id)
    {
        try {
            $song = Song::find($song_id);

            $video = new Video();
            $video->song_id = $song->id;

            if ($request->hasFile('video')) {
                $validator = Validator::make($request->all(), [
                    'video' => 'mimes:webm,mp4'
                ]);

                if ($validator->fails()) {
                    $errors = $validator->getMessageBag();
                    $request->flash();
                    return Redirect::back()->with('error', $errors);
                }
                if ($song->type == "OP") {
                    $path = "videos/openings/";
                } else {
                    $path = "videos/endings/";
                }
                $file_name = $song->post->slug . '-' . strtolower($song->suffix) . '-' . time() . '.' . 'webm';
                $video->video_src = '/storage/' . $path . $file_name;
                //Storage::disk('public')->put('/videos/',$file_name.$request->video);

                $video->type = 'file';
            } else {
                $validator = Validator::make($request->all(), [
                    'embed' => 'required'
                ]);

                if ($validator->fails()) {
                    $errors = $validator->getMessageBag();
                    $request->flash();
                    return Redirect::back()->with('error', $errors);
                }
                $video->embed_code = $request->embed;
                $video->video_src = null;
                $video->type = 'embed';
            }

            if ($video->save()) {
                if ($video->type === "file") {
                    $request->video->storeAs($path, $file_name, 'public');
                }
                return redirect(route('admin.videos.index', $video->song->id))->with('success', 'saved successfully');
            }
        } catch (ModelNotFoundException $e) {
            return redirect(route('admin.videos.index', $video->song->id))->with('error', $e);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $video = Video::findOrFail($id);
            dd($video);
        } catch (ModelNotFoundException $e) {
            dd($e);
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
        try {
            $video = Video::findOrFail($id);
            return view('admin.videos.edit', compact('video'));
        } catch (ModelNotFoundException $e) {
            dd($e);
        }
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
        try {
            $video = Video::findOrFail($id);
            //dd($request->all(),$video->song);
            if ($request->hasFile('video')) {
                $validator = Validator::make($request->all(), [
                    'video' => 'mimes:webm,mp4'
                ]);

                if ($validator->fails()) {
                    $errors = $validator->getMessageBag();
                    $request->flash();
                    return Redirect::back()->with('error', $errors);
                }
                if ($video->song->type == "OP") {
                    $path = "videos/openings/";
                } else {
                    $path = "videos/endings/";
                }
                $file_name = $video->song->post->slug . '-' . strtolower($video->song->suffix) . '-' . time() . '.' . 'webm';
                $video->video_src = $path . $file_name;
                //Storage::disk('public')->put('/videos/',$file_name.$request->video);

                $video->type = 'file';
            } else {
                $validator = Validator::make($request->all(), [
                    'embed' => 'required'
                ]);

                if ($validator->fails()) {
                    $errors = $validator->getMessageBag();
                    $request->flash();
                    return Redirect::back()->with('error', $errors);
                }
                $video->embed_code = $request->embed;
                $video->video_src = null;
                $video->type = 'embed';
            }

            if ($video->save()) {
                if ($video->type === "file") {
                    $request->video->storeAs($path, $file_name, 'public');
                }
                return redirect(route('admin.videos.index', $video->song->id))->with('success', 'saved successfully');
            }
        } catch (ModelNotFoundException $e) {
            return redirect(route('admin.videos.index', $video->song->id))->with('error', $e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $video = Video::findOrFail($id);

            if ($video->delete()) {
                if ($video->type == "file") {
                    Storage::disk('public')->delete($video->video_src);
                }
                return redirect(route('admin.videos.index', $video->song->id))
                    ->with('success', 'Video ' . $video->id . ' deleted successfully');
            }
        } catch (ModelNotFoundException $e) {
            return redirect(route('admin.videos.index', $video->song->id))
                ->with('error', $e);
        }
    }
}
