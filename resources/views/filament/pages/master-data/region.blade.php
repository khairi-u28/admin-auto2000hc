<x-filament-panels::page>
    <div class="space-y-4">
        <x-filament::section heading="Daftar Region">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b">
                        <th class="text-left py-2 px-3">Region</th>
                        <th class="text-center py-2 px-3">Jumlah Area</th>
                        <th class="text-center py-2 px-3">Jumlah Cabang</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($this->getRegionData() as $row)
                    <tr class="border-b hover:bg-gray-50 dark:hover:bg-gray-800">
                        <td class="py-2 px-3 font-medium">{{ $row['region'] }}</td>
                        <td class="py-2 px-3 text-center">{{ $row['total_area'] }}</td>
                        <td class="py-2 px-3 text-center">{{ $row['total_cabang'] }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </x-filament::section>
    </div>
</x-filament-panels::page>
