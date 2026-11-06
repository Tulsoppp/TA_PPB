<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;

class SimulationController extends Controller
{
    public function reset(Request $request)
    {
        Artisan::call('migrate:fresh', ['--seed' => true]);

        // Keep current user logged in if they existed
        $user = Auth::user();
        if ($user) {
            $refreshed = \App\Models\User::where('email', $user->email)->first();
            if ($refreshed) {
                Auth::login($refreshed);
            }
        }

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Seluruh data simulasi SIAKAD KRS telah berhasil direset ke kondisi awal.',
                'redirect' => route('dashboard')
            ]);
        }

        return redirect()->route('dashboard')->with('success', 'Data simulasi berhasil direset ke kondisi awal.');
    }
}

