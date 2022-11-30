<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Conner\Tagging\Model\Tag;
use Illuminate\Support\Facades\DB;

class CurrentSeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seasons= DB::table('current_season')->get();
        return view('admin.season.index', compact('seasons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $seasons = Tag::all();
        return view('admin.season.create', compact('seasons'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = $request->season;

        DB::table('current_season')->insert([
            'name' => $name
        ]);

        return redirect(route('admin.season.index'))->with('status', 'Data Has Been Inserted Successfully');
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
        $season = DB::table('current_season')->find($id);

        $seasons = Tag::all();
        return view('admin.season.edit', compact('season','seasons'));
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
        //$request->all();
        //dd($request->season);

        $name = $request->season;
        
        
        DB::table('current_season')
              ->where('id', $id)
              ->update(['name' => $name]);
              

        return redirect(route('admin.season.index'))->with('status', 'Data has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        DB::table('current_season')->where('id', '=', $id)->delete();
        
        return redirect(route('admin.season.index'))->with('status', 'Data deleted');
    }
}
