<?php

namespace App\Http\Controllers\Admin;

use App\Models\Post;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use stdClass;

class TagController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $tags = DB::table('tagging_tags')->paginate(10);
        return view('admin.tags.index', compact('tags'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $seasons = $this->seasonsYears()['seasons'];
        $years = $this->seasonsYears()['years'];
        //dd($seasons,$years);
        return view('admin.tags.create', compact('seasons', 'years'));
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
            'season' => 'required|string|max:50',
            'year' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $messageBag = $validator->getMessageBag();
            return redirect()
                ->back()
                ->withInput([
                    'season' => $request->input('season'),
                    'year' => $request->input('year')
                ])
                ->with('error', $messageBag);
        } else {
            $tag = new stdClass;
            $tag->name = preg_replace('/\s+/', ' ', $request->season . ' ' . $request->year);
            $tag->slug = Str::slug($tag->name);
            //dd($tag);
            
            try {
                DB::transaction(function () use ($tag) {
                    DB::table('tagging_tags')->insert([
                        'slug' => $tag->slug,
                        'name' => $tag->name
                    ]);
                });
                return redirect(route('admin.tags.index'))->with('success', 'Data Has Been Inserted Successfully');
            } catch (\Throwable $e) {
                return redirect(route('admin.tags.index'))->with('error', $e->getMessage());
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
        $tag = DB::table('tagging_tags')->find($id);
        $seasons = $this->seasonsYears()['seasons'];
        $years = $this->seasonsYears()['years'];

        return view('admin.tags.edit', compact('seasons', 'years', 'tag'));
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
        $validator = Validator::make($request->all(), [
            'season' => 'required|string|max:50',
            'year' => 'required|integer',
        ]);

        if ($validator->fails()) {
            $messageBag = $validator->getMessageBag();
            return redirect()
                ->back()
                ->withInput([
                    'season' => $request->input('season'),
                    'year' => $request->input('year')
                ])
                ->with('error', $messageBag);
        } else {
            $tag = new stdClass;
            $tag->name = preg_replace('/\s+/', ' ', $request->season . ' ' . $request->year);
            $tag->slug = Str::slug($tag->name);
            try {
                DB::transaction(function () use ($tag, $id) {
                    DB::table('tagging_tags')
                        ->where('id', $id)
                        ->update([
                            'slug' => $tag->slug,
                            'name' => $tag->name
                        ]);
                });
                return redirect(route('admin.tags.index'))->with('success', 'Data Has Been Updated Successfully');
            } catch (\Throwable $e) {
                return redirect(route('admin.tags.index'))->with('error', $e->getMessage());
            }
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
        $tag = DB::table('tagging_tags')->where('id', $id)->first();

        $posts = Post::withAnyTag($tag->name)->get();

        foreach ($posts as $post) {
            $post->untag($tag->name);
        }

        DB::table('tagging_tags')->where('id', $id)->delete();
        return redirect(route('admin.tags.index'))->with('success', 'Data deleted');
    }
    public function search(Request $request)
    {
        $tags = DB::table('tagging_tags')
            ->where('name', 'LIKE', '%'.$request->input('q').'%')
            ->paginate(10);
        return view('admin.tags.index', compact('tags'));
    }
    public function set($id)
    {
        DB::table('tagging_tags')
            ->where('flag', '1')
            ->update(['flag' => '0']);

        DB::table('tagging_tags')
            ->where('id', $id)
            ->update(['flag' => '1']);

        return redirect()->back()->with('success', 'Data has been updated');
    }
    public function unset($id)
    {
        DB::table('tagging_tags')
            ->where('id', $id)
            ->update(['flag' => '0']);

        return redirect()->back()->with('status', 'Data has been updated');
    }
    public function seasonsYears()
    {
        $years = [];
        $seasons = [];

        for ($i = 2025; $i > 1950; $i--) {
            $years[] = ['name' => $i, 'value' => $i];
        }

        $seasons = [
            ['name' => 'SPRING', 'value' => 'SPRING'],
            ['name' => 'SUMMER', 'value' => 'SUMMER'],
            ['name' => 'FALL', 'value' => 'FALL'],
            ['name' => 'WINTER', 'value' => 'WINTER']
        ];

        $data = [
            'years' => $years,
            'seasons' => $seasons
        ];

        return $data;
    }
}
