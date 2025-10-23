<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $role)
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        if ($user->role === $role) {
            return $next($request);
        }

        // Redirect to appropriate dashboard based on user's role
        switch ($user->role) {
            case 'admin':
                return redirect('/admin/dashboard');
            case 'petugas':
                return redirect('/petugas/dashboard');
            case 'pengguna':
                return redirect('/pengguna/dashboard');
            default:
                return redirect('/')->with('error', 'Unauthorized access.');
        }
    }
}