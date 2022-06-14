<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class HomeController extends Controller
{
    /**
     * Display regular user homepage.
     *
     * @return View
     */
    public function index(): View
    {
        return view('home', [
            'isAdmin' => false,
        ]);
    }

    /**
     * Display admin user homepage.
     *
     * This would normally be in a separate controller for admin related features.
     * But since both methods are almost identical, it is cleaner and more pragmatic to just keep it in the same controller.
     *
     * @return View
     */
    public function admin(): View
    {
        return view('home', [
            'isAdmin' => true,
        ]);
    }
}
