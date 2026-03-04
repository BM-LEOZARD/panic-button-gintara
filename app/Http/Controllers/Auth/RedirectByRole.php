<?php

namespace App\Http\Controllers\Auth;

// use App\Http\Controllers\Controller;
// use Illuminate\Http\Request;

trait RedirectByRole
{
    private function redirectByRole(string $role)
    {
        return match ($role) {
            'EndUser'    => redirect()->route('dashboard'),
            'Admin'      => redirect()->route('admin.dashboard'),
            'SuperAdmin' => redirect()->route('superadmin.dashboard'),
            default      => redirect()->route('login'),
        };
    }
}
