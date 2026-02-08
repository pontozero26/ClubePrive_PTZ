<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $logs = Log::where('level_name','INFO')
            ->orderBy('id', 'desc')
            ->get();
        return view('admin.logs.index', compact('logs'));
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $log = Log::find($id);
        return view('admin.logs.show', compact('log'));
    }
}
