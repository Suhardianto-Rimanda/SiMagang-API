<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\ActivityReport;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\StoreInternActivityRequest;
use App\Http\Requests\UpdateInternActivityRequest;

class InternActivityReportController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        if (!$user->intern) {
            return response()->json(['message' => 'Unauthorized. User is not an intern.'], 403);
        }

        $reports = $user->intern->activityReports;

        return response()->json(['data' => $reports], 200);
    }


    public function store(StoreInternActivityRequest $request)
    {
        // 1. Ambil profil intern dari pengguna yang sedang login
        $intern = Auth::user()->intern;
    
        // 2. Pastikan profil intern ada
        if (!$intern) {
            return response()->json(['message' => 'User does not have an intern profile.'], 400);
        }
    
        // 3. Ambil data yang sudah divalidasi oleh StoreInternActivityRequest
        $validatedData = $request->validated();
    
        // 4. Buat laporan baru menggunakan relasi dari model Intern
        $report = $intern->activityReports()->create($validatedData);
    
        // 5. Kembalikan respons sukses
        return response()->json([
            'message' => 'Activity report created successfully.',
            'data' => $report
        ], 201);
    }


    public function update(UpdateInternActivityRequest $request, ActivityReport $report)
    {
        if (Auth::user()->intern->id !== $report->intern_id) {
            return response()->json(['message' => 'Forbidden: You do not own this report.'], 403);
        }

        $validator = $request->validated();

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $report->update($request->all());

        return response()->json(['message' => 'Activity report updated successfully.', 'data' => $report], 200);
    }


    public function destroy(ActivityReport $report)
    {
        if (Auth::user()->intern->id !== $report->intern_id) {
            return response()->json(['message' => 'Forbidden: You do not own this report.'], 403);
        }

        $report->delete();

        return response()->json(['message' => 'Activity report deleted successfully.'], 200);
    }
}
