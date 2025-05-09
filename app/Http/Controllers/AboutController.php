<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    /**
     * Display the about page
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Get the total count of users
        $userCount = User::count();

        return view('public.pages.about', compact('userCount'));
    }
}
