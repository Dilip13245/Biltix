<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show()
    {
        $admin = session('admin_user');
        return view('admin.profile.show', compact('admin'));
    }

    public function edit()
    {
        $admin = session('admin_user');
        return view('admin.profile.edit', compact('admin'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'nullable|string|max:20',
            'current_password' => 'nullable|string|min:6',
            'new_password' => 'nullable|string|min:6|confirmed',
        ]);

        $adminSession = session('admin_user');
        $admin = Admin::find($adminSession->id);
        
        // Update basic info
        $admin->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
        ]);
        
        // Update password if provided
        if ($request->current_password && $request->new_password) {
            if (Hash::check($request->current_password, $admin->password)) {
                $admin->update(['password' => Hash::make($request->new_password)]);
            } else {
                return back()->withErrors(['current_password' => 'Current password is incorrect']);
            }
        }
        
        // Update session
        session(['admin_user' => $admin->fresh()]);
        
        return redirect()->route('admin.profile.show')->with('success', 'Profile updated successfully');
    }
}