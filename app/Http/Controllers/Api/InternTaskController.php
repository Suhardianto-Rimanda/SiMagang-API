<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Submission;
use App\Models\SubmissionAttempt;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class InternTaskController extends Controller
{
    public function submitTask(Request $request, Task $task)
    {
        $user = Auth::user();
        if (!$user->intern) {
            return response()->json(['message' => 'Unauthorized. User is not an intern.'], 403);
        }

        $intern = $user->intern;

        $validator = Validator::make($request->all(), [
            'files' => 'sometimes|array', // 'sometimes' berarti tidak wajib ada
            'files.*' => 'file|max:10240', // Max 10MB per file
            'text_submission' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if (!$request->hasFile('files') && !$request->has('text_submission')) {
            return response()->json(['message' => 'Tidak ada file atau teks yang dikirim.'], 400);
        }

        // 1. Cek apakah tugas ini memang untuk intern tersebut
        if (!$intern->tasks->contains($task)) {
             return response()->json(['message' => 'Forbidden: Tugas ini tidak ditugaskan untuk Anda.'], 403);
        }

        // 2. Cari submission yang sudah ada
        $submission = Submission::where('task_id', $task->id)
                                ->where('intern_id', $intern->id)
                                ->first();

        // 3. Jika submission sudah ada (ini adalah proses edit/update)
        if ($submission) {
            // Cek apakah sudah melewati deadline
            if (Carbon::now()->gt($task->due_date)) {
                return response()->json(['message' => 'Gagal: Tenggat waktu telah berakhir. Anda tidak dapat mengubah pengumpulan.'], 403);
            }

            // Jika belum deadline, lanjutkan proses update
            return DB::transaction(function () use ($request, $submission) {
                // Hapus file lama jika ada file baru yang diunggah
                if ($request->hasFile('files')) {
                    foreach ($submission->attempts as $attempt) {
                        Storage::disk('public')->delete($attempt->file_path);
                    }
                    $submission->attempts()->delete(); // Hapus record dari database
                }

                // Simpan file baru
                if ($request->hasFile('files')) {
                    foreach ($request->file('files') as $file) {
                        $path = Storage::disk('public')->putFile('submissions', $file);
                        $submission->attempts()->create(['file_path' => $path]);
                    }
                }
                
                // Update status dan tanggal
                $submission->update([
                    'status' => 'resubmitted', 
                    'submission_date' => now()
                ]);

                return response()->json([
                    'message' => 'Pengumpulan tugas berhasil diperbarui!',
                    'submission' => $submission->load('attempts')
                ], 200); // Status 200 OK untuk update
            });
        }

        // 4. Jika submission belum ada (ini adalah proses create baru)
        return DB::transaction(function () use ($request, $task, $intern) {
            $newSubmission = Submission::create([
                'task_id' => $task->id,
                'intern_id' => $intern->id,
                'status' => 'submitted',
                'submission_date' => now(),
            ]);

            if ($request->hasFile('files')) {
                foreach ($request->file('files') as $file) {
                    $path = Storage::disk('public')->putFile('submissions', $file);
                    $newSubmission->attempts()->create(['file_path' => $path]);
                }
            }

            return response()->json([
                'message' => 'Tugas berhasil dikumpulkan!',
                'submission' => $newSubmission->load('attempts')
            ], 201); // Status 201 Created untuk resource baru
        });
    }
}
