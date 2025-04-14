<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class MeController extends Controller
{
    public function updatePreferences(Request $request)
    {
        $user = auth()->user();
        
        $validated = $request->validate([
            'default_pagination' => 'sometimes|integer|in:5,10,15',
            // ... outras validações existentes
        ]);

        $user->update($validated);

        return response()->json($user);
    }
} 