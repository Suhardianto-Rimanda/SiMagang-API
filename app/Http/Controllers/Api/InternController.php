<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Models\LearningProgress; 
use Illuminate\Http\Request;
use App\Models\Submission;

class InternController extends Controller
{

    public function getTasks()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['message' => 'Unauthorized. User not authenticated.'], 401);
        }
        if (!$user->intern) {
            return response()->json(['message' => 'Unauthorized. User is not a valid intern.'], 403);
        }

        $tasks = $user->intern->tasks()->with([
            'supervisor', 
            'submissions' => function ($query) use ($user) {
                $query->where('intern_id', $user->intern->id)->with('attempts');
            }
        ])->get();

        return response()->json(['data' => $tasks], 200);
    }


    public function getLearningModules()
    {
        $user = Auth::user();
        if (!$user || !$user->intern) {
            return response()->json(['message' => 'Unauthorized.'], 403);
        }

        $learningModules = $user->intern->learningModules()->with([
            'supervisor', 
            'learningProgress' => function ($query) use ($user) {
                $query->where('intern_id', $user->intern->id);
            }
        ])->get();

        return response()->json(['data' => $learningModules], 200);
    }
}
