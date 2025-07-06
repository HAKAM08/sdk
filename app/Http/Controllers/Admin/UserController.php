<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    /**
     * Display a listing of the users.
     */
    public function index(Request $request)
    {
        $query = User::query();
        
        // Search by name or email
        if ($request->has('search') && !empty($request->search)) {
            $searchTerm = $request->search;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', '%' . $searchTerm . '%')
                  ->orWhere('email', 'like', '%' . $searchTerm . '%');
            });
        }
        
        // Sort by column
        $sortColumn = $request->sort ?? 'created_at';
        $sortDirection = $request->direction ?? 'desc';
        
        // Validate sort column to prevent SQL injection
        $allowedColumns = ['id', 'name', 'email', 'created_at'];
        if (!in_array($sortColumn, $allowedColumns)) {
            $sortColumn = 'created_at';
        }
        
        $query->orderBy($sortColumn, $sortDirection === 'asc' ? 'asc' : 'desc');
        
        $users = $query->paginate(10)->withQueryString();
        
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('admin.users.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'is_admin' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
            'is_admin' => $request->is_admin ?? false,
        ]);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:8|confirmed',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'is_admin' => 'boolean',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $userData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_admin' => $request->is_admin ?? false,
        ];

        // Only update password if provided
        if ($request->filled('password')) {
            $userData['password'] = Hash::make($request->password);
        }

        $user->update($userData);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    /**
     * Display the specified user.
     */
    public function show(User $user)
    {
        // Get user's orders with pagination
        $orders = $user->orders()->latest()->paginate(10);
        
        // Calculate total spent
        $totalSpent = $user->orders()->where('payment_status', 'paid')->sum('total');
        
        // Get order count
        $orderCount = $user->orders()->count();
        
        // Get recent activity (orders, logins, profile updates)
        // This is a placeholder - you would need to implement activity logging
        $recentActivity = collect();
        
        // Add recent orders to activity
        $user->orders()->latest()->take(5)->get()->each(function($order) use (&$recentActivity) {
            $recentActivity->push((object)[
                'id' => $order->id,
                'type' => 'order',
                'created_at' => $order->created_at
            ]);
        });
        
        // Sort activity by date
        $recentActivity = $recentActivity->sortByDesc('created_at');
        
        return view('admin.users.show', compact('user', 'orders', 'totalSpent', 'orderCount', 'recentActivity'));
    }
    
    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting yourself
        if ($user->id === auth()->id()) {
            return redirect()->route('admin.users.index')
                ->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }
}