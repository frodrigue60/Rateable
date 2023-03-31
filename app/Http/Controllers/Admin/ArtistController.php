<?php

namespace App\Http\Controllers\Admin;

use App\Models\Artist;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ArtistController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $artists =  Artist::all();
        $artists = $artists->sortByDesc('created_at');
        $artists = $this->paginate($artists);
        return view('admin.artists.index', compact('artists'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.artists.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:50',
        ]);

        if ($validator->fails()) {
            $messageBag = $validator->getMessageBag();
            return redirect()
                ->back()
                ->withInput([
                    'name' => $request->input('name'),
                    'name_jp' => $request->input('name_jp')
                    ])
                ->with('error', $messageBag);
        } else {
            $name = preg_replace('/\s+/', ' ', $request->name);
            $name_jp = preg_replace('/\s+/', ' ', $request->name_jp);
            $name_slug = Str::slug($name);

            $artist = new Artist();
            $artist->name = $name;
            if ($request->name_jp != null) {
                $artist->name_jp = $name_jp;
            }
            $artist->name_slug = $name_slug;
            if ($artist->save()) {
                return redirect(route('admin.artist.index'))->with('success', 'Data Has Been Inserted Successfully');
            } else {
                return redirect(route('admin.artist.index'))->with('error', 'Something has wrong');
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
        $artist = Artist::find($id);
        return view('admin.artists.edit')->with('artist', $artist);
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
        $artist = Artist::find($id);

        $name = $request->name;
        $name_jp = $request->name_jp;
        $name_slug = Str::slug($name);

        if ($name != null) {
            $artist->name = $name;
            $artist->name_jp = $name_jp;
            $artist->name_slug = $name_slug;

            $artist->update();
            return redirect(route('admin.artist.index'))->with('success', 'Data Has Been Updated Successfully');
        } else {
            return redirect(route('admin.artist.index'))->with('error', 'Artist not Updated, "name" has not be null');
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
        $artist = Artist::find($id);

        DB::table('posts')
            ->where('artist_id', $artist->id)
            ->update(['artist_id' => null]);

        $artist->delete();

        return redirect(route('admin.artist.index'))->with('success', 'Data deleted');
    }
    public function searchArtist(Request $request)
    {

        $artists = DB::table('artists')
            ->where('name', 'LIKE', "%{$request->input('q')}%")
            ->paginate(10);
        return view('admin.artists.index', compact('artists'));
    }
    public function paginate($artists, $perPage = 10, $page = null, $options = [])
    {
        $page = Paginator::resolveCurrentPage();
        $options = ['path' => Paginator::resolveCurrentPath()];
        $items = $artists instanceof Collection ? $artists : Collection::make($artists);
        $artists = new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
        return $artists;
    }
}
