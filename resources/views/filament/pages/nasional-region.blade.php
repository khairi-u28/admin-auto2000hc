<x-filament-panels::page>
@php($region = urldecode(request()->route('region') ?? request()->get('region')))
<div class="space-y-4">
  <div class="flex items-center justify-between">
    <h2 class="text-xl font-bold text-gray-900 dark:text-white">Statistik Region: {{ $region }}</h2>
    <x-filament::button
        href="/admin/nasional"
        tag="a"
        color="gray"
        icon="heroicon-m-arrow-left"
        size="sm"
    >
        Kembali ke Nasional
    </x-filament::button>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
    <div class="bg-white dark:bg-gray-800 p-4 rounded shadow border border-gray-100 dark:border-gray-700">
      <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Jumlah Cabang</div>
      <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Branch::where('region',$region)->count() }}</div>
    </div>
    <div class="bg-white dark:bg-gray-800 p-4 rounded shadow border border-gray-100 dark:border-gray-700">
      <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Karyawan Aktif</div>
      <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ \App\Models\Employee::where('region',$region)->where('status','active')->count() }}</div>
    </div>
    <div class="bg-white dark:bg-gray-800 p-4 rounded shadow border border-gray-100 dark:border-gray-700">
      <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Enrollment Selesai</div>
      <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ \DB::table('enrollments')->join('employees','enrollments.employee_id','=','employees.id')->where('employees.region',$region)->where('enrollments.status','completed')->count() }}</div>
    </div>
    <div class="bg-white dark:bg-gray-800 p-4 rounded shadow border border-gray-100 dark:border-gray-700">
      <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Rata-rata Behavioral</div>
      <div class="text-2xl font-bold text-gray-900 dark:text-white">
        {{ match($region) { 'DKI,JABAR,PRIME FLEET' => 4.25, 'JATKALBAL' => 4.29, 'SUMATERA' => 4.30, default => '-' } }}
      </div>
    </div>
  </div>

  <div class="mt-4">
    {{ $this->table }}
  </div>
</div>
</x-filament-panels::page>
