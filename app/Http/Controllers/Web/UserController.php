<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Artisan;
use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query();

        if ($request->has('status')) {
            $users->where('status', $request->status);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $users->where(function($q) use ($search) {
                $q->where('name', 'like', "%$search%")
                  ->orWhere('email', 'like', "%$search%")
                  ->orWhere('phone_number', 'like', "%$search%");
            });
        }

        $users = $users->paginate(10);

        return view('dashboard', compact('users'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function store(StoreUserRequest $request)
    {
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => bcrypt($request->password),
            'status' => $request->status ?? 'active',
        ]);

        return redirect()->route('dashboard')->with('success', 'User created successfully.');
    }

    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        DB::beginTransaction();

        try {
            $data = $request->validated();

            $user->update($data);
            DB::commit();

            Cache::flush();
            Artisan::call('cache:clear');

            return redirect()->route('dashboard')
                ->with('success', 'User updated successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to update user');
        }
    }

    public function destroy(User $user)
    {
        DB::beginTransaction();

        try {
            $user->delete();
            DB::commit();

            return redirect()->route('dashboard')
                   ->with('success', 'User deleted successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete user');
        }
    }

    public function bulkDelete(Request $request)
    {
        $ids = json_decode($request->input('ids'), true);

        if (!is_array($ids) || empty($ids)) {
            return back()->withErrors(['No valid user IDs provided for deletion.']);
        }

        DB::beginTransaction();

        try {
            $deleted = User::whereIn('id', $ids)->delete();

            DB::commit();

            return redirect()
                ->route('dashboard')
                ->with('status', "$deleted users deleted successfully.");

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Failed to delete users: ' . $e->getMessage());
        }
    }

    public function export()
    {
        return Excel::download(new UsersExport, 'users.xlsx');
    }
}
