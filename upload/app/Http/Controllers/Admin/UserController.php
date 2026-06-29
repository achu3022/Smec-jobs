<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::withTrashed();
        
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }
        
        if ($request->has('role') && $request->role !== 'all') {
            $query->where('role', $request->role);
        }
        
        $users = $query->orderBy('created_at', 'desc')->paginate(20);
        return response()->json($users);
    }
    
    public function toggleStatus(Request $request, $id)
    {
        $user = User::findOrFail($id);
        // Assuming we add an 'is_active' or similar column to User, or soft delete.
        // For now, let's toggle soft deletes if it's there, but User has SoftDeletes.
        // Or better yet, let's just add an 'is_active' boolean if they don't have it.
        // Let's check User model... it doesn't have is_active. 
        // We can just use soft deletes (suspend user).
        
        if ($user->trashed()) {
            $user->restore();
        } else {
            $user->delete();
        }
        
        return response()->json(['message' => 'User status updated successfully']);
    }
    
    public function destroy($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->forceDelete();
        return response()->json(['message' => 'User permanently deleted']);
    }
}
