<div style="display:grid;grid-template-columns:repeat(1,minmax(0,1fr));gap:1rem;">
  @foreach($this->regions as $region)
    <div style="background:#ffffff;border-radius:1rem;box-shadow:0 16px 32px rgba(15,23,42,0.08);padding:1rem;">
      <div style="display:flex;justify-content:space-between;align-items:center;border-left:4px solid #1A3A5C;background:#E8EEF4;padding:0.75rem;border-radius:0.75rem;">
        <div>
          <div style="font-size:0.875rem;font-weight:600;color:#0f172a;">{{ $region['name'] }}</div>
          <div style="font-size:0.75rem;color:#475569;">Karyawan Aktif: {{ number_format($region['active_employees']) }}</div>
        </div>
        <div class="text-right">
          <div style="font-size:0.875rem;color:#0f172a;">Behavioral</div>
          <div style="font-size:1.125rem;font-weight:700;color:#0f172a;">{{ number_format($region['behavior'], 2) }}</div>
          <div style="font-size:0.75rem;color:#475569;">AUBO B+O: {{ $region['aubo'] }}</div>
        </div>
      </div>

      <div class="mt-3">
        <div style="font-size:0.875rem;font-weight:500;margin-bottom:0.5rem;">Status Enrollment</div>
        <div style="width:100%;background:#e2e8f0;height:0.75rem;border-radius:999px;overflow:hidden;display:flex;">
          <div style="width: {{ $region['percentages']['menunggu_undangan'] }}%;background:#9ca3af;"></div>
          <div style="width: {{ $region['percentages']['hadir'] }}%;background:#2563eb;"></div>
          <div style="width: {{ $region['percentages']['lulus'] }}%;background:#16a34a;"></div>
          <div style="width: {{ $region['percentages']['batal'] }}%;background:#dc2626;"></div>
        </div>
        <div style="display:flex;justify-content:space-between;font-size:0.75rem;color:#475569;margin-top:0.5rem;">
          <div>Not started: {{ $region['breakdown']['menunggu_undangan'] }}</div>
          <div>In progress: {{ $region['breakdown']['hadir'] }}</div>
          <div>Selesai: {{ $region['breakdown']['lulus'] }}</div>
          <div>Overdue: {{ $region['breakdown']['batal'] }}</div>
        </div>

        <div style="text-align:right;margin-top:0.75rem;">
          <a href="{{ \App\Filament\Pages\NasionalRegionPage::getUrl(['region' => $region['name']]) }}" style="font-size:0.875rem;color:#2563eb;">Lihat Detail →</a>
        </div>
      </div>
    </div>
  @endforeach
</div>

