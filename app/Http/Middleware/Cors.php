<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class Cors
{
    public function handle(Request $request, Closure $next): Response
    {
        $origin = env('FRONTEND_URL', 'http://localhost:1421');

        // --- TAMBAH INI UNTUK MENANGANI OPTIONS REQUEST ---
        $headers = [
            'Access-Control-Allow-Origin'      => $origin,
            'Access-Control-Allow-Methods'     => 'GET, POST, PUT, DELETE, OPTIONS',
            'Access-Control-Allow-Headers'     => 'Content-Type, X-Auth-Token, Origin, Authorization, Accept, X-Requested-With',
            'Access-Control-Allow-Credentials' => 'true',
            'Access-Control-Max-Age'           => '86400', // Cache preflight response for 24 hours
        ];
        
        // JIKA INI ADALAH PREFLIGHT REQUEST (OPTIONS)
        if ($request->isMethod('OPTIONS')) {
            return response()->json('OK', 200, $headers); // Langsung kirim respons 200 dengan header
        }

        // Lanjutkan request POST/GET/PUT
        $response = $next($request);

        // Tambahkan header CORS ke response yang sebenarnya
        foreach ($headers as $key => $value) {
            $response->headers->set($key, $value);
        }

        return $response;
    }
}