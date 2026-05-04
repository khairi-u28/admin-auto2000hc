<x-filament-panels::page>
    <div style="display: flex; flex-direction: column; gap: 1rem;">
        <x-filament::section heading="Daftar Area">
            <table style="width: 100%; font-size: 0.875rem; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid #e5e7eb; background: #f9fafb;">
                        <th style="text-align: left; padding: 0.75rem;">Area</th>
                        <th style="text-align: left; padding: 0.75rem;">Region</th>
                        <th style="text-align: left; padding: 0.75rem;">ABH</th>
                        <th style="text-align: center; padding: 0.75rem;">Total Cabang</th>
                        <th style="text-align: center; padding: 0.75rem;">Total Karyawan</th>
                        <th style="text-align: center; padding: 0.75rem;">Batch Aktif</th>
                        <th style="text-align: center; padding: 0.75rem;">Kelulusan %</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($this->getAreaData() as $row)
                    @php
                        $areaRecord = \App\Models\Area::where('nama_area', $row['area'])->first();
                    @endphp
                    <tr onclick="window.location='{{ $areaRecord ? route('filament.admin.resources.areas.view', ['record' => $areaRecord->id]) : '#' }}'" 
                        style="cursor: pointer; border-bottom: 1px solid #e5e7eb;"
                        onmouseover="this.style.background='#f3f4f6'" 
                        onmouseout="this.style.background='transparent'">
                        <td style="padding: 0.75rem; font-weight: 600; color: #111827;">{{ $row['area'] }}</td>
                        <td style="padding: 0.75rem;">{{ $row['region'] }}</td>
                        <td style="padding: 0.75rem;">{{ $row['abh_name'] }}</td>
                        <td style="padding: 0.75rem; text-align: center;">{{ $row['total_cabang'] }}</td>
                        <td style="padding: 0.75rem; text-align: center;">{{ number_format($row['total_karyawan']) }}</td>
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
    </div>
</x-filament-panels::page>
