<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityReport;
use App\Models\Intern;
use App\Models\LearningProgress;
use App\Models\Submission;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function getActivityReports()
    {
        $reports = ActivityReport::with('intern.user')->get();
        return response()->json(['data' => $reports], 200);
    }

    public function getLearningProgress()
    {
        $progress = LearningProgress::with(['intern.user', 'module.supervisor.user'])->get();
        return response()->json(['data' => $progress], 200);
    }

    public function getSubmissions()
    {
        $submissions = Submission::with(['intern.user', 'task.supervisor.user'])->get();
        return response()->json(['data' => $submissions], 200);
    }

    public function getInternReports(Intern $intern)
    {
        $activityReports = $intern->activityReports;
        $learningProgress = $intern->learningProgress;
        $submissions = $intern->submissions()->with(['task.supervisor.user'])->get();

        return response()->json([
            'intern_info' => $intern->load('user'),
            'activity_reports' => $activityReports,
            'learning_progress' => $learningProgress,
            'submissions' => $submissions
        ], 200);
    }

    public function getAllInternReports()
    {
        $interns = Intern::with([
            'user',
            'activityReports',
            'learningProgress',
            'submissions.task.supervisor.user'
        ])->get();

        return response()->json(['data' => $interns], 200);
    }

    public function getReportSummaryForIntern(Request $request, Intern $intern)
    {
        $request->validate([
            'start_date' => 'required|date_format:Y-m-d',
            'end_date' => 'required|date_format:Y-m-d|after_or_equal:start_date',
        ]);

        $startDate = $request->query('start_date');
        $endDate = $request->query('end_date');

        // Ambil laporan aktivitas
        $activityReports = $intern->activityReports()
            ->whereBetween('report_date', [$startDate, $endDate])
            ->orderBy('report_date', 'asc')
            ->get();

        // Ambil progres pembelajaran
        $learningProgress = $intern->learningProgress()
            ->with('module')
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
