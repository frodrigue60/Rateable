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
        $song = Song::find($song_id);
        $songVariant = SongVariant::find($variant_id);
        $video = Video::where('song_variant_id', $songVariant->id)->first();
        //dd($song, $songVariant, $video);

        if ($video) {
            return redirect(route('song.post.manage', $song->post->id))->with('warning', 'Video already exist');
        } else {
            try {
                $video = new Video();
                //$video->song_id = $song->id;

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

                    $path = null;
                    $file_name = null;

                    switch ($song->type) {
                        case 'OP':
                            $path = "videos/openings/";
                            break;

                        case 'ED':
                            $path = "videos/endings/";
                            break;

                        default:
                            $path = "videos/";
                            break;
                    }

                    $mimeType = $request->video->getMimeType();
                    $extension = $this->getExtensionFromMimeType($mimeType);

                    $file_name = $song->post->slug . '-' . strtolower($song->slug) . '-' . time() . '.' . $extension;
                    $video->video_src = $path . $file_name;

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
                    $video->type = 'embed';
                }

                if ($video->save()) {
                    if ($video->type === "file") {
                        //Storage::disk('public')->put($path,$file_name.$request->video);
                        $request->video->storeAs($path, $file_name, 'public');
                    }
                    return redirect(route('song.post.manage', $song->post->id))->with('success', 'saved successfully');
                }
            } catch (ModelNotFoundException $e) {
                return redirect(route('song.post.manage', $song->post->id))->with('error', $e);
            }
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

                    case 'ED':
                        $path = "videos/endings/";
                        break;

                    default:
                        $path = "videos/";
                        break;
                }

                $old_file = $video->video_src;

                $mimeType = $request->video->getMimeType();
                $extension = $this->getExtensionFromMimeType($mimeType);

                $file_name = $video->song->post->slug . '-' . strtolower($video->song->slug) . '-' . time() . '.' . $extension;
                $video->video_src = $path . $file_name;


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
            //dd($old_file);
            $video->update();

            switch ($video->type) {
                case 'file':
                    //Delete old video file
                    //$filePath = str_replace('/storage', 'public', $old_file);
                    if (isset($old_file) && Storage::disk('public')->exists($old_file)) {
                        Storage::disk('public')->delete($old_file);
                    }

                    //Store new video file
                    //Storage::disk('public')->put('$path',$file_name.$request->video);
                    $request->video->storeAs($path, $file_name, 'public');

                    return redirect(route('song.post.manage', $video->song->post->id))->with('success', 'saved successfully');
                    break;

                case 'embed':
                    return redirect(route('song.post.manage', $video->song->post->id))->with('success', 'saved successfully');
                    break;

                default:
                    return redirect(route('song.post.manage', $video->song->post->id))->with('warning', 'error saving video');
                    break;
            }
        } catch (ModelNotFoundException $e) {
            return redirect(route('song.post.manage', $video->song->post->id))->with('error', $e);
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
        $video = Video::findOrFail($id);
        try {
            if ($video->delete()) {
                return redirect(route('song.post.manage', $video->song->post->id))->with('success', 'item deleted successfully');
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
            'image/webp' => 'webp',
            'image/png' => 'png',
            'image/gif' => 'gif',
            'application/pdf' => 'pdf',
            'video/mp4' => 'mp4',
            'video/quicktime' => 'mov',
            'video/webm' => 'webm',
            'audio/mpeg' => 'mp3',
            'audio/wav' => 'wav',
        ];

        return $mimeMap[$mimeType] ?? 'bin';
    }
}
