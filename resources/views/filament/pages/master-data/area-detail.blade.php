<div style="display: flex; flex-direction: column; gap: 1.5rem;">
    
    {{-- Header & KPIs --}}
    <div>
        <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem;">
            <h2 style="font-size: 1.5rem; font-weight: 700; color: #111827;">Area: {{ $area }}</h2>
            <span style="padding: 2px 10px; border-radius: 12px; font-size: 0.75rem; font-weight: 600; background: #EEF2FF; color: #4338CA;">{{ $region }}</span>
        </div>
        <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 1rem;">
            <x-filament::section>
                <div style="color: #6b7280; font-size: 0.75rem;">Total Karyawan</div>
                <div style="font-size: 1.25rem; font-weight: 700;">{{ number_format($totalKaryawan) }}</div>
            </x-filament::section>
            <x-filament::section>
                <div style="color: #6b7280; font-size: 0.75rem;">Total Cabang</div>
                <div style="font-size: 1.25rem; font-weight: 700;">{{ $totalCabang }}</div>
            </x-filament::section>
            <x-filament::section>
                <div style="color: #6b7280; font-size: 0.75rem;">Batch Aktif</div>
                <div style="font-size: 1.25rem; font-weight: 700; color: #185FA5;">{{ $activeBatch }}</div>
            </x-filament::section>
            <x-filament::section>
                <div style="color: #6b7280; font-size: 0.75rem;">Tingkat Kelulusan</div>
                <div style="font-size: 1.25rem; font-weight: 700; color: #0F6E56;">{{ $kelulusanPct }}%</div>
            </x-filament::section>
            <x-filament::section>
                <div style="color: #6b7280; font-size: 0.75rem;">ABH</div>
                <div style="font-size: 0.9375rem; font-weight: 600; margin-top: 0.25rem;">{{ $abhName }}</div>
            </x-filament::section>
        </div>
    </div>

    {{-- Cabang Breakdown Table --}}
    <x-filament::section heading="Daftar Cabang di Area Ini">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
            <thead>
                <tr style="border-bottom: 1px solid #e5e7eb; background: #f9fafb;">
                    <th style="text-align: left; padding: 0.75rem;">Kode</th>
                    <th style="text-align: left; padding: 0.75rem;">Nama Cabang</th>
                    <th style="text-align: center; padding: 0.75rem;">Tipe</th>
                    <th style="text-align: center; padding: 0.75rem;">Karyawan</th>
                    <th style="text-align: center; padding: 0.75rem;">Batch Aktif</th>
                    <th style="text-align: center; padding: 0.75rem;">Kelulusan %</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cabangBreakdown as $row)
                <tr onclick="window.location='{{ route('filament.admin.resources.branches.view', ['record' => $row['id']]) }}'" 
                    style="cursor: pointer; border-bottom: 1px solid #e5e7eb;"
                    onmouseover="this.style.background='#f3f4f6'" 
                    onmouseout="this.style.background='transparent'">
                    <td style="padding: 0.75rem; font-weight: 600; color: #6b7280;">{{ $row['code'] }}</td>
                    <td style="padding: 0.75rem; font-weight: 600; color: #111827;">{{ $row['name'] }}</td>
                    <td style="padding: 0.75rem; text-align: center;">
                        <span style="padding: 2px 8px; border-radius: 10px; font-size: 0.6875rem; font-weight: 600; background: #F3F4F6; color: #374151;">{{ $row['type'] }}</span>
                    </td>
                    <td style="padding: 0.75rem; text-align: center;">{{ number_format($row['employees_count']) }}</td>
                    <td style="padding: 0.75rem; text-align: center;">{{ $row['active_batch'] }}</td>
                    <td style="padding: 0.75rem; text-align: center;">
                        @if($row['kelulusan_pct'] !== null)
                        <span style="padding:1px 8px;border-radius:20px;font-size:10px;font-weight:500;background:{{ $row['kelulusan_pct']>=70?'#EAF3DE':($row['kelulusan_pct']>=50?'#FAEEDA':'#FCEBEB') }};color:{{ $row['kelulusan_pct']>=70?'#3B6D11':($row['kelulusan_pct']>=50?'#854F0B':'#A32D2D') }}">
                            {{ $row['kelulusan_pct'] }}%
                        </span>
                        @else
                        <span style="color:#9ca3af;font-size:11px">-</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </x-filament::section>

    {{-- Charts & Recent Batches --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        {{-- Gap Chart --}}
        <x-filament::section heading="Top 5 Kompetensi Gap">
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach($topGaps as $gap)
                @php
                    $gapCount = $gap->total_emp - $gap->lulus_count;
                    $pct = $gap->total_emp > 0 ? round(($gapCount / $gap->total_emp) * 100) : 0;
                @endphp
                <div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.75rem; margin-bottom: 0.25rem;">
                        <span>{{ $gap->name }}</span>
                        <span style="font-weight: 600; color: #dc2626;">{{ $gapCount }} Gap</span>
                    </div>
                    <div style="height: 8px; background: #f3f4f6; border-radius: 4px; overflow: hidden;">
                        <div style="width: {{ $pct }}%; height: 100%; background: #ef4444; border-radius: 4px;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </x-filament::section>

        {{-- Recent Batches --}}
        <x-filament::section heading="Recent 5 Batches">
            <div style="display: flex; flex-direction: column; gap: 0.75rem;">
                @foreach($recentBatches as $batch)
                <div style="display: flex; justify-content: space-between; align-items: center; padding-bottom: 0.75rem; border-bottom: 1px solid #f3f4f6;">
                    <div>
                        <div style="font-weight: 600; font-size: 0.875rem;">{{ $batch->competency->name }}</div>
                        <div style="font-size: 0.75rem; color: #6b7280;">{{ $batch->branch->nama }} • {{ $batch->start_date->format('d M Y') }}</div>
                    </div>
                    <span style="font-size: 0.75rem; padding: 2px 8px; border-radius: 10px; background: #f3f4f6;">{{ ucfirst($batch->status) }}</span>
                </div>
                @endforeach
            </div>
        </x-filament::section>
    </div>
</div>
