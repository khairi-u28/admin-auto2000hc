<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    /**
     * GET /api/v1/courses
     * List published courses.
     */
    public function index(Request $request)
    {
        $query = Course::query()
            ->when($request->filled('type'), fn ($q) => $q->where('type', $request->type))
            ->when($request->filled('department'), fn ($q) => $q->where('department', $request->department))
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('search'), fn ($q) => $q->where('title', 'like', "%{$request->search}%"));

        $perPage  = min((int) $request->input('per_page', 15), 100);
        $paginate = $query->paginate($perPage);

        return response()->json([
            'data' => $paginate->map(fn (Course $c) => [
                'id'               => $c->id,
                'title'            => $c->title,
                'department'       => $c->department,
                'type'             => $c->type,
                'duration_minutes' => $c->duration_minutes,
                'status'           => $c->status,
                'description'      => $c->description,
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
     * GET /api/v1/courses/{id}
     * Get a single course with content URL.
     */
    public function show(string $id)
    {
        $course = Course::findOrFail($id);

        return response()->json([
            'data' => [
                'id'               => $course->id,
                'title'            => $course->title,
                'department'       => $course->department,
                'type'             => $course->type,
                'duration_minutes' => $course->duration_minutes,
                'status'           => $course->status,
                'description'      => $course->description,
                'external_url'     => $course->external_url,
                'file_url'         => $course->file_path
                    ? asset('storage/' . $course->file_path)
                    : null,
            ],
            'meta'    => [],
            'message' => 'success',
        ]);
    }
}
