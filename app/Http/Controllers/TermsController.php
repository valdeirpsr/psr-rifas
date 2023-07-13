<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TermsController extends Controller
{
    /**
     * Show the form for creating the resource.
     */
    public function index()
    {
        return view('terms')->render();
    }
}
