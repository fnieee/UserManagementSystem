<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query();

        // Search functionality
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orWhere('phone_number', 'like', "%$search%");
            });
        }

        // Status filter - only apply if status is provided and valid
        if ($request->filled('status') && in_array($request->status, ['active', 'inactive'])) {
            $query->where('status', $request->status);
        }

        $users = $query->latest()->paginate(10);

        // Check if results are empty due to search/filter
        if ($request->hasAny(['search', 'status']) && $users->isEmpty()) {
            $request->session()->flash('warning', 'No users found matching your criteria.');
        }

        return view('dashboard', compact('users'));
    }
}
