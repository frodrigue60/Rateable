<?php

namespace App\Http\Controllers;

use App\Models\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class UserRequestController extends Controller
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
        return view('public.requests.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $userRequest = new UserRequest();
        $userRequest->content = $request->content;
        $userRequest->user_id = Auth::user()->id;
        
        if ($userRequest->save()) {
            return redirect('/')->with('success','Thank for your request');
        } else {
            return redirect('/')->with('error','Something has been wrong');
        }
        
        /* $content = $request->content;
        $user_id = Auth::user()->id;
        $attended_by = null;
        $status = 'pending';

        try {
            DB::table('requests')->insert([
                'content' => $content,
                'user_id' => $user_id,
                'attended_by' => $attended_by,
                'status' => $status,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Throwable $th) {
            //throw $th;
            return redirect('/')->with('error',$th->getMessage());
        }*/
        
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
        //
    }
}
