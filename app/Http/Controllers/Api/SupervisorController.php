<?php

namespace App\Http\Controllers\Api;

use App\Models\Intern;
use Illuminate\Http\Request;
use App\Models\ActivityReport;
use App\Models\LearningProgress;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class SupervisorController extends Controller
{


    // Iterns & submissions
    public function interns()
    {
        $user = Auth::user();
        if (!$user->supervisor) {
            return response()->json(['message' => 'Unauthorized. User is not a supervisor.'], 403);
        }

        $supervisor = $user->supervisor;
        return response()->json(['data' => $supervisor->interns], 200);
    }

    public function getReportSummary(Request $request, Intern $intern)
    {
        // Validasi input tanggal
        $request->validate([
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
        ]);
    
        // Otorisasi: pastikan intern ini milik supervisor yang sedang login
        $supervisor = Auth::user()->supervisor;
        if ($intern->supervisor_id !== $supervisor->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
    
        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');
    
        // Ambil laporan aktivitas dalam rentang tanggal
        $activityReports = ActivityReport::where('intern_id', $intern->id)
            ->whereBetween('report_date', [$startDate, $endDate])
            ->orderBy('report_date', 'asc')
            ->get();
    
        // Ambil progres pembelajaran dalam rentang tanggal
        $learningProgress = LearningProgress::with('module')
            ->where('intern_id', $intern->id)
            ->whereBetween('created_at', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
            ->orderBy('created_at', 'asc')
            ->get();
    
        return response()->json([
            'data' => [
                'activity_reports' => $activityReports,
                'learning_progress' => $learningProgress,
            ]
        ]);
    }
}
