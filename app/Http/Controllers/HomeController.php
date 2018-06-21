<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;

class HomeController extends ApiController
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function index(Request $request)
    {
        dd($request->path());
    }

    //
}
