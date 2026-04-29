<x-filament-panels::page>
@php
    $branchId = request()->route('branchId') ?? request()->get('branchId');
    $branch = \App\Models\Branch::find($branchId);
@endphp
<div class="space-y-4">
  <div class="flex items-center justify-between">
    <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Ringkasan Cabang {{ $branch?->name }}</h2>
    <a href="/admin/nasional/area/{{ urlencode($branch?->area ?? '') }}?region={{ urlencode($branch?->region ?? '') }}" class="text-sm text-gray-600 dark:text-gray-400">← Area {{ $branch?->area }}</a>
  </div>

  <div class="bg-white dark:bg-gray-800 p-4 rounded shadow border border-gray-100 dark:border-gray-700">
    <div class="flex items-center gap-4">
      <div class="w-16 h-16 rounded-full bg-[#1A3A5C] text-white flex items-center justify-center font-bold">{{ $branch?->code ?? '' }}</div>
      <div>
        <div class="text-lg font-semibold text-gray-900 dark:text-white">{{ $branch?->name }}</div>
        <div class="text-sm text-gray-600 dark:text-gray-400">{{ $branch?->region }} • {{ $branch?->area }} • {{ $branch?->type }}</div>
      </div>
    </div>

    <div class="mt-4 grid grid-cols-1 lg:grid-cols-5 gap-4">
      <div class="bg-white dark:bg-gray-800 p-4 rounded shadow border border-gray-100 dark:border-gray-700">
        <div class="text-sm text-gray-600 dark:text-gray-400">Jumlah Karyawan Aktif</div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Employee::where('branch_id',$branch?->id)->where('status','active')->count() }}</div>
      </div>
      <div class="bg-white dark:bg-gray-800 p-4 rounded shadow border border-gray-100 dark:border-gray-700">
        <div class="text-sm text-gray-600 dark:text-gray-400">training Aktif</div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">-</div>
      </div>
      <div class="bg-white dark:bg-gray-800 p-4 rounded shadow border border-gray-100 dark:border-gray-700">
        <div class="text-sm text-gray-600 dark:text-gray-400">training Selesai</div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ \DB::table('trainings')->join('employees','trainings.employee_id','=','employees.id')->where('employees.branch_id',$branch?->id)->where('trainings.status','lulus')->count() }}</div>
      </div>
      <div class="bg-white dark:bg-gray-800 p-4 rounded shadow border border-gray-100 dark:border-gray-700">
        <div class="text-sm text-gray-600 dark:text-gray-400">Rekam Pelatihan</div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\TrainingRecord::whereHas('employee', fn($q)=> $q->where('branch_id',$branch?->id))->count() }}</div>
      </div>
      <div class="bg-white dark:bg-gray-800 p-4 rounded shadow border border-gray-100 dark:border-gray-700">
        <div class="text-sm text-gray-600 dark:text-gray-400">Avg Competency Level</div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">-</div>
      </div>
    </div>

    <div class="mt-4">
      <a href="/admin/nasional/cabang/{{ $branch?->id }}?tab=employees" class="text-sm text-blue-600 dark:text-blue-400">Lihat Daftar Karyawan →</a>
    </div>
  </div>
</div>
</x-filament-panels::page>


