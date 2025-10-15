<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreInternLearningRequest;
use App\Http\Requests\UpdateInternLearningRequest;
use App\Models\LearningProgress;
use Illuminate\Support\Facades\Auth;

class InternLearningProgressController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!$user->intern) {
            return response()->json(['message' => 'Unauthorized. User is not an intern.'], 403);
        }

        $progress = $user->intern->learningProgress;

        return response()->json(['data' => $progress], 200);
    }

    public function store(StoreInternLearningRequest $request)
    {
        // 1. Ambil profil intern dari pengguna yang sedang login
        $intern = Auth::user()->intern;
    
        // 2. Tambahkan pemeriksaan keamanan untuk memastikan profil intern ada
        if (!$intern) {
            return response()->json(['message' => 'User does not have an intern profile.'], 400);
        }
    
        // 3. Ambil data yang sudah divalidasi secara otomatis oleh StoreInternLearningRequest
        $validatedData = $request->validated();
    
        // 4. Buat data progress baru menggunakan relasi dari model Intern
        $progress = $intern->learningProgress()->create([
            'module_id' => $validatedData['module_id'],
            'title' => $validatedData['title'],
            'description' => $validatedData['description'],
            'progress_status' => $validatedData['progress_status'],
        ]);
    
        // 5. Kembalikan respons sukses
        return response()->json([
            'message' => 'Learning progress created successfully.',
            'data' => $progress
        ], 201);
    }
    
    public function update(UpdateInternLearningRequest $request, LearningProgress $progress)
    {
        if (Auth::user()->intern->id !== $progress->intern_id) {
            return response()->json(['message' => 'Forbidden: You do not own this progress record.'], 403);
        }

        $validator = $request->validated();

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $progress->update($request->all());

        return response()->json(['message' => 'Learning progress updated successfully.', 'data' => $progress], 200);
    }


    public function destroy(LearningProgress $progress)
    {
        if (Auth::user()->intern->id !== $progress->intern_id) {
            return response()->json(['message' => 'Forbidden: You do not own this progress record.'], 403);
        }

        $progress->delete();

        return response()->json(['message' => 'Learning progress deleted successfully.'], 200);
    }
}
