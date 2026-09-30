<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastSeen
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            $user = Auth::user();
            
            // Hanya update jika last_seen kosong atau sudah lebih dari 1 menit yang lalu
            // Ini untuk mencegah spam query ke database setiap kali user pindah halaman
            if (!$user->last_seen || $user->last_seen->diffInMinutes(now()) >= 1) {
                $user->update([
                    'last_seen' => now()
                ]);
            }
        }

        return $next($request);
    }
}