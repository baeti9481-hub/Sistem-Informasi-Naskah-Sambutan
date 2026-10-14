<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAjudanReadOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Jika user adalah Ajudan ('2') dan mencoba melakukan simpan/edit/hapus (bukan method GET/HEAD)
        if ($user && $user->role === '2' && !$request->isMethodSafe()) {
            return redirect()->back()->with('error', 'Akses ditolak! Ajudan hanya memiliki hak akses untuk melihat data.');
        }

        return $next($request);
    }
}
