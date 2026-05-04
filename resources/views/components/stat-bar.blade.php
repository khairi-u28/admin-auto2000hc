@props(['label', 'pct', 'color' => '#185FA5', 'showPct' => true])
<div style="display:flex;align-items:center;gap:8px;margin-bottom:6px">
  <div style="font-size:11px;color:var(--color-text-secondary,#6b7280);width:120px;flex-shrink:0;text-align:right;overflow:hidden;text-overflow:ellipsis;white-space:nowrap" title="{{ $label }}">
    {{ $label }}
  </div>
  <div style="flex:1;background:var(--color-background-secondary,#f9fafb);border-radius:4px;height:8px;overflow:hidden">
    <div style="height:100%;border-radius:4px;background:{{ $color }};width:{{ min(100, max(0, $pct)) }}%"></div>
  </div>
  @if($showPct)
    <span style="font-size:11px;font-weight:500;width:32px;text-align:right;color:{{ $color }}">{{ $pct }}%</span>
  @endif
</div>

