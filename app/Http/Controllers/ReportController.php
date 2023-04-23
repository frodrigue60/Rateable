<?php

namespace App\Http\Controllers;

use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class ReportController extends Controller
{
    /**
     * Create a report
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function createReport(Request $request, $id)
    {
        if (Auth::check()) {

            $exist = DB::table('reports')
                ->where('post_id', $id)
                ->exists();

            if ($exist) {

                if (!Session::has('post_reported_' . $id)) {
                    DB::table('reports')
                        ->where('post_id', $id)
                        ->increment('nums');
                    Session::put('post_reported_' . $id, true);
                }
                return redirect()->back()->with('success', 'Report already exist, thanks for report this problem');
            } else {
                $report = new Report();
                $report->post_id = $id;
                $report->source = $request->header('Referer');
                $report->save();
                return redirect()->back()->with('success', 'Thanks for report this problem');
            }
        } else {
            return redirect('/')->with('warning', 'Please login to create a report');
        }
    }
}
