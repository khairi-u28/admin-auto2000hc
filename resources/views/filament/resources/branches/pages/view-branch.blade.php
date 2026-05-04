<x-filament-panels::page>
    @php($data = $this->getAnalyticsData())
    <x-filament::section heading="Profil Cabang">
        <div style="display:grid;grid-template-columns:repeat(4,minmax(0,1fr));gap:10px;margin-bottom:12px;">
            <div style="border:1px solid #e5e7eb;border-radius:10px;padding:10px;">Total Karyawan<br><b>{{ $data['totalKaryawan'] }}</b></div>
            <div style="border:1px solid #e5e7eb;border-radius:10px;padding:10px;">Batch Aktif<br><b>{{ $data['activeBatch'] }}</b></div>
            <div style="border:1px solid #e5e7eb;border-radius:10px;padding:10px;">Kelulusan<br><b>{{ $data['kelulusanPct'] }}%</b></div>
            <div style="border:1px solid #e5e7eb;border-radius:10px;padding:10px;">Inaktif 90 Hari<br><b>{{ $data['inactiveCount'] }}</b></div>
        </div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:12px;">
            <div style="border:1px solid #e5e7eb;border-radius:10px;padding:10px;">
                <div style="font-size:11px;color:#6b7280;margin-bottom:8px;">Karyawan per Jabatan</div>
                @foreach($data['karyawanPerJabatan'] as $row)
                    <div style="display:flex;justify-content:space-between;padding:4px 0;border-top:1px solid #f1f5f9;"><span>{{ $row->name }}</span><b>{{ $row->total }}</b></div>
                @endforeach
            </div>
            <div style="border:1px solid #e5e7eb;border-radius:10px;padding:10px;">
                <div style="font-size:11px;color:#6b7280;margin-bottom:8px;">Top Gap Kompetensi</div>
                @foreach($data['topGaps'] as $row)
                    <div style="padding:4px 0;border-top:1px solid #f1f5f9;">{{ $row->name }}</div>
                @endforeach
            </div>
        </div>
        <table style="width:100%;font-size:12px;border-collapse:collapse;">
            <thead><tr><th style="text-align:left;padding:6px;">Kode Batch</th><th style="text-align:left;padding:6px;">Kompetensi</th><th style="text-align:left;padding:6px;">Status</th><th style="text-align:left;padding:6px;">Tgl Selesai</th></tr></thead>
            <tbody>
                @foreach($data['batchHistory'] as $batch)
                    <tr><td style="padding:6px;border-top:1px solid #f1f5f9;">{{ $batch->batch_code }}</td><td style="padding:6px;border-top:1px solid #f1f5f9;">{{ $batch->competency?->name ?? '-' }}</td><td style="padding:6px;border-top:1px solid #f1f5f9;">{{ $batch->status }}</td><td style="padding:6px;border-top:1px solid #f1f5f9;">{{ optional($batch->end_date)->format('d/m/Y') }}</td></tr>
                @endforeach
            </tbody>
        </table>
    </x-filament::section>
</x-filament-panels::page>

