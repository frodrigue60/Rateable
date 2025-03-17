<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Season;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;

class SeasonController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $seasons = Season::all();
        return view('admin.seasons.index', compact('seasons'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.seasons.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $name = Str::upper($request->season_name);

        $validator = Validator::make($request->all(), [
            'season_name' => 'string|required|unique:seasons,name',
        ]);

        if ($validator->fails()) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors($validator);
        }

        $season = new Season();
        $season->name = $name;

        if ($season->save()) {
            return redirect(route('admin.seasons.index'))->with('success', 'Season saved successfully!');
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
        $season = Season::find($id);
        return view('admin.seasons.edit', compact('season'));
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

        $name = Str::upper($request->season_name);
        $exists = Season::where('name', $name)->exists();

        if ($exists) {
            return redirect(route('admin.seasons.index'))->with('warning', 'Season ' . $name . ' already exists!');
        }

        $season = Season::find($id);

        $season->name = $name;

        if ($season->update()) {
            return redirect(route('admin.seasons.index'))->with('success', 'Season updated successfully!');
        } else {
            return redirect(route('admin.seasons.index'))->with('danger', 'An error has been ocurred!');
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
        $season = Season::find($id);

        if ($season->delete()) {
            return redirect(route('admin.seasons.index'))->with('success', 'Season deleted successfully!');
        } else {
            return redirect(route('admin.seasons.index'))->with('danger', 'An error has been ocurred!');
        }
    }
}
