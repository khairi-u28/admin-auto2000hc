@php
    $record = $records->first();
    if (!$record) return;

    // Data calculation
    $target = (int) ($record->target_participants ?? 0);
    $actual = (int) ($record->actual_participants_count ?? 0);
    $hadirPct = $target > 0 ? round(($actual / $target) * 100, 1) : 0;

    $lulus = $record->participants()->where('status', 'lulus')->count();
    $tidakLulus = $record->participants()->where('status', 'tidak_lulus')->count();
    $evaluated = $lulus + $tidakLulus;
    $lulusPct = $evaluated > 0 ? round(($lulus / $evaluated) * 100, 1) : 0;

    $ratingBatch = $record->feedback()
        ->where('is_submitted', true)
        ->selectRaw('AVG((training_relevance + training_material_quality + training_schedule + training_facility) / 4) as avg_score')
        ->value('avg_score') ?? 0;

    $ratingTrainer = $record->feedback()
        ->where('is_submitted', true)
        ->selectRaw('AVG((trainer_mastery + trainer_delivery + trainer_responsiveness + trainer_attitude) / 4) as avg_score')
        ->value('avg_score') ?? 0;

    $trainerComments = $record->feedback()
        ->whereNotNull('trainer_comments')
        ->where('trainer_comments', '!=', '')
        ->latest('submitted_at')
        ->value('trainer_comments') ?? 'Belum ada catatan evaluasi dari trainer.';
@endphp

<style>
.bento-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-template-rows: repeat(2, auto);
    gap: 1rem;
    padding: 1rem;
}
.bento-card {
    background: var(--color-background-primary,#fff);
    border: 0.5px solid var(--color-border-tertiary,#e5e7eb);
    border-radius: 1rem;
    padding: 1.5rem;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
}
.bento-card-title {
    font-size: 0.75rem;
    font-weight: 600;
    color: var(--color-text-secondary,#6b7280);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    margin-bottom: 1rem;
}
.bento-card-value {
    font-size: 2rem;
    font-weight: 700;
    color: var(--color-text-primary,#111);
}
.bento-card-sub {
    font-size: 0.875rem;
    color: var(--color-text-secondary,#6b7280);
    margin-top: 0.25rem;
}
.card-large {
    grid-column: span 2;
    grid-row: span 2;
}
.card-wide {
    grid-column: span 2;
}
.progress-bar {
    height: 8px;
    width: 100%;
    background: #f3f4f6;
    border-radius: 4px;
    margin-top: 1rem;
    overflow: hidden;
}
.progress-fill {
    height: 100%;
    border-radius: 4px;
    transition: width 1s ease-in-out;
}
.comment-text {
    font-size: 0.9375rem;
    line-height: 1.6;
    color: var(--color-text-primary,#374151);
    white-space: pre-line;
}
</style>

<div class="bento-grid">
    {{-- Card 1: Kehadiran --}}
    <div class="bento-card">
        <div class="bento-card-title">Kehadiran</div>
        <div class="bento-card-value" style="color: #185FA5">{{ $hadirPct }}%</div>
        <div class="bento-card-sub">{{ $actual }} / {{ $target }} Peserta</div>
        <div class="progress-bar">
            <div class="progress-fill" style="width: {{ $hadirPct }}%; background: #185FA5;"></div>
        </div>
    </div>

    {{-- Card 2: Kelulusan --}}
    <div class="bento-card">
        <div class="bento-card-title">Kelulusan</div>
        <div class="bento-card-value" style="color: #0F6E56">{{ $lulusPct }}%</div>
        <div class="bento-card-sub">{{ $lulus }} / {{ $evaluated }} Lulus</div>
        <div class="progress-bar">
            <div class="progress-fill" style="width: {{ $lulusPct }}%; background: #0F6E56;"></div>
        </div>
    </div>

    {{-- Card 3: Trainer Evaluation (Large) --}}
    <div class="bento-card card-large">
        <div class="bento-card-title">Evaluasi Trainer</div>
        <div class="comment-text">
            {{ $trainerComments }}
        </div>
    </div>

    {{-- Card 4: Rating Batch --}}
    <div class="bento-card">
        <div class="bento-card-title">Rating Batch</div>
        <div class="bento-card-value" style="color: #534AB7">{{ number_format($ratingBatch, 1) }}</div>
        <div class="bento-card-sub">Skala 5.0</div>
        <div style="display: flex; gap: 2px; margin-top: 0.5rem;">
            @for($i=1; $i<=5; $i++)
                <svg style="width: 1.25rem; height: 1.25rem; color: {{ $i <= round($ratingBatch) ? '#F59E0B' : '#E5E7EB' }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
            @endfor
        </div>
    </div>

    {{-- Card 5: Rating Trainer --}}
    <div class="bento-card">
        <div class="bento-card-title">Rating Trainer</div>
        <div class="bento-card-value" style="color: #854F0B">{{ number_format($ratingTrainer, 1) }}</div>
        <div class="bento-card-sub">Skala 5.0</div>
        <div style="display: flex; gap: 2px; margin-top: 0.5rem;">
            @for($i=1; $i<=5; $i++)
                <svg style="width: 1.25rem; height: 1.25rem; color: {{ $i <= round($ratingTrainer) ? '#F59E0B' : '#E5E7EB' }}" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                </svg>
            @endfor
        </div>
    </div>
</div>
