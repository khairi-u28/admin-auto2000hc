<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class BatchController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $employeeId = $request->user()?->employee?->id;

        $query = Batch::with(['competency', 'branch'])
            ->where('status', '!=', 'draft');

        if ($employeeId) {
            $query->whereHas('participants', function ($q) use ($employeeId) {
                $q->where('employee_id', $employeeId);
            });
        }

        $batches = $query->latest('start_date')->paginate(10);

        return response()->json($batches);
    }

    public function show(Batch $batch): JsonResponse
    {
        $batch->load([
            'competency', 
            'branch', 
            'materi.module', 
            'materi.course',
            'participants' => function ($q) {
                $q->select('id', 'batch_id', 'employee_id', 'status');
            }
        ]);

        return response()->json([
            'data' => $batch
        ]);
    }
}
