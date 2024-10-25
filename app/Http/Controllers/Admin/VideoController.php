<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Song;
use App\Models\SongVariant;
use App\Models\Video;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use App\Jobs\ProcessVideo;

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
    public function create($song_id, $variant_id)
    {
        if ($variant_id != null) {
            $song_variant = SongVariant::find($variant_id);
            $song = $song_variant->song;
            return view('admin.songs.variants.videos.create', compact('song', 'song_variant'));
        } else {
            return view('admin.videos.create', compact('song'));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, $song_id, $variant_id)
    {
        $path = null;
        $file_name = null;

        try {
            $song = Song::find($song_id);

            $video = new Video();
            $video->song_id = $song->id;

            $video->song_variant_id = $variant_id;

            if ($request->hasFile('video')) {
                $validator = Validator::make($request->all(), [
                    'video' => 'mimes:webm,mp4'
                ]);

                if ($validator->fails()) {
                    $errors = $validator->getMessageBag();
                    $request->flash();
                    return Redirect::back()->with('error', $errors);
                }

                switch ($video->song->type) {
                    case 'OP':
                        $path = "videos/openings/";
                        break;

                    case 'OP':
                        $path = "videos/endings/";
                        break;

                    default:
                        $path = "videos/";
                        break;
                }
                /* if ($song->type == "OP") {
                    $path = "videos/openings/";
                } else {
                    $path = "videos/endings/";
                } */
                $mimeType = $request->video->getMimeType();
                $extension = $this->getExtensionFromMimeType($mimeType);

                $file_name = $song->post->slug . '-' . strtolower($song->suffix) . '-' . time() . '.' . $extension;
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
            //dd($video);
            if ($video->save()) {
                if ($video->type === "file") {
                    $request->video->storeAs($path, $file_name, 'public');
                }
                return redirect(route('song.post.manage', $video->song->post->id))->with('success', 'saved successfully');
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

                switch ($video->song->type) {
                    case 'OP':
                        $path = "videos/openings/";
                        break;

                    case 'OP':
                        $path = "videos/endings/";
                        break;

                    default:
                        $path = "videos/";
                        break;
                }
                /* if ($video->song->type == "OP") {
                    $path = "videos/openings/";
                } else {
                    $path = "videos/endings/";
                } */
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
                    //$request->video->storeAs($path, $file_name, 'public');

                    $videoPath = $request->file('video')->store('videos', 'public');
                    // Despachar el job para procesar el video
                    ProcessVideo::dispatch($videoPath);
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

    protected function getExtensionFromMimeType($mimeType)
    {
        $mimeMap = [
            'image/jpeg' => 'jpg',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'application/pdf' => 'pdf',
            'video/mp4' => 'mp4',
            'video/quicktime' => 'mov',
            'audio/mpeg' => 'mp3',
            'audio/wav' => 'wav',
        ];

        return $mimeMap[$mimeType] ?? 'bin';
    }
}
