<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class HomeAction extends Controller
{
    public function __invoke()
    {
        return Inertia::render('home');
    }
}
