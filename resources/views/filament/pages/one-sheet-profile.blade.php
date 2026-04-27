<x-filament-panels::page>
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
                                'Cabang' => $this->employee->branch?->nama ?? $this->employee->branch?->name,
                                'Area' => $this->employee->branch?->areaRelation?->nama_area ?? $this->employee->area,
                                'Region' => $this->employee->branch?->regionRelation?->nama_region ?? $this->employee->region,
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
                                'Tipe' => $this->employee->employee_type,
                                'Grade' => $this->employee->grade,
                                'Masuk' => $this->employee->entry_date?->format('d/m/Y'),
                                'Lahir' => $this->employee->date_of_birth?->format('d/m/Y'),
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
                <x-filament::section heading="Riwayat Materi & Kompetensi" icon="heroicon-o-academic-cap">
                    @if($this->employee->trainingRecords->isEmpty())
                        <p style="text-align: center; color: var(--text-dim); padding: 3rem 0; font-style: italic; font-size: 0.875rem;">Belum ada data kompetensi.</p>
                    @else
                        <div style="overflow-x: auto; border: 1px solid var(--border-item); border-radius: 1rem;">
                            <table style="width: 100%; text-align: left; border-collapse: collapse; font-size: 0.875rem;">
                                <thead style="background: var(--bg-item); border-bottom: 1px solid var(--border-item);">
                                    <tr>
                                        <th style="padding: 1rem; font-weight: 800; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase;">Materi</th>
                                        <th style="padding: 1rem; font-weight: 800; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; text-align: center;">Pencapaian</th>
                                        <th style="padding: 1rem; font-weight: 800; color: var(--text-muted); font-size: 0.75rem; text-transform: uppercase; text-align: right;">Berlaku S/D</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($this->employee->trainingRecords as $record)
                                        <tr style="border-bottom: 1px solid var(--border-item);">
                                            <td style="padding: 1.125rem 1rem;">
                                                <div style="font-weight: 750; color: var(--text-main); margin-bottom: 0.25rem;">{{ $record->competencyTrack?->name }}</div>
                                                <div style="font-size: 0.75rem; color: var(--text-dim);">Tgl: {{ $record->completion_date?->format('d/m/Y') }}</div>
                                            </td>
                                            <td style="padding: 1rem; text-align: center;">
                                                <x-filament::badge :color="match($record->level_achieved) { 3 => 'success', 2 => 'info', 1 => 'warning', default => 'gray' }">
                                                    {{ match($record->level_achieved) { 3 => 'Certified', 2 => 'Level 2', 1 => 'Level 1', default => 'N/A' } }}
                                                </x-filament::badge>
                                            </td>
                                            <td style="padding: 1rem; text-align: right; font-weight: 700; color: var(--text-muted);">
                                                {{ $record->certification_expiry?->format('d/m/Y') ?? 'Permanent' }}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @endif
                </x-filament::section>
            </div>

            {{-- Right Sidebar --}}
            <div style="display: flex; flex-direction: column; gap: 2rem;">
                {{-- HAV Score Panel --}}
                <div style="background: linear-gradient(135deg, #1A3A5C 0%, #164e63 100%); border-radius: 2rem; padding: 2.5rem; color: white; box-shadow: 0 15px 30px -5px rgba(0, 0, 0, 0.2); position: relative; overflow: hidden;">
                    <x-heroicon-o-fire style="position: absolute; right: -1rem; bottom: -1rem; width: 8rem; height: 8rem; opacity: 0.1; transform: rotate(-12deg);" />
                    
                    <div style="display: flex; justify-content: space-between; align-items: center; opacity: 0.6; margin-bottom: 1.5rem;">
                        <span style="font-size: 0.7rem; font-weight: 800; text-transform: uppercase; letter-spacing: 0.1em;">HAV Index</span>
                        <x-heroicon-s-sparkles style="width: 1rem; height: 1rem;" />
                    </div>
                    <div style="display: flex; align-items: baseline; gap: 0.5rem; margin-bottom: 2.5rem;">
                        <span style="font-size: 4.5rem; font-weight: 900; line-height: 0.8;">{{ $this->employee->hav_score ?? '-' }}</span>
                        <span style="font-size: 0.75rem; font-weight: 800; opacity: 0.5; text-transform: uppercase;">Points</span>
                    </div>
                    
                    <div style="display: grid; gap: 1rem;">
                        <div style="background: rgba(255,255,255,0.08); padding: 1rem; border-radius: 1.25rem; border: 1px solid rgba(255,255,255,0.1);">
                            <div style="font-size: 0.6rem; text-transform: uppercase; opacity: 0.5; font-weight: 800; margin-bottom: 0.25rem;">Classification</div>
                            <div style="font-weight: 800; font-size: 1.125rem;">{{ $this->employee->hav_category ?? '-' }}</div>
                        </div>
                        <div style="background: rgba(255,255,255,0.08); padding: 1rem; border-radius: 1.25rem; border: 1px solid rgba(255,255,255,0.1);">
                            <div style="font-size: 0.6rem; text-transform: uppercase; opacity: 0.5; font-weight: 800; margin-bottom: 0.25rem;">iTalent Status</div>
                            <div style="font-weight: 800; font-size: 1.125rem;">{{ $this->employee->italent_user ?? '-' }}</div>
                        </div>
                    </div>
                </div>

                {{-- Digital Learning Progress --}}
                <x-filament::section heading="Digital Learning" icon="heroicon-o-presentation-chart-line">
                    <div style="display: flex; flex-direction: column; gap: 1.5rem; padding: 0.5rem 0;">
                        @forelse($this->employee->enrollments as $enrollment)
                            <div style="display: flex; flex-direction: column; gap: 0.5rem;">
                                <div style="display: flex; justify-content: space-between; font-size: 0.75rem; font-weight: 800; text-transform: uppercase;">
                                    <span style="color: var(--text-muted); overflow: hidden; text-overflow: ellipsis; max-width: 160px; white-space: nowrap;">{{ $enrollment->curriculum?->title }}</span>
                                    <span style="color: var(--text-title);">{{ $enrollment->progress_pct }}%</span>
                                </div>
                                <div style="height: 0.625rem; width: 100%; background: var(--bg-item); border-radius: 999px; overflow: hidden; border: 1px solid var(--border-item);">
                                    <div style="height: 100%; border-radius: 999px; background: #1A3A5C; transition: width 1.2s cubic-bezier(0.4, 0, 0.2, 1); width: {{ $enrollment->progress_pct }}%;"></div>
                                </div>
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
