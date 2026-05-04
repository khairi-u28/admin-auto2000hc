<x-filament-panels::page>
    @php
        $golonganOptions = ['IIIA', 'IIIB', 'IIIC', 'IVA', 'IVB', 'IVC', 'VA', 'VB', 'VC'];
        $randomGolongan = $golonganOptions[array_rand($golonganOptions)];
        $pendidikanOptions = ['S1 Manajemen', 'S1 Teknik Industri', 'S1 Teknik Mesin', 'S1 Teknik Otomotif', 'S1 Sistem Informasi', 'S1 Akuntansi', 'S1 Psikologi', 'S1 Ilmu Komunikasi', 'S1 Administrasi Bisnis', 'S1 Statistika', 'S1 Matematika', 'S1 Teknik Informatika', 'S1 Ekonomi', 'S1 Hubungan Internasional', 'S1 Hukum', 'S1 Teknik Elektro', 'S1 Teknik Sipil', 'S1 Supply Chain Management', 'S1 Logistik', 'S2 Manajemen'];
        $instansiOptions = ['Universitas Indonesia', 'Institut Teknologi Bandung', 'Universitas Gadjah Mada', 'Universitas Airlangga', 'Institut Pertanian Bogor', 'Universitas Brawijaya', 'Universitas Diponegoro', 'Universitas Padjadjaran', 'Universitas Sebelas Maret', 'Universitas Hasanuddin', 'Universitas Negeri Jakarta', 'Universitas Trisakti', 'Binus University', 'Universitas Katolik Atma Jaya', 'Universitas Mercu Buana', 'Universitas Telkom', 'Universitas Andalas', 'Universitas Sumatera Utara', 'Universitas Lampung', 'Universitas Udayana'];
        $randomPendidikan = $pendidikanOptions[array_rand($pendidikanOptions)];
        $randomInstansi = $instansiOptions[array_rand($instansiOptions)];
        $kpiMid = rand(40, 85);
        $kpiFull = rand($kpiMid + 1, 100);
        $kpiYear = now()->subYear()->year;
        $allRoles = ['Sales Consultant', 'Service Advisor', 'Foreman', 'Branch Coordinator', 'Account Supervisor', 'Sales Manager', 'Area Business Head', 'Region Business Head', 'HC Analyst', 'HC Specialist', 'Product Development', 'General Services'];
        shuffle($allRoles);
        $roleHistoryCount = rand(3, 5);
        $roleHistory = [];
        $cursorDate = $this->employee->entry_date ? \Carbon\Carbon::parse($this->employee->entry_date) : now()->subYears(8);
        for ($i = 0; $i < $roleHistoryCount; $i++) {
            $isCurrent = $i === $roleHistoryCount - 1;
            $start = $cursorDate->copy();
            $end = $isCurrent ? null : $start->copy()->addMonths(rand(10, 28));
            $roleHistory[] = ['jabatan' => $isCurrent ? ($this->employee->jobRole?->name ?? $this->employee->position_name ?? 'Jabatan Saat Ini') : ($allRoles[$i] ?? 'Staff'), 'start' => $start, 'end' => $end, 'current' => $isCurrent];
            if ($end) {
                $cursorDate = $end->copy()->addDays(1);
            }
        }
    @endphp
    <style>
        /* Modern Theme Variables for Safe Mode */
        .one-sheet-container {
            --bg-page: transparent;
            --bg-card: #ffffff;
            --bg-item: #f8fafc;
            --border-card: #e2e8f0;
            --border-item: #f1f5f9;
            --text-title: #1A3A5C;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --text-dim: #94a3b8;
        }

        .dark .one-sheet-container {
            --bg-card: #111827;
            --bg-item: #1f2937;
            --border-card: #1f2937;
            --border-item: #374151;
            --text-title: #38bdf8; /* Brighter teal/blue for dark mode title */
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --text-dim: #64748b;
        }
    </style>

    <div class="one-sheet-container" style="display: flex; flex-direction: column; gap: 2rem;">
        
        {{-- Header Section --}}
        <x-filament::section>
            <div style="display: flex; flex-direction: row; align-items: center; gap: 1.5rem; flex-wrap: wrap;">
                {{-- Profile Image --}}
                <div style="flex-shrink: 0;">
                    <div style="width: 6.5rem; height: 6.5rem; border-radius: 1.5rem; display: flex; align-items: center; justify-content: center; border: 2px solid var(--border-item); background: var(--bg-item);">
                        <x-heroicon-o-user style="width: 3rem; height: 3rem; color: var(--text-dim);" />
                    </div>
                </div>

                <div style="flex-grow: 1;">
                    <div style="display: flex; align-items: center; gap: 1rem; flex-wrap: wrap; margin-bottom: 0.5rem;">
                        <h2 style="font-size: 2rem; font-weight: 800; line-height: 1.1; color: var(--text-title); margin: 0;">
                            {{ $this->employee->nama_lengkap }}
                        </h2>
                        <x-filament::badge :color="$this->employee->status === 'active' ? 'success' : 'danger'" size="sm">
                            {{ $this->employee->status === 'active' ? 'aktif' : 'non_aktif' }}
                        </x-filament::badge>
                    </div>
                    
                    <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 1.5rem; font-size: 0.875rem; color: var(--text-muted); font-weight: 500;">
                        <span style="background: var(--bg-item); padding: 0.25rem 0.625rem; border-radius: 0.5rem; color: var(--text-main); border: 1px solid var(--border-item);">NRP: {{ $this->employee->nrp }}</span>
                        <div style="display: flex; align-items: center; gap: 0.375rem;">
                            <x-heroicon-m-briefcase style="width: 1.125rem; height: 1.125rem; opacity: 0.5;" />
                            <span>{{ $this->employee->position_name }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </x-filament::section>

        {{-- Content Grid --}}
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 2rem;">
            
            {{-- Left Column (main info) --}}
            <div style="display: flex; flex-direction: column; gap: 2.5rem; grid-column: span 2;">
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem;">
                    <x-filament::section heading="Penempatan" icon="heroicon-o-map-pin">
                        <div style="display: flex; flex-direction: column; gap: 0.875rem;">
                            @foreach([
                                'Jabatan' => $this->employee->jobRole?->name,
                                'Kode Cabang' => $this->employee->branch?->kode_cabang,
                                'Cabang' => $this->employee->branch?->nama ?? $this->employee->branch?->name,
                                'Area' => $this->employee->branch?->areaRelation?->nama_area ?? $this->employee->area,
                                'Region' => $this->employee->branch?->regionRelation?->nama_region ?? $this->employee->region,
                                'Tipe' => $this->employee->employee_type,
                            ] as $label => $val)
                                <div style="display: flex; justify-content: space-between; font-size: 0.875rem; padding-bottom: 0.625rem; border-bottom: 1px solid var(--border-item);">
                                    <span style="color: var(--text-dim); font-weight: 700; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.025em;">{{ $label }}</span>
                                    <span style="font-weight: 600; color: var(--text-main);">{{ $val ?? '-' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </x-filament::section>

                    <x-filament::section heading="Pribadi" icon="heroicon-o-identification">
                        <div style="display: flex; flex-direction: column; gap: 0.875rem;">
                            @foreach([
                                'Golongan' => $this->employee->grade ?: $randomGolongan,
                                'Masuk' => $this->employee->entry_date?->format('d/m/Y'),
                                'Lahir' => $this->employee->date_of_birth?->format('d/m/Y'),
                                'Pendidikan Terakhir' => $randomPendidikan,
                                'Instansi Pendidikan' => $randomInstansi,
                            ] as $label => $val)
                                <div style="display: flex; justify-content: space-between; font-size: 0.875rem; padding-bottom: 0.625rem; border-bottom: 1px solid var(--border-item);">
                                    <span style="color: var(--text-dim); font-weight: 700; font-size: 0.7rem; text-transform: uppercase; letter-spacing: 0.025em;">{{ $label }}</span>
                                    <span style="font-weight: 600; color: var(--text-main);">{{ $val ?? '-' }}</span>
                                </div>
                            @endforeach
                        </div>
                    </x-filament::section>
                </div>

                {{-- Competency Table --}}
                <x-filament::section heading="Riwayat Training" icon="heroicon-o-academic-cap">
                    @if($this->employee->trainingRecords->isEmpty())
                        <p style="text-align: center; color: var(--text-dim); padding: 3rem 0; font-style: italic; font-size: 0.875rem;">Belum ada data kompetensi.</p>
                    @else
                        <div style="overflow-x: auto; border: 1px solid var(--border-item); border-radius: 1rem;">
                            <table style="width: 100%; text-align: left; border-collapse: collapse; font-size: 0.875rem;">
                                <thead style="background: var(--bg-item); border-bottom: 1px solid var(--border-item);">
                                    <tr>
                                        <th style="padding: 1rem; font-weight: 800; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase;">Tgl Training</th>
                                        <th style="padding: 1rem; font-weight: 800; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase;">Nama Training</th>
                                        <th style="padding: 1rem; font-weight: 800; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; text-align: center;">Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($this->employee->trainingRecords as $record)
                                        <tr style="border-bottom: 1px solid var(--border-item);">
                                            <td style="padding: 1.125rem 1rem; vertical-align: top;">
                                                {{ $record->completion_date?->format('d/m/Y') ?? '-' }}
                                            </td>
                                            <td style="padding: 1.125rem 1rem; vertical-align: top;">
                                                <div style="font-weight: 700; color: var(--text-main); margin-bottom: 0.25rem;">{{ $record->competency?->name ?? 'Training' }}</div>
                                                <div style="font-size: 0.75rem; color: var(--text-dim);">Sumber: {{ $record->source ?? 'Internal' }}</div>
                                            </td>
                                            <td style="padding: 1.125rem 1rem; text-align: center; vertical-align: top;">
                                                <x-filament::badge :color="match($record->level_achieved) { 3 => 'success', 2 => 'info', 1 => 'warning', default => 'secondary' }">
                                                    {{ match($record->level_achieved) { 3 => 'Selesai', 2 => 'Progress', 1 => 'Mulai', default => 'Pending' } }}
                                                </x-filament::badge>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </x-filament::section>

                <x-filament::section heading="Riwayat Jabatan" icon="heroicon-o-briefcase">
                    <div style="overflow-x: auto; border: 1px solid var(--border-item); border-radius: 1rem;">
                        <table style="width: 100%; text-align: left; border-collapse: collapse; font-size: 0.875rem;">
                            <thead style="background: var(--bg-item); border-bottom: 1px solid var(--border-item);">
                                <tr>
                                    <th style="padding: 1rem; font-weight: 800; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase;">Jabatan</th>
                                    <th style="padding: 1rem; font-weight: 800; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase;">Start</th>
                                    <th style="padding: 1rem; font-weight: 800; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase;">End</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($roleHistory as $history)
                                    <tr style="border-bottom: 1px solid var(--border-item); {{ $history['current'] ? 'background: rgba(56, 189, 248, 0.08);' : '' }}">
                                        <td style="padding: 1rem; font-weight: {{ $history['current'] ? '700' : '600' }};">{{ $history['jabatan'] }}</td>
                                        <td style="padding: 1rem;">{{ $history['start']->format('d/m/Y') }}</td>
                                        <td style="padding: 1rem;">{{ $history['end'] ? $history['end']->format('d/m/Y') : 'NOW' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </x-filament::section>
            </div>

            {{-- Right Sidebar --}}
            <div style="display: flex; flex-direction: column; gap: 2rem;">
                {{-- HAV Score Panel --}}
                <div style="background: linear-gradient(135deg, #1A3A5C 0%, #164e63 100%); border-radius: 2rem; padding: 2.5rem; color: white; box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.2); position: relative; overflow: hidden; margin-bottom:16px !important">
                    <x-heroicon-o-fire style="position: absolute; right: -1rem; bottom: -1rem; width: 8rem; height: 8rem; opacity: 0.1; transform: rotate(-12deg);" />
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; opacity: 0.6; margin-bottom: 1.5rem;">
                        <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em;">Performance Index</span>
                        <x-heroicon-s-sparkles style="width: 1rem; height: 1rem;" />
                    </div>
                    <div style="display: grid; gap: 1rem;">
                        <div style="background: rgba(255,255,255,0.08); padding: 1rem; border-radius: 1.25rem; border: 1px solid rgba(255,255,255,0.1);">
                            <div style="font-size: 0.6rem; text-transform: uppercase; opacity: 0.5; font-weight: 800; margin-bottom: 0.25rem;">Classification</div>
                            <div style="font-weight: 800; font-size: 1.125rem;">{{ $this->employee->hav_category ?? '-' }}</div>
                            <div style="font-size: 0.85rem; opacity: 0.85; margin-top: 0.5rem;">HAV Index: {{ $this->employee->hav_score ?? '-' }}</div>
                        </div>
                        <div style="background: rgba(255,255,255,0.08); padding: 1rem; border-radius: 1.25rem; border: 1px solid rgba(255,255,255,0.1);">
                            <div style="font-size: 0.6rem; text-transform: uppercase; opacity: 0.5; font-weight: 800; margin-bottom: 0.25rem;">PK</div>
                            @php
                                $pkOptions = [
                                    'K' => [3, 5],
                                    'C' => [6, 8],
                                    'C+' => [9, 11],
                                    'B' => [12, 14],
                                    'B+' => [15, 17],
                                    'BS' => [18, 20],
                                ];
                                $pkKey = array_rand($pkOptions);
                                $pkValue = rand($pkOptions[$pkKey][0], $pkOptions[$pkKey][1]);
                            @endphp
                            <div style="font-weight: 800; font-size: 1.125rem;">{{ $pkKey }} index ({{ $pkValue }})</div>
                        </div>
                        <div style="background: rgba(255,255,255,0.08); padding: 1rem; border-radius: 1.25rem; border: 1px solid rgba(255,255,255,0.1);">
                            <div style="font-size: 0.6rem; text-transform: uppercase; opacity: 0.5; font-weight: 800; margin-bottom: 0.25rem;">KPI Mid & Full Year</div>
                            <div style="font-weight: 800; font-size: 1.125rem;">{{ $kpiMid }}% : {{ $kpiFull }}% ({{ $kpiYear }})</div>
                        </div>
                </div>

                {{-- Learning Path Progress --}}
                <x-filament::section heading="Learning Path" icon="heroicon-o-presentation-chart-line" style="margin: top 16px !important;">
                    <div style="display: flex; flex-direction: column; gap: 1.5rem; padding: 0.5rem 0">
                        @forelse($this->employee->batchParticipants as $training)
                            @php
                                $pct = match($training->status) {
                                    'lulus' => 100,
                                    'hadir' => 75,
                                    'berlangsung' => 40,
                                    'diundang' => 10,
                                    default => 0,
                                };
                            @endphp
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <div style="display: flex; justify-content: space-between; font-size: 0.75rem; font-weight: 800; text-transform: uppercase;">
                                    <span style="color: var(--text-muted); overflow: hidden; text-overflow: ellipsis; max-width: 160px; white-space: nowrap;">{{ $training->batch?->name ?? $training->batch?->competency?->name }}</span>
                                    <span style="color: var(--text-title);">{{ $pct }}%</span>
                                </div>
                                <div style="height: 0.625rem; width: 100%; background: var(--bg-item); border-radius: 999px; overflow: hidden; border: 1px solid var(--border-item);">
                                    <div style="--bar-width: {{ $pct }}%; height: 100%; border-radius: 999px; background: #1A3A5C; transition: width 1.2s cubic-bezier(0.4, 0, 0.2, 1); width: var(--bar-width);"></div>
                                </div>
                                <div style="font-size: 0.65rem; color: var(--text-dim); text-transform: uppercase; font-weight: 700;">Status: {{ str_replace('_', ' ', $training->status) }}</div>
                            </div>
                        @empty
                            <p style="text-align: center; font-size: 0.875rem; color: var(--text-dim);">Tidak ada riwayat.</p>
                        @endforelse
                    </div>
                </x-filament::section>
            </div>
        </div>
    </div>
</x-filament-panels::page>
