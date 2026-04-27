@extends('filament::page')

@section('content')
@php($branchId = request()->route('branchId') ?? request()->get('branchId'))
@php($branch = \App\Models\Branch::find($branchId))
<div class="space-y-4">
  <div class="flex items-center justify-between">
    <h2 class="text-lg font-semibold">Ringkasan Cabang {{ $branch?->name }}</h2>
    <a href="/admin/nasional/area/{{ urlencode($branch?->area ?? '') }}?region={{ urlencode($branch?->region ?? '') }}" class="text-sm text-gray-600">← Area {{ $branch?->area }}</a>
  </div>

  <div class="bg-white p-4 rounded shadow">
    <div class="flex items-center gap-4">
      <div class="w-16 h-16 rounded-full bg-[#1A3A5C] text-white flex items-center justify-center font-bold">{{ $branch?->code ?? '' }}</div>
      <div>
        <div class="text-lg font-semibold">{{ $branch?->name }}</div>
        <div class="text-sm text-gray-600">{{ $branch?->region }} • {{ $branch?->area }} • {{ $branch?->type }}</div>
      </div>
    </div>

    <div class="mt-4 grid grid-cols-1 lg:grid-cols-5 gap-4">
      <div class="bg-white p-4 rounded shadow">
        <div class="text-sm text-gray-600">Jumlah Karyawan Aktif</div>
        <div class="text-2xl font-bold">{{ \App\Models\Employee::where('branch_id',$branch?->id)->where('status','active')->count() }}</div>
      </div>
      <div class="bg-white p-4 rounded shadow">
        <div class="text-sm text-gray-600">Enrollment Aktif</div>
        <div class="text-2xl font-bold">-</div>
      </div>
      <div class="bg-white p-4 rounded shadow">
        <div class="text-sm text-gray-600">Enrollment Selesai</div>
        <div class="text-2xl font-bold">{{ \DB::table('enrollments')->join('employees','enrollments.employee_id','=','employees.id')->where('employees.branch_id',$branch?->id)->where('enrollments.status','completed')->count() }}</div>
      </div>
      <div class="bg-white p-4 rounded shadow">
        <div class="text-sm text-gray-600">Rekam Pelatihan</div>
        <div class="text-2xl font-bold">{{ \App\Models\TrainingRecord::whereHas('employee', fn($q)=> $q->where('branch_id',$branch?->id))->count() }}</div>
      </div>
      <div class="bg-white p-4 rounded shadow">
        <div class="text-sm text-gray-600">Avg Competency Level</div>
        <div class="text-2xl font-bold">-</div>
      </div>
    </div>

    <div class="mt-4">
      <a href="/admin/nasional/cabang/{{ $branch?->id }}?tab=employees" class="text-sm text-blue-600">Lihat Daftar Karyawan →</a>
    </div>
  </div>
</div>
@endsection
