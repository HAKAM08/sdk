<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // Middleware is applied in routes
    }

    /**
     * Display the user's dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        $user = Auth::user();
        $recentOrders = Order::where('user_id', $user->id)
                            ->orderBy('created_at', 'desc')
                            ->take(5)
                            ->get();
        
        return view('user.dashboard', compact('user', 'recentOrders'));
    }

    /**
     * Display the user's profile.
     *
     * @return \Illuminate\Http\Response
     */
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    /**
     * Update the user's profile.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function updateProfile(Request $request)
    {
        $user = Auth::user();
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
        ]);
        
        $user->update($validated);
        
        return redirect()->route('user.profile')->with('success', 'Profile updated successfully!');
    }

    /**
     * Display the user's orders.
     *
     * @return \Illuminate\Http\Response
     */
    public function orders()
    {
        $user = Auth::user();
        $orders = Order::where('user_id', $user->id)
                      ->orderBy('created_at', 'desc')
                      ->paginate(10);
        
        return view('user.orders', compact('orders'));
    }

    /**
     * Display a specific order.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function showOrder($id)
    {
        $user = Auth::user();
        $order = Order::where('user_id', $user->id)
                     ->where('id', $id)
                     ->firstOrFail();
        
        return view('user.order-detail', compact('order'));
    }
}
