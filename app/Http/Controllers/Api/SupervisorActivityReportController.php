<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityReport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SupervisorActivityReportController extends Controller
{
    /**
     * Menampilkan semua laporan aktivitas dari intern
     * yang berada di bawah bimbingan supervisor yang sedang login.
     */
    public function index()
    {
        $user = Auth::user();

        // Pastikan user adalah seorang supervisor
        if (!$user->supervisor) {
            return response()->json(['message' => 'Unauthorized. User is not a supervisor.'], 403);
        }

        // Ambil semua ID intern yang dibimbing oleh supervisor ini
        $internIds = $user->supervisor->interns()->pluck('id');

        // Ambil semua laporan aktivitas dari para intern tersebut
        $activityReports = ActivityReport::with(['intern.user']) // Eager load relasi intern dan user-nya
            ->whereIn('intern_id', $internIds)
            ->orderBy('report_date', 'desc')
            ->get();

        return response()->json(['data' => $activityReports], 200);
    }
}