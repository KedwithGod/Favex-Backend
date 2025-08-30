<?php
namespace App\Http\Controllers;

class DashboardController extends Controller
{
    public function index()
    {
        // In a real app, gate with auth middleware
        return view('dashboard');
    }
}
