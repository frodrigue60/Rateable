<?php

namespace App\Http\Controllers;

use App\Models\UserRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class UserRequestController extends Controller
{
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

        $validator = Validator::make($request->all(), [
            'content' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            $messageBag = $validator->getMessageBag();
            return redirect()
                ->back()
                ->with('error', $messageBag);
        }
        
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
}
