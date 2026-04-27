<x-filament-panels::page>
  @php
    $stats = $this->getNationalStats();
    $statusDistribution = $this->getEnrollmentStatusDistribution();
    $monthlyTrend = $this->getMonthlyCompletionTrend();
    $regionCompletion = $this->getRegionCompletionData();
    $maxMonthly = max(array_column($monthlyTrend, 'value')) ?: 1;
  @endphp
  <div class="space-y-4">
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4">
      <div class="bg-white dark:bg-gray-800 p-4 rounded shadow border border-gray-100 dark:border-gray-700">
        <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Total Karyawan Aktif Nasional</div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_karyawan_aktif'] }}</div>
      </div>
      <div class="bg-white dark:bg-gray-800 p-4 rounded shadow border border-gray-100 dark:border-gray-700">
        <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Total Cabang</div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_cabang'] }}</div>
      </div>
      <div class="bg-white dark:bg-gray-800 p-4 rounded shadow border border-gray-100 dark:border-gray-700">
        <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Total Enrollment</div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_enrollment'] }}</div>
      </div>
      <div class="bg-white dark:bg-gray-800 p-4 rounded shadow border border-gray-100 dark:border-gray-700">
        <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Completion Rate</div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['completion_rate'] }}%</div>
      </div>
      <div class="bg-white dark:bg-gray-800 p-4 rounded shadow border border-gray-100 dark:border-gray-700">
        <div class="text-sm text-gray-600 dark:text-gray-400 font-medium">Total Training Records</div>
        <div class="text-2xl font-bold text-gray-900 dark:text-white">{{ $stats['total_training_records'] }}</div>
      </div>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-4">
      <div class="bg-white dark:bg-gray-800 p-6 rounded shadow border border-gray-100 dark:border-gray-700 xl:col-span-2">
        <div class="font-bold text-lg mb-4 text-gray-900 dark:text-white">Completion Rate by Region</div>
        <div class="space-y-4">
          @foreach($regionCompletion as $regionRow)
            <div>
              <div class="flex items-center justify-between text-sm mb-1">
                <span class="font-medium text-gray-700 dark:text-gray-200">{{ $regionRow['region'] }}</span>
                <span class="text-gray-500 dark:text-gray-400">{{ $regionRow['rate'] }}%</span>
              </div>
              <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-3 overflow-hidden">
                <div class="bg-[#1A3A5C] h-3 rounded-full" style="width: {{ $regionRow['rate'] }}%;"></div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <div class="bg-white dark:bg-gray-800 p-6 rounded shadow border border-gray-100 dark:border-gray-700">
        <div class="font-bold text-lg mb-4 text-gray-900 dark:text-white">Enrollment Status Distribution</div>
        <div class="space-y-3">
          @foreach(['not_started' => 'Not Started', 'in_progress' => 'In Progress', 'completed' => 'Completed', 'overdue' => 'Overdue'] as $statusKey => $statusLabel)
            <div class="flex items-center justify-between text-sm">
              <span class="text-gray-600 dark:text-gray-300">{{ $statusLabel }}</span>
              <span class="font-semibold text-gray-900 dark:text-white">{{ $statusDistribution[$statusKey] ?? 0 }}</span>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <div class="bg-white dark:bg-gray-800 p-6 rounded shadow border border-gray-100 dark:border-gray-700">
      <div class="font-bold text-lg mb-4 text-gray-900 dark:text-white">Monthly Completion Trend</div>
      <div class="grid grid-cols-2 md:grid-cols-3 xl:grid-cols-6 gap-4">
        @foreach($monthlyTrend as $point)
          <div class="rounded-lg border border-gray-100 dark:border-gray-700 p-4">
            <div class="text-xs uppercase tracking-wide text-gray-500 dark:text-gray-400 mb-2">{{ $point['label'] }}</div>
            <div class="text-2xl font-bold text-gray-900 dark:text-white mb-3">{{ $point['value'] }}</div>
            <div class="w-full bg-gray-100 dark:bg-gray-700 rounded-full h-2 overflow-hidden">
              <div class="bg-[#1B7A4E] h-2 rounded-full" style="width: {{ round(($point['value'] / $maxMonthly) * 100, 1) }}%;"></div>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    {{-- The data table defined in NasionalPage.php --}}
    <div class="mt-4">
      {{ $this->table }}
    </div>
  </div>
</x-filament-panels::page>
