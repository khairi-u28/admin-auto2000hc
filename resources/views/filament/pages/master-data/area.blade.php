<x-filament-panels::page>
    <div class="space-y-4">
        <x-filament::section heading="Daftar Area">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2 px-3">Region</th>
                        <th class="text-left py-2 px-3">Area</th>
                        <th class="text-left py-2 px-3">Nama ABH</th>
                        <th class="text-center py-2 px-3">Jumlah Cabang</th>
                        <th class="text-center py-2 px-3">Jumlah Karyawan</th>
                        <th class="text-center py-2 px-3">Avg HAV Score</th>
                        <th class="text-center py-2 px-3">High Performers</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($this->getAreaData() as $row)
                    <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="py-2 px-3">{{ $row['region'] }}</td>
                        <td class="py-2 px-3 font-medium">{{ $row['area'] }}</td>
                        <td class="py-2 px-3">{{ $row['nama_abh'] ?? '-' }}</td>
                        <td class="py-2 px-3 text-center">{{ $row['total_cabang'] }}</td>
                        <td class="py-2 px-3 text-center">{{ $row['total_karyawan'] ?? 0 }}</td>
                        <td class="py-2 px-3 text-center">{{ $row['avg_hav_score'] ? number_format($row['avg_hav_score'], 1) : '-' }}</td>
                        <td class="py-2 px-3 text-center">{{ $row['high_performers'] ?? 0 }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </x-filament::section>
    </div>
</x-filament-panels::page>
