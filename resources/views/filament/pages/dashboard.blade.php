<x-filament-panels::page>
<style>
.db-grid{display:grid;gap:12px}
.db-grid-4{grid-template-columns:repeat(4,minmax(0,1fr))}
.db-grid-2-1{grid-template-columns:5fr 7fr}
.db-grid-1-1{grid-template-columns:repeat(2,minmax(0,1fr))}
.db-grid-recent{grid-template-columns:7fr 5fr}

@media (max-width: 1024px) {
    .db-grid-4 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .db-grid-2-1, .db-grid-recent { grid-template-columns: 1fr; }
}
@media (max-width: 640px) {
    .db-grid-4, .db-grid-1-1 { grid-template-columns: 1fr; }
    .bar-lbl { width: 80px; }
}
.db-card{background:var(--color-background-primary,#fff);border:0.5px solid
 var(--color-border-tertiary,#e5e7eb);border-radius:12px;padding:14px 16px}
.db-card-title{font-size:11px;font-weight:500;color:var(--color-text-secondary,#6b7280);
 text-transform:uppercase;letter-spacing:0.5px;margin-bottom:10px}
.kpi-val{font-size:22px;font-weight:500;line-height:1.1}
.kpi-sub{font-size:11px;color:var(--color-text-secondary,#6b7280);margin-top:3px}
.bar-row{display:flex;align-items:center;gap:8px;margin-bottom:6px}
.bar-lbl{font-size:11px;color:var(--color-text-secondary,#6b7280);
 width:120px;flex-shrink:0;text-align:right;white-space:nowrap;
 overflow:hidden;text-overflow:ellipsis}
.bar-track{flex:1;background:var(--color-background-secondary,#f9fafb);
 border-radius:4px;height:9px;overflow:hidden}
.bar-fill{height:100%;border-radius:4px}
.leg-row{display:flex;align-items:center;gap:6px;font-size:11px;
 color:var(--color-text-primary,#111);margin-bottom:4px}
.leg-dot{width:9px;height:9px;border-radius:50%;flex-shrink:0}
.leg-val{margin-left:auto;font-weight:500}
.warn-box{border-radius:8px;padding:8px 10px;margin-bottom:6px;
 display:flex;gap:8px;align-items:flex-start}
.warn-title{font-size:12px;font-weight:500;margin-bottom:1px}
.warn-sub{font-size:10px;color:var(--color-text-secondary,#6b7280)}
.mini-table{width:100%;border-collapse:collapse;font-size:11px}
.mini-table th{font-size:10px;color:var(--color-text-secondary,#6b7280);
 padding:0 6px 6px;text-align:left;font-weight:500}
.mini-table td{padding:6px;border-top:0.5px solid 
 var(--color-border-tertiary,#e5e7eb)}
.badge{display:inline-block;padding:1px 8px;border-radius:20px;
 font-size:10px;font-weight:500}
</style>

{{-- ROW 1: ORG STATS (4 cards) --}}
<div class="db-grid db-grid-4" style="margin-bottom:12px">

  {{-- Total Karyawan --}}
  <div class="db-card">
    <div class="db-card-title">Total Karyawan</div>
    <div class="kpi-val" style="color:#185FA5">
      {{ number_format($totalKaryawan) }}
    </div>
    <div class="kpi-sub">Aktif dari {{ number_format($totalKaryawanAll) }} total</div>
    @php $inactivePct = $totalKaryawanAll > 0 
        ? round(($totalKaryawanAll - $totalKaryawan) / $totalKaryawanAll * 100) : 0 @endphp
    <div style="margin-top:8px;background:var(--color-background-secondary,#f9fafb);
     border-radius:6px;height:6px;overflow:hidden">
      <div style="height:100%;border-radius:6px;background:#185FA5;
       width:{{ 100 - $inactivePct }}%"></div>
    </div>
    <div style="font-size:10px;color:var(--color-text-secondary,#6b7280);
     margin-top:3px">{{ $inactivePct }}% tidak aktif</div>
  </div>

  {{-- Region --}}
  <div class="db-card">
    <div class="db-card-title">Region</div>
    <div class="kpi-val" style="color:#0F6E56">{{ $totalRegion }}</div>
    <div class="kpi-sub">Wilayah operasional</div>
    <div style="margin-top:10px;display:flex;flex-direction:column;gap:3px">
      <div style="font-size:10px;color:var(--color-text-secondary,#6b7280)">
        Mencakup {{ $totalArea }} area
      </div>
      <div style="font-size:10px;color:var(--color-text-secondary,#6b7280)">
        & {{ $totalCabang }} cabang
      </div>
    </div>
  </div>

  {{-- Area --}}
  <div class="db-card">
    <div class="db-card-title">Area</div>
    <div class="kpi-val" style="color:#534AB7">{{ $totalArea }}</div>
    <div class="kpi-sub">Di bawah {{ $totalRegion }} region</div>
    @php $avgCabang = $totalArea > 0 ? round($totalCabang / $totalArea, 1) : 0 @endphp
    <div style="margin-top:10px;font-size:10px;
     color:var(--color-text-secondary,#6b7280)">
      Rata-rata {{ $avgCabang }} cabang/area
    </div>
  </div>

  {{-- Cabang by Tipe --}}
  <div class="db-card">
    <div class="db-card-title">Cabang ({{ $totalCabang }})</div>
    @foreach(['VSP'=>'#185FA5','V'=>'#0F6E56','SP'=>'#534AB7','BP'=>'#854F0B','HO'=>'#3B6D11'] as $tipe => $color)
    @php $count = $cabangByTipe[$tipe] ?? 0 @endphp
    <div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
      <span style="font-size:11px;width:24px;font-weight:500;
       color:{{ $color }}">{{ $tipe }}</span>
      <div style="flex:1;background:var(--color-background-secondary,#f9fafb);
       border-radius:3px;height:7px;overflow:hidden">
        <div style="height:100%;border-radius:3px;background:{{ $color }};
         width:{{ $totalCabang > 0 ? round($count/$totalCabang*100) : 0 }}%">
        </div>
      </div>
      <span style="font-size:11px;font-weight:500;width:28px;
       text-align:right">{{ $count }}</span>
    </div>
    @endforeach
  </div>
</div>

{{-- ROW 4: RECENT BATCHES + EARLY WARNING --}}
<div class="db-grid db-grid-recent">

  {{-- Recent Batches --}}
  <div class="db-card">
    <div class="db-card-title">Batch Terbaru</div>
    <table class="mini-table">
      <thead><tr>
        <th>Batch</th><th>Kompetensi</th>
        <th>Tipe</th><th>Status</th><th>Peserta</th><th>Lulus %</th>
      </tr></thead>
      <tbody>
        @forelse($recentBatches as $batch)
        @php
          $sc = match($batch->status) {
            'selesai'     =>'background:#EAF3DE;color:#3B6D11',
            'berlangsung' =>'background:#FAEEDA;color:#854F0B',
            'open'        =>'background:#E6F1FB;color:#185FA5',
            'dibatalkan'  =>'background:#FCEBEB;color:#A32D2D',
            default       =>'background:#F1EFE8;color:#5F5E5A',
          };
          $pCount = $batch->participants_count ?? 0;
          $kpct = $batch->kelulusan_pct ?? 0;
          $kc = $kpct >= 70 ? '#3B6D11' : ($kpct >= 50 ? '#854F0B' : '#A32D2D');
          $kb = $kpct >= 70 ? '#EAF3DE' : ($kpct >= 50 ? '#FAEEDA' : '#FCEBEB');
        @endphp
        <tr>
          <td style="font-weight:500">{{ $batch->batch_code }}</td>
          <td>{{ Str::limit($batch->competency?->name ?? '-', 20) }}</td>
          <td>
            <span class="badge" style="background:{{ $batch->type==='HO'
              ? '#EEEDFE' : '#E1F5EE' }};color:{{ $batch->type==='HO'
              ? '#534AB7' : '#0F6E56' }}">{{ $batch->type }}</span>
          </td>
          <td><span class="badge" style="{{ $sc }}">
            {{ ucfirst($batch->status) }}</span></td>
          <td>{{ $batch->target_participants }}</td>
          <td>
            <span class="badge" style="background:{{ $kb }};color:{{ $kc }}">
              {{ $kpct }}%
            </span>
          </td>
        </tr>
        @empty
        <tr><td colspan="5" style="text-align:center;
         color:var(--color-text-secondary,#6b7280);padding:16px">
          Belum ada batch</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Early Warning --}}
  <div class="db-card">
    <div class="db-card-title">⚑ Early Warning</div>
    @forelse($warnings as $warn)
    @php
      $bg  = $warn['type']==='danger' ? '#FCEBEB' : '#FAEEDA';
      $bdr = $warn['type']==='danger' ? '#F7C1C1' : '#FAC775';
      $tc  = $warn['type']==='danger' ? '#A32D2D' : '#854F0B';
      $dot = $warn['type']==='danger' ? '#E24B4A' : '#EF9F27';
    @endphp
    <div class="warn-box" style="background:{{ $bg }};
     border:0.5px solid {{ $bdr }}">
      <div style="width:8px;height:8px;border-radius:50%;
       background:{{ $dot }};flex-shrink:0;margin-top:2px"></div>
      <div>
        <div class="warn-title" style="color:{{ $tc }}">
          {{ $warn['count'] }} {{ $warn['label'] }}
        </div>
        <div class="warn-sub">{{ $warn['sub'] }}</div>
      </div>
    </div>
    @empty
    <div style="padding:20px 0;text-align:center">
      <div style="font-size:24px;margin-bottom:6px">✓</div>
      <div style="font-size:12px;color:var(--color-text-secondary,#6b7280)">
        Semua indikator normal
      </div>
    </div>
    @endforelse
  </div>
</div>

{{-- ROW 2: BATCH DONUT + LP FULFILLMENT --}}
<div class="db-grid db-grid-2-1" style="margin-bottom:12px">

  {{-- Batch Status Donut --}}
  <div class="db-card">
    <div class="db-card-title">Status Training</div>
    @php
      $batchColors = ['draft'=>'#B4B2A9','open'=>'#185FA5',
        'berlangsung'=>'#EF9F27','selesai'=>'#639922','dibatalkan'=>'#E24B4A'];
      $batchLabels = ['draft'=>'Draft','open'=>'Open',
        'berlangsung'=>'Berlangsung','selesai'=>'Selesai','dibatalkan'=>'Dibatalkan'];
      $totalBatches = array_sum($batchStatusCounts ?: [0]);
    @endphp
    <div style="display:flex;align-items:center;gap:16px">
      <svg width="200" height="200" viewBox="0 0 100 100">
        @php
          $offset = 0;
          $circumference = 2 * M_PI * 38;
        @endphp
        @foreach($batchColors as $status => $color)
          @php
            $count = $batchStatusCounts[$status] ?? 0;
            $dash = $totalBatches > 0 
              ? ($count / $totalBatches) * $circumference : 0;
            $gap  = $circumference - $dash;
          @endphp
          <circle cx="50" cy="50" r="38" fill="none"
            stroke="{{ $color }}" stroke-width="16"
            stroke-dasharray="{{ round($dash,2) }} {{ round($gap,2) }}"
            stroke-dashoffset="{{ round(-$offset, 2) }}"
            transform="rotate(-90 50 50)"/>
          @php $offset += $dash @endphp
        @endforeach
        <text x="50" y="46" text-anchor="middle" font-size="14"
          font-weight="500" fill="var(--color-text-primary,#111)">
          {{ $totalBatches }}
        </text>
        <text x="50" y="58" text-anchor="middle" font-size="9"
          fill="var(--color-text-secondary,#6b7280)">total</text>
      </svg>
      <div style="flex:1">
        @foreach($batchColors as $status => $color)
        <div class="leg-row">
          <div class="leg-dot" style="background:{{ $color }}"></div>
          {{ $batchLabels[$status] }}
          <span class="leg-val">{{ $batchStatusCounts[$status] ?? 0 }}</span>
        </div>
        @endforeach
      </div>
    </div>
  </div>

  {{-- Learning Path Fulfillment by Job Role --}}
  <div class="db-card">
    <div class="db-card-title">Fulfillment Learning Path per Jabatan</div>
    @forelse($lpFulfillment as $item)
    @php
      $pct = $item['pct'];
      $barColor = $pct >= 70 ? '#639922' : ($pct >= 40 ? '#EF9F27' : '#E24B4A');
    @endphp
    <div class="bar-row">
      <div class="bar-lbl" title="{{ $item['name'] }}">{{ $item['name'] }}</div>
      <div class="bar-track">
        <div class="bar-fill" style="width:{{ $pct }}%;background:{{ $barColor }}">
        </div>
      </div>
      <span style="font-size:11px;font-weight:500;width:34px;text-align:right;
       color:{{ $barColor }}">{{ $pct }}%</span>
    </div>
    @empty
    <p style="font-size:12px;color:var(--color-text-secondary,#6b7280);
     padding:16px 0;text-align:center">
      Data learning path belum tersedia
    </p>
    @endforelse
  </div>
</div>

{{-- ROW 3: FULL WIDTH — TOP COMPETENCY GAPS --}}
<div class="db-card" style="margin-bottom:12px">
  <div class="db-card-title">Top Kompetensi dengan Gap Terbanyak</div>
  <div class="db-grid db-grid-1-1" style="gap:12px">
    @foreach(array_chunk($competencyGaps, 4) as $chunk)
    <div>
      @foreach($chunk as $comp)
      @php
        $supply = $comp['total'] > 0 
          ? round($comp['lulus'] / $comp['total'] * 100) : 0;
        $gapPct = 100 - $supply;
      @endphp
      <div style="margin-bottom:8px">
        <div style="display:flex;justify-content:space-between;
         margin-bottom:3px">
          <span style="font-size:11px;color:var(--color-text-primary,#111)">
            {{ Str::limit($comp['name'], 35) }}
          </span>
          <span style="font-size:10px;color:#A32D2D;font-weight:500">
            {{ $comp['gap'] }} belum lulus
          </span>
        </div>
        <div style="background:var(--color-background-secondary,#f9fafb);
         border-radius:4px;height:8px;overflow:hidden;
         display:flex">
          <div style="height:100%;background:#185FA5;
           width:{{ $supply }}%"></div>
          <div style="height:100%;background:#FCEBEB;
           width:{{ $gapPct }}%"></div>
        </div>
        <div style="display:flex;justify-content:space-between;
         margin-top:2px">
          <span style="font-size:9px;color:#185FA5">
            {{ $supply }}% lulus
          </span>
          <span style="font-size:9px;color:#A32D2D">
            {{ $gapPct }}% gap
          </span>
        </div>
      </div>
      @endforeach
    </div>
    @endforeach
  </div>
</div>

</x-filament-panels::page>