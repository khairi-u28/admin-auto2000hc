<x-filament-panels::page>
<style>
.rpt-grid{display:grid;gap:12px}
.grid-6{grid-template-columns:repeat(6,minmax(0,1fr))}
.grid-7-5{grid-template-columns:7fr 5fr}
.grid-8-4{grid-template-columns:8fr 4fr}
.grid-1-1{grid-template-columns:repeat(2,minmax(0,1fr))}

@media (max-width: 1280px) {
    .grid-6 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
}
@media (max-width: 1024px) {
    .grid-7-5, .grid-8-4, .grid-1-1 { grid-template-columns: 1fr; }
}
@media (max-width: 640px) {
    .grid-6 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
    .bar-lbl { width: 70px; }
}
.rpt-card{background:var(--color-background-primary,#fff);border:0.5px solid
 var(--color-border-tertiary,#e5e7eb);border-radius:12px;padding:14px 16px}
.rpt-title{font-size:11px;font-weight:500;
 color:var(--color-text-secondary,#6b7280);
 text-transform:uppercase;letter-spacing:0.5px;margin-bottom:10px;
 display:flex;align-items:center;gap:6px}
.rpt-title-sub{font-size:10px;color:var(--color-text-tertiary,#9ca3af);
 font-weight:400;margin-left:auto}
.kpi-card{background:var(--color-background-secondary,#f9fafb);
 border-radius:8px;padding:10px 12px}
.kpi-v{font-size:20px;font-weight:500;line-height:1.1}
.kpi-l{font-size:10px;color:var(--color-text-secondary,#6b7280);margin-top:2px}
.kpi-s{font-size:10px;color:var(--color-text-tertiary,#9ca3af);margin-top:3px}
.rpt-table{width:100%;border-collapse:collapse;font-size:11px}
.rpt-table th{font-size:9px;font-weight:500;
 color:var(--color-text-secondary,#6b7280);
 text-transform:uppercase;letter-spacing:0.4px;
 padding:0 8px 6px;text-align:left}
.rpt-table th:not(:first-child){text-align:center}
.rpt-table td{padding:6px 8px;
 border-top:0.5px solid var(--color-border-tertiary,#e5e7eb)}
.rpt-table td:not(:first-child){text-align:center}
.rpt-table tr:hover td{
 background:var(--color-background-secondary,#f9fafb)}
.bar-row{display:flex;align-items:center;gap:8px;margin-bottom:7px}
.bar-lbl{font-size:10px;color:var(--color-text-secondary,#6b7280);
 width:90px;flex-shrink:0;text-align:right;
 overflow:hidden;text-overflow:ellipsis;white-space:nowrap}
.bar-track{flex:1;background:var(--color-background-secondary,#f9fafb);
 border-radius:3px;height:8px;overflow:hidden;display:flex}
.pct-badge{display:inline-block;padding:1px 7px;
 border-radius:20px;font-size:10px;font-weight:500}
.warn-row{border-radius:8px;padding:8px 10px;margin-bottom:6px;
 display:flex;gap:8px;align-items:flex-start}
.hmap{border-collapse:collapse;width:100%;font-size:10px}
.hmap th{font-size:9px;color:var(--color-text-secondary,#6b7280);
 padding:0 6px 10px;text-align:center;font-weight:500;
 vertical-align:bottom}
.hmap th:first-child{text-align:left;width:140px}
.hmap td{padding:8px 4px;text-align:center;border-radius:3px;font-weight:500}
.hmap tr td:first-child{font-size:10px;
 color:var(--color-text-primary,#111);text-align:left;
 padding-right:8px;font-weight:400;white-space:nowrap}
</style>

{{-- FILTER BAR --}}
<div style="display:flex;gap:8px;align-items:center;padding:0 0 16px;
 border-bottom:0.5px solid var(--color-border-tertiary,#e5e7eb);
 margin-bottom:16px;flex-wrap:wrap">
  <span style="font-size:11px;font-weight:500;
   color:var(--color-text-secondary,#6b7280)">Filter:</span>

  <select wire:model.live="filterYear"
   style="border:0.5px solid var(--color-border-secondary,#d1d5db);
   border-radius:8px;padding:5px 10px;font-size:11px;
   background:var(--color-background-secondary,#f9fafb);
   color:var(--color-text-primary,#111)">
    @for($y = now()->year; $y >= now()->year - 3; $y--)
    <option value="{{ $y }}" {{ $filterYear==$y?'selected':'' }}>
      {{ $y }}
    </option>
    @endfor
  </select>

  <select wire:model.live="filterPeriode"
   style="border:0.5px solid var(--color-border-secondary,#d1d5db);
   border-radius:8px;padding:5px 10px;font-size:11px;
   background:var(--color-background-secondary,#f9fafb);
   color:var(--color-text-primary,#111)">
    <option value="full">Full Year</option>
    <option value="s1">Semester 1</option>
    <option value="s2">Semester 2</option>
  </select>

  <select wire:model.live="filterTipe"
   style="border:0.5px solid var(--color-border-secondary,#d1d5db);
   border-radius:8px;padding:5px 10px;font-size:11px;
   background:var(--color-background-secondary,#f9fafb);
   color:var(--color-text-primary,#111)">
    <option value="all">Semua Tipe</option>
    <option value="HO">HO</option>
    <option value="Cabang">Cabang</option>
  </select>

  <select wire:model.live="filterRegion"
   style="border:0.5px solid var(--color-border-secondary,#d1d5db);
   border-radius:8px;padding:5px 10px;font-size:11px;
   background:var(--color-background-secondary,#f9fafb);
   color:var(--color-text-primary,#111)">
    <option value="all">Semua Region</option>
    @foreach($regions as $r)
    <option value="{{ $r }}">{{ $r }}</option>
    @endforeach
  </select>

  <a href="{{ route('filament.admin.pages.nasional-page', 
    ['export'=>'excel','year'=>$filterYear,'periode'=>$filterPeriode,
     'tipe'=>$filterTipe,'region'=>$filterRegion]) }}"
   style="margin-left:auto;background:var(--color-background-secondary,#f9fafb);
   border:0.5px solid var(--color-border-secondary,#d1d5db);
   border-radius:8px;padding:5px 12px;font-size:11px;text-decoration:none;
   color:var(--color-text-primary,#111);display:flex;align-items:center;gap:4px">
    ↓ Export Excel
  </a>
</div>

{{-- KPI ROW --}}
<div class="rpt-grid grid-6" style="margin-bottom:14px">
  @php
    $kpis = [
      ['val'=>$totalSelesai,'lbl'=>'Batch Selesai','sub'=>'periode ini','c'=>'#185FA5'],
      ['val'=>number_format($totalPeserta),'lbl'=>'Peserta Terlatih',
       'sub'=>'karyawan unik','c'=>'#0F6E56'],
      ['val'=>$kelulusanPct.'%','lbl'=>'Tingkat Kelulusan',
       'sub'=>$totalLulus.' lulus / '.$totalEval.' dievaluasi','c'=>'#3B6D11'],
      ['val'=>$avgTraining,'lbl'=>'Avg Rating Training',
       'sub'=>'dari skala 5.0','c'=>'#534AB7'],
      ['val'=>$avgTrainer,'lbl'=>'Avg Rating Trainer',
       'sub'=>'dari skala 5.0','c'=>'#854F0B'],
      ['val'=>$totalOverdue,'lbl'=>'Batch Terlambat',
       'sub'=>'perlu tindak lanjut',
       'c'=>$totalOverdue > 0 ? '#A32D2D' : '#3B6D11'],
    ];
  @endphp
  @foreach($kpis as $k)
  <div class="kpi-card">
    <div class="kpi-v" style="color:{{ $k['c'] }}">{{ $k['val'] }}</div>
    <div class="kpi-l">{{ $k['lbl'] }}</div>
    <div class="kpi-s">{{ $k['sub'] }}</div>
  </div>
  @endforeach
</div>

{{-- ROW 2: REGION TABLE + DONUT --}}
<div class="rpt-grid grid-7-5" style="margin-bottom:12px">

  <div class="rpt-card">
    <div class="rpt-title">Performa per Region</div>
    <table class="rpt-table">
      <thead><tr>
        <th>Region</th><th>Batch</th><th>Peserta</th>
        <th>Lulus</th><th>Kelulusan</th><th>Rating</th>
      </tr></thead>
      <tbody>
        @forelse($regionData as $row)
        @php
          $pct = $row->total_peserta > 0
            ? round($row->total_lulus / $row->total_peserta * 100, 1) : 0;
          $bc = $pct>=70?'background:#EAF3DE;color:#3B6D11':
                ($pct>=50?'background:#FAEEDA;color:#854F0B':
                'background:#FCEBEB;color:#A32D2D');
        @endphp
        <tr>
          <td style="font-weight:500">{{ $row->region }}</td>
          <td>{{ $row->total_batch }}</td>
          <td>{{ number_format($row->total_peserta) }}</td>
          <td>{{ number_format($row->total_lulus) }}</td>
          <td><span class="pct-badge" style="{{ $bc }}">{{ $pct }}%</span></td>
          <td>{{ $row->avg_rating ?? '-' }}</td>
        </tr>
        @empty
        <tr><td colspan="6" style="text-align:center;padding:16px;
          color:var(--color-text-secondary,#6b7280)">
          Belum ada data batch untuk filter ini
        </td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="rpt-card">
    <div class="rpt-title">Distribusi Status Batch</div>
    @php
      $btColors=['draft'=>'#B4B2A9','open'=>'#185FA5','berlangsung'=>'#EF9F27',
        'selesai'=>'#639922','dibatalkan'=>'#E24B4A'];
      $btLabels=['draft'=>'Draft','open'=>'Open','berlangsung'=>'Berlangsung',
        'selesai'=>'Selesai','dibatalkan'=>'Dibatalkan'];
      $tbTotal=array_sum($batchStatusCounts ?: [0]);
      $circ = 2*M_PI*38;
      $off = 0;
    @endphp
    <div style="display:flex;align-items:center;gap:12px">
      <svg width="90" height="90" viewBox="0 0 90 90">
        @foreach($btColors as $st => $col)
          @php
            $cnt=$batchStatusCounts[$st]??0;
            $d=$tbTotal>0?($cnt/$tbTotal)*$circ:0;
            $g=$circ-$d;
          @endphp
          <circle cx="45" cy="45" r="38" fill="none" stroke="{{ $col }}"
            stroke-width="14"
            stroke-dasharray="{{ round($d,2) }} {{ round($g,2) }}"
            stroke-dashoffset="{{ round(-$off,2) }}"
            transform="rotate(-90 45 45)"/>
          @php $off+=$d @endphp
        @endforeach
        <text x="45" y="42" text-anchor="middle" font-size="13"
          font-weight="500" fill="var(--color-text-primary,#111)">
          {{ $tbTotal }}</text>
        <text x="45" y="53" text-anchor="middle" font-size="9"
          fill="var(--color-text-secondary,#6b7280)">total</text>
      </svg>
      <div style="flex:1">
        @foreach($btColors as $st => $col)
        <div style="display:flex;align-items:center;gap:6px;
         margin-bottom:5px;font-size:11px">
          <div style="width:8px;height:8px;border-radius:50%;
           background:{{ $col }};flex-shrink:0"></div>
          {{ $btLabels[$st] }}
          <span style="margin-left:auto;font-weight:500">
            {{ $batchStatusCounts[$st]??0 }}
          </span>
        </div>
        @endforeach
      </div>
    </div>
  </div>
</div>

{{-- ROW 3: TREND CHART + EARLY WARNING --}}
<div class="rpt-grid grid-8-4" style="margin-bottom:12px">

  <div class="rpt-card">
    <div class="rpt-title">Tren Batch Selesai per Bulan
      <span class="rpt-title-sub">{{ $filterYear }}</span></div>
    @php
      $months=['Jan','Feb','Mar','Apr','Mei','Jun',
               'Jul','Ags','Sep','Okt','Nov','Des'];
      $maxVal=max($monthlyTrend->max('selesai') ?: 1, 1);
      $svgH=70; $svgW=480;
      $pts=[];
      foreach($monthlyTrend as $i=>$m){
        $x=round($i*($svgW/11));
        $y=round($svgH - ($m['selesai']/$maxVal)*($svgH-4));
        $pts[]=$x.','.$y;
      }
      $polyline=implode(' ',$pts);
      $polygon=$polyline.' '.$svgW.','.$svgH.' 0,'.$svgH;
    @endphp
    <div style="position:relative">
      <svg width="100%" height="70" viewBox="0 0 {{ $svgW }} {{ $svgH }}"
       preserveAspectRatio="none">
        <polygon points="{{ $polygon }}"
          fill="#E6F1FB" opacity="0.6"/>
        <polyline points="{{ $polyline }}"
          fill="none" stroke="#185FA5" stroke-width="2"
          stroke-linejoin="round"/>
        @foreach($monthlyTrend as $i=>$m)
          @php $x=round($i*($svgW/11));
            $y=round($svgH-($m['selesai']/$maxVal)*($svgH-4)); @endphp
          <circle cx="{{ $x }}" cy="{{ $y }}" r="3" fill="#185FA5"/>
          @if($m['selesai']>0)
          <text x="{{ $x }}" y="{{ max(8,$y-5) }}" text-anchor="middle"
            font-size="8" fill="#185FA5">{{ $m['selesai'] }}</text>
          @endif
        @endforeach
      </svg>
    </div>
    <div style="display:flex;justify-content:space-between;margin-top:4px">
      @foreach($months as $i => $mn)
      <span style="font-size:9px;color:var(--color-text-tertiary,#9ca3af)">
        {{ $mn }}</span>
      @endforeach
    </div>
  </div>

  <div class="rpt-card">
    <div class="rpt-title">⚑ Early Warning</div>
    @if($totalOverdue > 0)
    <div class="warn-row" style="background:#FCEBEB;border:0.5px solid #F7C1C1">
      <div style="width:7px;height:7px;border-radius:50%;
       background:#E24B4A;flex-shrink:0;margin-top:2px"></div>
      <div>
        <div style="font-size:11px;font-weight:500;color:#A32D2D;
         margin-bottom:1px">{{ $totalOverdue }} Batch Terlambat</div>
        <div style="font-size:10px;color:var(--color-text-secondary,#6b7280)">
          Melewati tanggal selesai</div>
      </div>
    </div>
    @endif
    @if(collect($lowFulfillRoles)->where('pct','<',30)->count() > 0)
    <div class="warn-row" style="background:#FAEEDA;border:0.5px solid #FAC775">
      <div style="width:7px;height:7px;border-radius:50%;
       background:#EF9F27;flex-shrink:0;margin-top:2px"></div>
      <div>
        <div style="font-size:11px;font-weight:500;color:#854F0B;margin-bottom:1px">
          {{ collect($lowFulfillRoles)->where('pct','<',30)->count() }} Jabatan
          Fulfillment &lt;30%
        </div>
        <div style="font-size:10px;
         color:var(--color-text-secondary,#6b7280)">
          Learning path belum terpenuhi</div>
      </div>
    </div>
    @endif
    @if($totalOverdue === 0 && collect($lowFulfillRoles)->where('pct','<',30)->count()===0)
    <div style="padding:20px 0;text-align:center">
      <div style="font-size:20px;margin-bottom:6px">✓</div>
      <div style="font-size:12px;color:var(--color-text-secondary,#6b7280)">
        Semua indikator normal</div>
    </div>
    @endif
  </div>
</div>

{{-- ROW 4: SUPPLY VS GAP + HEATMAP --}}
<div class="rpt-grid grid-1-1" style="margin-bottom:12px">

  <div class="rpt-card">
    <div class="rpt-title">Supply vs Gap Kompetensi
      <span class="rpt-title-sub">top 8 gap terbesar</span></div>
    <div style="display:flex;gap:10px;margin-bottom:8px">
      <div style="display:flex;align-items:center;gap:5px;font-size:10px">
        <div style="width:8px;height:8px;border-radius:50%;
         background:#185FA5"></div>Sudah lulus
      </div>
      <div style="display:flex;align-items:center;gap:5px;font-size:10px">
        <div style="width:8px;height:8px;border-radius:50%;
         background:#E24B4A"></div>Masih gap
      </div>
    </div>
    @foreach($competencyAnalysis as $comp)
    @php
      $sup = $comp['demand'] > 0
        ? round($comp['supply'] / $comp['demand'] * 100) : 0;
      $gap = 100 - $sup;
    @endphp
    <div class="bar-row">
      <div class="bar-lbl" title="{{ $comp['name'] }}">
        {{ Str::limit($comp['name'],18) }}</div>
      <div class="bar-track">
        <div style="height:100%;background:#185FA5;width:{{ $sup }}%"></div>
        <div style="height:100%;background:#FCEBEB;width:{{ $gap }}%"></div>
      </div>
      <span style="font-size:10px;color:#A32D2D;font-weight:500;
       width:38px;text-align:right">
        {{ $comp['demand'] - $comp['supply'] }}</span>
    </div>
    @endforeach
  </div>

</div>

<div class="rpt-card" style="margin-bottom:12px">
  <div class="rpt-title">Fulfillment Jabatan × Kompetensi
    <span class="rpt-title-sub">% karyawan lulus</span></div>
  <div style="overflow-x:auto">
    <table class="hmap">
      <thead><tr>
        <th></th>
        @foreach(($heatmapData[0]['cells'] ?? []) as $cell)
        <th title="{{ $cell['comp'] }}">
          {{ $cell['comp'] }}</th>
        @endforeach
      </tr></thead>
      <tbody>
        @foreach($heatmapData as $row)
        <tr>
          <td>{{ $row['role'] }}</td>
          @foreach($row['cells'] as $cell)
          @php
            $p=$cell['pct'];
            $cs=$p>=90?'background:#9FE1CB;color:#085041':
              ($p>=70?'background:#EAF3DE;color:#3B6D11':
              ($p>=30?'background:#FAEEDA;color:#854F0B':
              'background:#FCEBEB;color:#A32D2D'));
          @endphp
          <td style="{{ $cs }}">{{ $p }}%</td>
          @endforeach
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>
  <div style="display:flex;gap:5px;margin-top:12px;align-items:center;flex-wrap:wrap">
    <span style="font-size:9px;color:var(--color-text-secondary,#6b7280)">Skala:</span>
    @foreach(['FCEBEB|A32D2D|<30%','FAEEDA|854F0B|30–69%',
      'EAF3DE|3B6D11|70–89%','9FE1CB|085041|≥90%'] as $s)
    @php [$bg,$tc,$lbl]=explode('|',$s) @endphp
    <span style="font-size:9px;padding:2px 6px;border-radius:3px;
     background:#{{ $bg }};color:#{{ $tc }}">{{ $lbl }}</span>
    @endforeach
  </div>
</div>

{{-- ROW 5: FULL WIDTH — TRAINER & DETAIL WARNINGS --}}
<div class="rpt-grid grid-1-1" style="margin-bottom:12px">
  {{-- Trainer List --}}
  <div class="rpt-card">
    <div class="rpt-title">Daftar Trainer Teraktif</div>
    <div style="overflow-x:auto">
      <table class="rpt-table">
        <thead><tr>
          <th>Nama Trainer</th><th>NRP</th>
          <th>Cabang</th><th>Total Training</th>
        </tr></thead>
        <tbody>
          @forelse($trainerData as $t)
          <tr>
            <td style="font-weight:500">{{ $t->nama_lengkap }}</td>
            <td>{{ $t->nrp }}</td>
            <td>
              <div style="font-size:10px;font-weight:600">{{ $t->kode_cabang }}</div>
              <div style="font-size:9px;color:var(--color-text-tertiary,#9ca3af)">{{ $t->branch_name }}</div>
            </td>
            <td>
              <span class="pct-badge" style="background:#E6F1FB;color:#185FA5">
                {{ $t->total_training }} Batch
              </span>
            </td>
          </tr>
          @empty
          <tr><td colspan="4" style="text-align:center;padding:16px;
            color:var(--color-text-secondary,#6b7280)">
            Tidak ada data trainer
          </td></tr>
          @endforelse
        </tbody>
      </table>
    </div>
  </div>

  {{-- Jabatan Terendah --}}
  <div class="rpt-card">
    <div class="rpt-title">Jabatan dengan Fulfillment Terendah</div>
    <div style="display:flex;flex-direction:column;gap:12px;margin-top:8px">
      @forelse($lowFulfillRoles as $r)
      @php
        $bc = $r['pct']>=70?'#639922':($r['pct']>=40?'#EF9F27':'#E24B4A');
      @endphp
      <div class="bar-row">
        <div class="bar-lbl" title="{{ $r['name'] }}">
          {{ Str::limit($r['name'],22) }}</div>
        <div class="bar-track">
          <div style="height:100%;border-radius:3px;
           background:{{ $bc }};width:{{ $r['pct'] }}%"></div>
        </div>
        <span style="font-size:10px;font-weight:500;width:30px;
         text-align:right;color:{{ $bc }}">{{ $r['pct'] }}%</span>
      </div>
      @empty
      <div style="text-align:center;padding:16px;color:var(--color-text-secondary,#6b7280)">
        Tidak ada data jabatan
      </div>
      @endforelse
    </div>
  </div>
</div>

{{-- ROW 6: FULL WIDTH — DETAIL WARNINGS --}}
<div class="rpt-card">
  <div class="rpt-title">Detail Batch Terlambat (Overdue)</div>
  <table class="rpt-table">
    <thead><tr>
      <th>Kode Batch</th><th>Kompetensi</th>
      <th>Tgl Selesai</th><th>Selisih</th>
    </tr></thead>
    <tbody>
      @forelse($overdueBatches as $b)
      @php $days = \Carbon\Carbon::parse($b->end_date)->diffInDays(now()) @endphp
      <tr>
        <td style="font-weight:500">{{ $b->batch_code }}</td>
        <td>{{ Str::limit($b->competency?->name??'-',30) }}</td>
        <td>{{ \Carbon\Carbon::parse($b->end_date)->format('d/m/Y') }}</td>
        <td><span class="pct-badge"
          style="background:#FCEBEB;color:#A32D2D">
          +{{ $days }} hr</span></td>
      </tr>
      @empty
      <tr><td colspan="4" style="text-align:center;
        color:var(--color-text-secondary,#6b7280);padding:12px">
        Tidak ada batch terlambat
      </td></tr>
      @endforelse
    </tbody>
  </table>
</div>
</x-filament-panels::page>