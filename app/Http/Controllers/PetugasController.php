<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Petugas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PetugasController extends Controller
{
    public function index(Request $request)
    {
        $petugas = Petugas::all();
        
        // Jika request expects JSON (API), return JSON
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $petugas,
                'count' => $petugas->count(),
            ]);
        }
        
        return view('petugas.index', compact('petugas'));
    }

    public function create()
    {
        return view('petugas.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:petugas',
            'password' => 'required|string|min:6',
        ]);

        $petugas = Petugas::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Jika request expects JSON (API), return JSON
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Petugas berhasil ditambahkan',
                'data' => $petugas,
            ], 201);
        }

        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil ditambahkan');
    }

    public function edit(Petugas $petuga) // gunakan singular karena route model binding
    {
        return view('petugas.edit', ['petugas' => $petuga]);
    }

    public function update(Request $request, Petugas $petuga)
    {
        $request->validate([
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:petugas,email,' . $petuga->id,
            'password' => 'nullable|string|min:6',
        ]);

        $data = [
            'username' => $request->username,
            'email' => $request->email,
        ];

        if ($request->password) {
            $data['password'] = Hash::make($request->password);
        }

        $petuga->update($data);

        // Jika request expects JSON (API), return JSON
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Petugas berhasil diupdate',
                'data' => $petuga->fresh(),
            ]);
        }

        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil diupdate');
    }

    public function show(Request $request, Petugas $petugas)
    {
        // Jika request expects JSON (API), return JSON
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'data' => $petugas,
            ]);
        }
        
        // Web request - return view (if needed)
        return view('petugas.show', compact('petugas'));
    }

    public function destroy(Request $request, Petugas $petuga)
    {
        $petuga->delete();
        
        // Jika request expects JSON (API), return JSON
        if ($request->expectsJson() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Petugas berhasil dihapus',
            ]);
        }
        
        return redirect()->route('petugas.index')->with('success', 'Petugas berhasil dihapus');
    }
}
