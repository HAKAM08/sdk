<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Use middleware in routes instead
    }

    /**
     * Display the admin dashboard.
     */
    public function dashboard()
    {
        $totalUsers = User::where('is_admin', false)->count();
        $totalProducts = Product::count();
        $totalOrders = Order::count();
        $recentOrders = Order::with('user')->orderBy('created_at', 'desc')->take(5)->get();
        
        return view('admin.dashboard', compact('totalUsers', 'totalProducts', 'totalOrders', 'recentOrders'));
    }
}