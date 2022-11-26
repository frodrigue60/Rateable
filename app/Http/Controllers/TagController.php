<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use App\Models\Tag;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;




class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //$tags = Tag::all();
        $tags = DB::table('tagging_tags')->paginate(10);
        //dd($tags);

        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tags.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->name;
        $slug = Str::slug($request->name);

        DB::table('tagging_tags')->insert([
            'slug' => $slug,
            'name' => $name
        ]);

        return redirect(route('admin.tags.index'))->with('status', 'Data Has Been Inserted Successfully');
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
        $tag = DB::table('tagging_tags')->find($id);

        return view('admin.tags.edit')->with('tag', $tag);
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
        $name = $request->name;
        $slug = Str::slug($request->name);

        DB::table('tagging_tags')
            ->where('id', $id)
            ->update(['name' => $name, 'slug' => $slug]);


        return redirect(route('admin.tags.index'))->with('status', 'Data has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('tagging_tags')->where('id', '=', $id)->delete();

        return redirect(route('admin.tags.index'))->with('status', 'Data deleted');
    }

    public function slug($name)
    {
        $openings = Post::withAnyTag([$name])
        ->where('type', 'op')
        ->get();; // fetch articles with any tag listed

        //$tags = DB::table('tagging_tags')->get();
        $endings = Post::withAnyTag([$name])
        ->where('type', 'ed')
        ->get();

        //dd($endings,$openings);
        return view('fromTags', compact('openings','endings'));
    }

    public function alltags()
    {
        $tags = DB::table('tagging_tags')->get();

        return view('tags', compact('tags'));
    }

    public function searchTag(Request $request)
    {
        if (Auth::check()) {
            if (Auth::user()->type == 'admin') {
                $tags = DB::table('tagging_tags')
                    ->where('name', 'LIKE', "%{$request->input('search')}%")
                    ->paginate(10);

                return view('admin.tags.index', compact('tags'));
            }
            return redirect()->route('/')->with('status', 'Only admins');
        } else {
            return redirect()->route('/')->with('status', 'Please login');
        }
    }
}
