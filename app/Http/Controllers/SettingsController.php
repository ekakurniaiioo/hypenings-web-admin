<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class SettingsController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        return view('settings.index', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required','email','max:255', Rule::unique('users')->ignore($user->id)],
        ]);

        $user->update($request->only('name', 'email'));

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updateSecurity(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password is incorrect.']);
        }

        $user->update(['password' => Hash::make($request->password)]);

        return back()->with('success', 'Password updated successfully.');
    }

    public function updateAppearance(Request $request)
    {
        $request->validate([
            'logo' => 'nullable|image|max:2048',
            'theme_color' => 'nullable|string',
        ]);

        // Simpan file logo kalau ada
        if ($request->hasFile('logo')) {
            $path = $request->file('logo')->store('public/logo');
            // Simpan path ke setting (misal di db atau config file)
            // Contoh: Setting::updateOrCreate(['key' => 'logo'], ['value' => $path]);
        }

        // Simpan theme color juga (contoh disini pakai session saja)
        session(['theme_color' => $request->theme_color]);

        return back()->with('success', 'Appearance updated successfully.');
    }
}
