<x-filament-panels::page>
  @php
    $stats = $this->getNationalStats();
    $statusDistribution = $this->getTrainingStatusDistribution();
    $monthlyTrend = $this->getMonthlyCompletionTrend();
    $regionCompletion = $this->getRegionCompletionData();
    $competencyCompletion = $this->getCompetencyCompletionData();
    $havDistribution = $this->getHAVScoreDistribution();
    $maxMonthly = max(array_column($monthlyTrend, 'value')) ?: 1;
  @endphp
  <div style="display:flex;flex-direction:column;gap:1rem;">
    <div style="display:grid;grid-template-columns:repeat(1,minmax(0,1fr));gap:1rem;">
      <div style="background:#ffffff;padding:1rem;border-radius:1rem;box-shadow:0 1px 2px rgba(0,0,0,0.08);border:1px solid #e5e7eb;">
        <div style="font-size:0.875rem;color:#475569;font-weight:500;">Total Karyawan Aktif Nasional</div>
        <div style="font-size:2rem;font-weight:700;color:#0f172a;">{{ $stats['total_karyawan_aktif'] }}</div>
      </div>
      <div style="background:#ffffff;padding:1rem;border-radius:1rem;box-shadow:0 1px 2px rgba(0,0,0,0.08);border:1px solid #e5e7eb;">
        <div style="font-size:0.875rem;color:#475569;font-weight:500;">Total Cabang</div>
        <div style="font-size:2rem;font-weight:700;color:#0f172a;">{{ $stats['total_cabang'] }}</div>
      </div>
      <div style="background:#ffffff;padding:1rem;border-radius:1rem;box-shadow:0 1px 2px rgba(0,0,0,0.08);border:1px solid #e5e7eb;">
        <div style="font-size:0.875rem;color:#475569;font-weight:500;">Total training</div>
        <div style="font-size:2rem;font-weight:700;color:#0f172a;">{{ $stats['total_training'] }}</div>
      </div>
      <div style="background:#ffffff;padding:1rem;border-radius:1rem;box-shadow:0 1px 2px rgba(0,0,0,0.08);border:1px solid #e5e7eb;">
        <div style="font-size:0.875rem;color:#475569;font-weight:500;">Completion Rate</div>
        <div style="font-size:2rem;font-weight:700;color:#0f172a;">{{ $stats['completion_rate'] }}%</div>
      </div>
      <div style="background:#ffffff;padding:1rem;border-radius:1rem;box-shadow:0 1px 2px rgba(0,0,0,0.08);border:1px solid #e5e7eb;">
        <div style="font-size:0.875rem;color:#475569;font-weight:500;">Total Training Records</div>
        <div style="font-size:2rem;font-weight:700;color:#0f172a;">{{ $stats['total_training_records'] }}</div>
      </div>
    </div>

    <div style="display:grid;grid-template-columns:repeat(1,minmax(0,1fr));gap:1rem;">
      <div style="background:#ffffff;padding:1.5rem;border-radius:1rem;box-shadow:0 1px 2px rgba(0,0,0,0.08);border:1px solid #e5e7eb;">
        <div style="font-size:1.125rem;font-weight:700;color:#0f172a;margin-bottom:1rem;">Completion Rate by Region</div>
        <div style="display:flex;flex-direction:column;gap:1rem;">
          @foreach($regionCompletion as $regionRow)
            <div>
              <div style="display:flex;justify-content:space-between;align-items:center;font-size:0.875rem;margin-bottom:0.25rem;color:#0f172a;font-weight:500;">
                <span>{{ $regionRow['region'] }}</span>
                <span style="color:#64748b;">{{ $regionRow['rate'] }}%</span>
              </div>
              <div style="width:100%;background:#f3f4f6;border-radius:999px;height:0.75rem;overflow:hidden;">
                <div style="--bar-width: {{ $regionRow['rate'] }}%; width: var(--bar-width); background:#1A3A5C;height:0.75rem;border-radius:999px;"></div>
              </div>
            </div>
          @endforeach
        </div>
      </div>

      <div style="background:#ffffff;padding:1.5rem;border-radius:1rem;box-shadow:0 1px 2px rgba(0,0,0,0.08);border:1px solid #e5e7eb;">
        <div style="font-size:1.125rem;font-weight:700;color:#0f172a;margin-bottom:1rem;">HAV Score Distribution</div>
        <div style="display:flex;flex-direction:column;gap:0.75rem;">
          @foreach($havDistribution as $category)
            <div style="display:flex;justify-content:space-between;align-items:center;font-size:0.875rem;color:#475569;">
              <span>{{ $category['category'] }}</span>
              <span style="font-weight:600;color:#0f172a;">{{ $category['count'] }}</span>
            </div>
          @endforeach
        </div>
      </div>
    </div>

    <div style="background:#ffffff;padding:1.5rem;border-radius:1rem;box-shadow:0 1px 2px rgba(0,0,0,0.08);border:1px solid #e5e7eb;">
      <div style="font-size:1.125rem;font-weight:700;color:#0f172a;margin-bottom:1rem;">Top Competency Completion Rates</div>
      <div style="display:flex;flex-direction:column;gap:1rem;">
        @foreach($competencyCompletion as $competency)
          <div>
            <div style="display:flex;justify-content:space-between;align-items:center;font-size:0.875rem;margin-bottom:0.25rem;color:#0f172a;font-weight:500;">
              <span>{{ $competency['competency_name'] }}</span>
              <span style="color:#64748b;">Avg Level: {{ number_format($competency['avg_level'], 1) }}</span>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:0.75rem;color:#64748b;margin-bottom:0.5rem;">
              <span>{{ $competency['total_records'] }} records</span>
              <span>{{ number_format(($competency['avg_level'] / 3) * 100, 1) }}% completion</span>
            </div>
            <div style="width:100%;background:#f3f4f6;border-radius:999px;height:0.5rem;overflow:hidden;">
              <div style="--bar-width: {{ ($competency['avg_level'] / 3) * 100 }}%; width: var(--bar-width); background:#10B981;height:0.5rem;border-radius:999px;"></div>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    <div style="background:#ffffff;padding:1.5rem;border-radius:1rem;box-shadow:0 1px 2px rgba(0,0,0,0.08);border:1px solid #e5e7eb;">
      <div style="font-size:1.125rem;font-weight:700;color:#0f172a;margin-bottom:1rem;">Monthly Completion Trend</div>
      <div style="display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:1rem;">
        @foreach($monthlyTrend as $point)
          <div style="background:#f8fafc;border:1px solid #e5e7eb;border-radius:0.75rem;padding:1rem;">
            <div style="font-size:0.75rem;text-transform:uppercase;letter-spacing:0.08em;color:#64748b;margin-bottom:0.5rem;">{{ $point['label'] }}</div>
            <div style="font-size:2rem;font-weight:700;color:#0f172a;margin-bottom:0.75rem;">{{ $point['value'] }}</div>
            <div style="width:100%;background:#f3f4f6;border-radius:999px;height:0.5rem;overflow:hidden;">
              <div style="--bar-width: {{ round(($point['value'] / $maxMonthly) * 100, 1) }}%; width: var(--bar-width); background:#1B7A4E;height:0.5rem;border-radius:999px;"></div>
            </div>
          </div>
        @endforeach
      </div>
    </div>

    {{-- The data table defined in NasionalPage.php --}}
    <div style="margin-top:1rem;">
      {{ $this->table }}
    </div>
  </div>
</x-filament-panels::page>

