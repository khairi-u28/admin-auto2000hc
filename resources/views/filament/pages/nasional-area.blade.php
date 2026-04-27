@extends('filament::page')

@section('content')
@php($area = urldecode(request()->route('area') ?? request()->get('area')))
@php($region = urldecode(request()->get('region') ?? request()->route('region')))
<div class="space-y-4">
  <div class="flex items-center justify-between">
    <h2 class="text-lg font-semibold">Ringkasan Area {{ $area }}</h2>
    <a href="/admin/nasional/region/{{ urlencode($region) }}" class="text-sm text-gray-600">← Region {{ $region }}</a>
  </div>

  <div class="grid grid-cols-1 lg:grid-cols-4 gap-4">
    <div class="bg-white p-4 rounded shadow">
      <div class="text-sm text-gray-600">Jumlah Cabang</div>
      <div class="text-2xl font-bold">{{ \App\Models\Branch::where('area',$area)->count() }}</div>
    </div>
    <div class="bg-white p-4 rounded shadow">
      <div class="text-sm text-gray-600">Jumlah Karyawan</div>
      <div class="text-2xl font-bold">{{ \App\Models\Employee::where('area',$area)->count() }}</div>
    </div>
    <div class="bg-white p-4 rounded shadow">
      <div class="text-sm text-gray-600">Enrollment Aktif</div>
      <div class="text-2xl font-bold">-</div>
    </div>
    <div class="bg-white p-4 rounded shadow">
      <div class="text-sm text-gray-600">Enrollment Selesai</div>
      <div class="text-2xl font-bold">{{ \DB::table('enrollments')->join('employees','enrollments.employee_id','=','employees.id')->where('employees.area',$area)->where('enrollments.status','completed')->count() }}</div>
    </div>
  </div>

  <div class="bg-white p-4 rounded shadow">
    <div class="font-semibold">Daftar Cabang</div>
    <div class="mt-2">
      <table class="w-full text-sm">
        <thead><tr class="text-left text-xs text-gray-600"><th>Kode</th><th>Nama</th><th>Tipe</th><th>Jml Karyawan</th><th>% Selesai</th><th></th></tr></thead>
        <tbody>
          @foreach(\App\Models\Branch::where('area',$area)->get() as $b)
            @php
              $emp = \App\Models\Employee::where('branch_id',$b->id)->count();
              $completed = \DB::table('enrollments')->join('employees','enrollments.employee_id','=','employees.id')->where('employees.branch_id',$b->id)->where('enrollments.status','completed')->count();
              $pct = $emp ? round($completed / $emp * 100,1) : 0;
            @endphp
            <tr>
              <td class="py-2">{{ $b->code }}</td>
              <td>{{ $b->name }}</td>
              <td>{{ $b->type }}</td>
              <td>{{ $emp }}</td>
              <td>{{ $pct }}%</td>
              <td><a href="/admin/nasional/cabang/{{ $b->id }}" class="text-blue-600">Lihat Cabang →</a></td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>
  </div>
</div>
@endsection
