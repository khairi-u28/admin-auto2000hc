<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Curriculum;
use Illuminate\Http\Request;

class CurriculumController extends Controller
{
    /**
     * GET /api/v1/curricula
     * List published curricula.
     */
    public function index(Request $request)
    {
        $query = Curriculum::with(['jobRole', 'modules'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('department'), fn ($q) => $q->where('department', $request->department))
            ->when($request->filled('job_role_id'), fn ($q) => $q->where('job_role_id', $request->job_role_id))
            ->when($request->filled('academic_year'), fn ($q) => $q->where('academic_year', $request->academic_year));

        $perPage  = min((int) $request->input('per_page', 15), 100);
        $paginate = $query->paginate($perPage);

        return response()->json([
            'data' => $paginate->map(fn (Curriculum $c) => [
                'id'            => $c->id,
                'title'         => $c->title,
                'department'    => $c->department,
                'academic_year' => $c->academic_year,
                'status'        => $c->status,
                'job_role'      => $c->jobRole ? ['id' => $c->jobRole->id, 'name' => $c->jobRole->name] : null,
                'modules_count' => $c->modules->count(),
            ]),
            'meta' => [
                'total'        => $paginate->total(),
                'per_page'     => $paginate->perPage(),
                'current_page' => $paginate->currentPage(),
                'last_page'    => $paginate->lastPage(),
            ],
            'message' => 'success',
        ]);
    }

    /**
     * GET /api/v1/curricula/{id}
     * Get a single curriculum with modules.
     */
    public function show(string $id)
    {
        $curriculum = Curriculum::with(['jobRole', 'modules.courses'])->findOrFail($id);

        return response()->json([
            'data' => [
                'id'            => $curriculum->id,
                'title'         => $curriculum->title,
                'department'    => $curriculum->department,
                'academic_year' => $curriculum->academic_year,
                'status'        => $curriculum->status,
                'job_role'      => $curriculum->jobRole ? ['id' => $curriculum->jobRole->id, 'name' => $curriculum->jobRole->name] : null,
                'modules'       => $curriculum->modules->map(fn ($m) => [
                    'id'          => $m->id,
                    'title'       => $m->title,
                    'department'  => $m->department,
                    'status'      => $m->status,
                    'courses'     => $m->courses->map(fn ($c) => [
                        'id'               => $c->id,
                        'title'            => $c->title,
                        'type'             => $c->type,
                        'duration_minutes' => $c->duration_minutes,
                        'status'           => $c->status,
                    ])->toArray(),
                ])->toArray(),
            ],
            'meta'    => [],
            'message' => 'success',
        ]);
    }
}
