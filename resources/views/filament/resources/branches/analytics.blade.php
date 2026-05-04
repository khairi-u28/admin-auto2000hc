<div style="display: flex; flex-direction: column; gap: 1.5rem; margin-top: 1.5rem;">
    
    {{-- Row 1: KPIs --}}
    <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 1rem;">
        <x-filament::section>
            <div style="color: #6b7280; font-size: 0.75rem;">Total Karyawan</div>
            <div style="font-size: 1.5rem; font-weight: 700;">{{ number_format($totalKaryawan) }}</div>
        </x-filament::section>
        <x-filament::section>
            <div style="color: #6b7280; font-size: 0.75rem;">Batch Aktif</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #185FA5;">{{ $activeBatch }}</div>
        </x-filament::section>
        <x-filament::section>
            <div style="color: #6b7280; font-size: 0.75rem;">Tingkat Kelulusan</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #0F6E56;">{{ $kelulusanPct }}%</div>
        </x-filament::section>
        <x-filament::section>
            <div style="color: #6b7280; font-size: 0.75rem;">Gap Count</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: #dc2626;">{{ $topGaps->sum(fn($g) => $g->total_emp - $g->lulus_count) }}</div>
        </x-filament::section>
    </div>

    {{-- Row 2: LP Fulfillment & Top Gaps --}}
    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1.5rem;">
        <x-filament::section heading="LP Fulfillment per Jabatan">
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                @foreach($lpFulfillment as $row)
                <div>
                    <div style="display: flex; justify-content: space-between; font-size: 0.75rem; margin-bottom: 0.25rem;">
                        <span>{{ $row['jabatan'] }}</span>
                        <span style="font-weight: 600;">{{ $row['lulus'] }} / {{ $row['total'] }} ({{ $row['pct'] }}%)</span>
                    </div>
                    <div style="height: 8px; background: #f3f4f6; border-radius: 4px; overflow: hidden;">
                        <div style="width: {{ $row['pct'] }}%; height: 100%; background: #6366f1; border-radius: 4px;"></div>
                    </div>
                </div>
                @endforeach
            </div>
        </x-filament::section>

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
    </div>

    {{-- Row 3: Batch History --}}
    <x-filament::section heading="Batch History">
        <table style="width: 100%; border-collapse: collapse; font-size: 0.875rem;">
            <thead>
                <tr style="border-bottom: 1px solid #e5e7eb; background: #f9fafb;">
                    <th style="text-align: left; padding: 0.75rem;">Kode Batch</th>
                    <th style="text-align: left; padding: 0.75rem;">Kompetensi</th>
                    <th style="text-align: center; padding: 0.75rem;">Tipe</th>
                    <th style="text-align: center; padding: 0.75rem;">Status</th>
                    <th style="text-align: center; padding: 0.75rem;">Lulus/Total</th>
                    <th style="text-align: center; padding: 0.75rem;">Kelulusan %</th>
                    <th style="text-align: center; padding: 0.75rem;">Tgl Selesai</th>
                </tr>
            </thead>
            <tbody>
                @foreach($batchHistory as $batch)
                @php
                    $pct = $batch->total_count > 0 ? round(($batch->lulus_count / $batch->total_count) * 100, 1) : 0;
                @endphp
                <tr style="border-bottom: 1px solid #e5e7eb;">
                    <td style="padding: 0.75rem; font-weight: 600;">{{ $batch->batch_code }}</td>
                    <td style="padding: 0.75rem;">{{ $batch->competency->name }}</td>
                    <td style="padding: 0.75rem; text-align: center;">{{ $batch->type }}</td>
                    <td style="padding: 0.75rem; text-align: center;">
                        <span style="padding: 2px 8px; border-radius: 10px; font-size: 0.6875rem; font-weight: 600; background: #f3f4f6;">{{ ucfirst($batch->status) }}</span>
                    </td>
                    <td style="padding: 0.75rem; text-align: center;">{{ $batch->lulus_count }} / {{ $batch->total_count }}</td>
                    <td style="padding: 0.75rem; text-align: center; font-weight: 600;">{{ $pct }}%</td>
                    <td style="padding: 0.75rem; text-align: center;">{{ $batch->end_date?->format('d M Y') ?? '-' }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </x-filament::section>

    {{-- Row 4: Early Warning --}}
    @if($inactiveCount > 0 || $upcomingEnd->count() > 0)
    <div style="display: flex; flex-direction: column; gap: 1rem;">
        @if($inactiveCount > 0)
        <div style="padding: 1rem; border-radius: 0.75rem; background: #fffbeb; border: 1px solid #fde68a; display: flex; align-items: center; gap: 0.75rem;">
            <svg style="width: 1.5rem; height: 1.5rem; color: #d97706;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
            <span style="color: #92400e; font-weight: 500;">{{ $inactiveCount }} Karyawan tanpa aktivitas learning ≥ 90 hari</span>
        </div>
        @endif

        @if($upcomingEnd->count() > 0)
        <div style="padding: 1rem; border-radius: 0.75rem; background: #fffbeb; border: 1px solid #fde68a; display: flex; flex-direction: column; gap: 0.5rem;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <svg style="width: 1.5rem; height: 1.5rem; color: #d97706;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span style="color: #92400e; font-weight: 600;">Batch Mendekati Akhir (≤ 7 hari):</span>
            </div>
            <ul style="margin-left: 2.25rem; color: #92400e; font-size: 0.875rem;">
                @foreach($upcomingEnd as $b)
                <li>{{ $b->batch_code }} - {{ $b->competency->name }} (Selesai: {{ $b->end_date->format('d M Y') }})</li>
                @endforeach
            </ul>
        </div>
        @endif
    </div>
    @endif

</div>
