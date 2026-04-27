<div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
  @foreach($this->regions as $region)
    <div class="bg-white rounded shadow p-4">
      <div class="flex items-center justify-between border-l-4" style="border-left-color:#1A3A5C; background:#E8EEF4; padding:0.5rem; align-items:center;">
        <div>
          <div class="text-sm font-semibold">{{ $region['name'] }}</div>
          <div class="text-xs text-gray-600">Karyawan Aktif: {{ number_format($region['active_employees']) }}</div>
        </div>
        <div class="text-right">
          <div class="text-sm">Behavioral</div>
          <div class="text-lg font-bold">{{ number_format($region['behavior'], 2) }}</div>
          <div class="text-xs text-gray-600">AUBO B+O: {{ $region['aubo'] }}</div>
        </div>
      </div>

      <div class="mt-3">
        <div class="text-sm font-medium mb-2">Status Enrollment</div>
        <div class="w-full bg-gray-100 h-3 rounded overflow-hidden flex">
          <div class="bg-gray-400" @style(["width: {$region['percentages']['not_started']}%"])></div>
          <div class="bg-blue-500" @style(["width: {$region['percentages']['in_progress']}%"])></div>
          <div class="bg-green-500" @style(["width: {$region['percentages']['completed']}%"])></div>
          <div class="bg-red-500" @style(["width: {$region['percentages']['overdue']}%"])></div>
        </div>
        <div class="flex justify-between text-xs mt-2 text-gray-600">
          <div>Not started: {{ $region['breakdown']['not_started'] }}</div>
          <div>In progress: {{ $region['breakdown']['in_progress'] }}</div>
          <div>Selesai: {{ $region['breakdown']['completed'] }}</div>
          <div>Overdue: {{ $region['breakdown']['overdue'] }}</div>
        </div>

        <div class="mt-3 text-right">
          <a href="{{ \App\Filament\Pages\NasionalRegionPage::getUrl(['region' => $region['name']]) }}" class="text-sm text-blue-600">Lihat Detail →</a>
        </div>
      </div>
    </div>
  @endforeach
</div>
