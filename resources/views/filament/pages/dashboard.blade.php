<x-filament-panels::page>
  <style>
    :root {
      --hc-bg: #f8f9fb;
      --hc-card: #ffffff;
      --hc-border: #e8eaed;
      --hc-text: #1a1d23;
      --hc-muted: #6b7280;
      --hc-accent: #2563eb;
      --hc-green: #059669;
      --hc-red: #dc2626;
      --hc-amber: #d97706;
      --hc-purple: #7c3aed
    }

    .hc-page {
      max-width: 1400px;
      margin: 0 auto;
      display: flex;
      flex-direction: column;
      gap: 24px
    }

    .hc-section-head {
      margin-bottom: 16px
    }

    .hc-section-title {
      font-size: 15px;
      font-weight: 600;
      color: var(--hc-text);
      letter-spacing: -0.2px
    }

    .hc-section-sub {
      font-size: 12px;
      color: var(--hc-muted);
      margin-top: 2px
    }

    .hc-card {
      background: var(--hc-card);
      border: 1px solid var(--hc-border);
      border-radius: 14px;
      padding: 18px 20px;
      transition: box-shadow .2s
    }

    .hc-card:hover {
      box-shadow: 0 2px 12px rgba(0, 0, 0, .04)
    }

    /* KPI Row */
    .hc-kpi-grid {
      display: grid;
      grid-template-columns: repeat(4, minmax(180px, 1fr));
      gap: 14px
    }

    .number-and-growth {
      margin: 4px 0;
      display: flex;
      flex-direction: row;
      gap: 4px;
      align-items: end;
    }

    .hc-kpi {
      display: flex;
      align-items: center;
      gap: 16px;
      padding: 16px;
      min-height: 110px;
    }

    .hc-kpi-icon {
      width: 48px;
      height: 48px;
      border-radius: 12px;
      display: inline-flex;
      align-items: center;
      justify-content: center;
      font-size: 20px;
      flex-shrink: 0;
    }

    .hc-kpi-content {
      display: flex;
      flex-direction: column;
      flex: 1;
    }

    .hc-kpi-val {
      font-size: 24px;
      font-weight: 700;
      line-height: 1.2;
      letter-spacing: -0.5px
    }

    .hc-kpi-label {
      font-size: 11px;
      color: var(--hc-muted);
      text-transform: uppercase;
      letter-spacing: .5px;
      margin-bottom: 2px;
    }

    .hc-kpi-trend {
      font-size: 11px;
      margin-top: 6px;
      font-weight: 500;
      display: inline-flex;
      align-items: center;
      gap: 3px;
      padding: 2px 8px;
      border-radius: 20px
    }

    /* Insights Grid */
    .hc-insights-grid {
      display: grid;
      grid-template-columns: minmax(0, 2.2fr) minmax(340px, 0.8fr);
      gap: 16px;
      align-items: stretch
    }

    /* Heatmap */
    .hc-hm-wrap {
      overflow-x: auto;
      -webkit-overflow-scrolling: touch
    }

    .hc-hm {
      width: 100%;
      border-collapse: separate;
      border-spacing: 3px;
      font-size: 12px
    }

    .hc-hm th {
      padding: 7px 8px;
      font-size: 10px;
      font-weight: 600;
      color: var(--hc-muted);
      text-transform: uppercase;
      letter-spacing: .5px;
      text-align: center;
      white-space: nowrap
    }

    .hc-hm th:first-child {
      text-align: left
    }

    .hc-hm td {
      padding: 7px 8px;
      text-align: center;
      border-radius: 8px;
      font-weight: 600;
      font-size: 12px;
      min-width: 58px
    }

    .hc-hm td:first-child {
      text-align: left;
      font-weight: 500;
      color: var(--hc-text);
      background: transparent !important;
      min-width: 100px;
      white-space: nowrap
    }

    /* Chart */
    .hc-chart-area {
      position: relative;
      height: 170px;
      margin: 12px 0 4px
    }

    .hc-chart-svg {
      width: 100%;
      height: 100%
    }

    .hc-chart-legend {
      display: flex;
      gap: 16px;
      justify-content: center;
      margin-top: 4px
    }

    .hc-chart-legend span {
      font-size: 11px;
      display: flex;
      align-items: center;
      gap: 5px
    }

    .hc-chart-dot {
      width: 8px;
      height: 8px;
      border-radius: 50%;
      flex-shrink: 0
    }

    .hc-insight-note {
      background: #ecfdf5;
      border: 1px solid #a7f3d0;
      border-radius: 10px;
      padding: 10px 14px;
      font-size: 12px;
      color: #065f46;
      margin-top: 12px;
      line-height: 1.5
    }

    /* Risk Cards */
    .hc-risk-grid {
      display: grid;
      grid-template-columns: repeat(5, 1fr);
      gap: 12px
    }

    .hc-risk {
      border-radius: 14px;
      padding: 14px 12px;
      text-align: left;
      min-height: 110px;
      display: flex;
      flex-direction: column;
      justify-content: space-between
    }

    .hc-risk-icon {
      font-size: 16px;
      margin-bottom: 6px
    }

    .hc-risk-val {
      font-size: 24px;
      font-weight: 700;
      line-height: 1
    }

    .hc-risk-label {
      font-size: 11px;
      color: var(--hc-muted);
      margin-top: 6px;
      line-height: 1.3
    }

    /* Management Attention */
    .hc-mgmt-grid {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 16px
    }

    .hc-mgmt-grid .hc-card {
      padding: 16px
    }

    .hc-mgmt-list {
      list-style: none;
      padding: 0;
      margin: 0
    }

    .hc-mgmt-list li {
      padding: 8px 10px;
      border-radius: 10px;
      margin-bottom: 5px;
      font-size: 12.5px;
      line-height: 1.5;
      display: flex;
      align-items: flex-start;
      gap: 8px
    }

    .hc-mgmt-list li::before {
      content: '';
      width: 6px;
      height: 6px;
      border-radius: 50%;
      flex-shrink: 0;
      margin-top: 6px
    }

    .hc-mgmt-risk li {
      background: #fef2f2;
      color: #991b1b
    }

    .hc-mgmt-risk li::before {
      background: #dc2626
    }

    .hc-mgmt-pos li {
      background: #f0fdf4;
      color: #166534
    }

    .hc-mgmt-pos li::before {
      background: #059669
    }

    /* Responsive */
    @media(max-width:1024px) {
      .hc-kpi-grid {
        grid-template-columns: repeat(3, 1fr)
      }

      .hc-insights-grid,
      .hc-mgmt-grid {
        grid-template-columns: 1fr
      }

      .hc-risk-grid {
        grid-template-columns: repeat(3, 1fr)
      }
    }

    @media(max-width:640px) {
      .hc-kpi-grid {
        grid-template-columns: 1fr
      }

      .hc-risk-grid {
        grid-template-columns: 1fr
      }

      .hc-insights-grid,
      .hc-mgmt-grid {
        grid-template-columns: 1fr
      }

      .hc-card {
        padding: 14px
      }

      .hc-chart-area {
        height: 160px
      }

      .hc-kpi-val {
        font-size: 22px
      }

      .hc-risk-val {
        font-size: 20px
      }
    }

    /* Dark mode support */
    .dark .hc-card {
      background: var(--color-background-primary, #1f2937);
      border-color: var(--color-border-tertiary, #374151)
    }

    .dark .hc-section-title {
      color: #f3f4f6
    }

    .dark .hc-hm td:first-child {
      color: #e5e7eb
    }

    .dark .hc-insight-note {
      background: #064e3b;
      border-color: #065f46;
      color: #a7f3d0
    }

    .dark .hc-mgmt-risk li {
      background: #450a0a;
      color: #fca5a5
    }

    .dark .hc-mgmt-pos li {
      background: #052e16;
      color: #86efac
    }
  </style>

  <div class="hc-page">

    {{-- ══════════════════════════════════════════════════════════════
    SECTION 1 — ORGANIZATIONAL OVERVIEW
    ══════════════════════════════════════════════════════════════ --}}
    <div>
      <div class="hc-section-head">
        <div class="hc-section-title">Organizational Overview</div>
        <div class="hc-section-sub">Kondisi utama organisasi saat ini</div>
      </div>

      <div class="hc-kpi-grid">
        {{-- KPI 1: Total Karyawan Aktif --}}
        <div class="hc-card hc-kpi">
          <div class="hc-kpi-icon" style="background:#eff6ff;color:#2563eb">👥</div>
          <div class="hc-kpi-content">
              <div class="hc-kpi-label">Total Karyawan Aktif</div>
              <div class="number-and-growth">
                <div class="hc-kpi-val" style="color:#2563eb">{{ number_format($totalKaryawanAktif) }}</div>
                <div class="hc-kpi-trend" style="background:#ecfdf5;color:#059669">+32 orang</div>
              </div>
              <div style="font-size:11px;color:#2b2b2b">vs tahun 2024</div>
          </div>
        </div>

        {{-- KPI 2: Indeks Kapabilitas --}}
        <div class="hc-card hc-kpi">
          <div class="hc-kpi-icon" style="background:#f0fdf4;color:#059669">📊</div>
          <div class="hc-kpi-content">
              <div class="hc-kpi-label">Indeks Kapabilitas</div>
              <div class="number-and-growth">
                <div class="hc-kpi-val" style="color:#059669">{{ $capabilityIndex }}%</div>
                <div class="hc-kpi-trend" style="font-weight:bold;background:#ecfdf5;color:#059669">+5.1%</div>
              </div>
              <div style="font-size:11px;color:#2b2b2b">vs tahun 2024</div>
          </div>
        </div>

        {{-- KPI 3: Rata-rata KPI --}}
        <div class="hc-card hc-kpi">
          <div class="hc-kpi-icon" style="background:#fefce8;color:#d97706">🎯</div>
          <div class="hc-kpi-content">
              <div class="hc-kpi-label">Rata-rata KPI</div>
              <div class="number-and-growth">
                <div class="hc-kpi-val" style="color:#d97706">{{ $avgKpi }}%</div>
                <small class="hc-kpi-trend" style="font-weight:bold;background:#ecfdf5;color:#059669">+2.8%</small>
                <!-- <small style="font-weight:bold;color:#059669">↑ +2.8%</small> -->
              </div>
              <div style="font-size:11px;color:#2b2b2b">vs tahun 2024</div>
          </div>
        </div>

        {{-- KPI 4: Siap Promosi --}}
        <div class="hc-card hc-kpi">
          <div class="hc-kpi-icon" style="background:#faf5ff;color:#7c3aed">🚀</div>
          <div class="hc-kpi-content">
              <div class="hc-kpi-label">Karyawan Eligible</div>
              <div class="number-and-growth">
                <!-- <div class="hc-kpi-val" style="color:#7c3aed">{{ $promosiSiap }}%</div> -->
                <div class="hc-kpi-val" style="color:#7c3aed">76%</div>
                <!-- <div class="hc-kpi-trend" style="background:#faf5ff;color:#7c3aed">{{ $promosiCount }} orang</div> -->
                 <small class="hc-kpi-trend" style="font-weight:bold;background:#fee2e2;color:#991b1b">-12.5%</small>
              </div>
              <div style="font-size:11px;color:#2b2b2b">vs tahun 2024</div>
          </div>
        </div>

        {{-- KPI 5: Budaya Perusahaan --}}
        <!-- <div class="hc-card hc-kpi">
          <div class="hc-kpi-icon" style="background:#fdf2f8;color:#db2777">💡</div>
          <div class="hc-kpi-content">
              <div class="hc-kpi-label">Budaya Perusahaan</div>
              <div class="hc-kpi-val" style="color:#db2777">{{ $budayaIndex }}%</div>
              <div class="hc-kpi-trend" style="background:#ecfdf5;color:#059669">↑ stabil</div>
          </div>
        </div> -->
      </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
    SECTION 2 — WORKFORCE CAPABILITY INSIGHTS
    ══════════════════════════════════════════════════════════════ --}}
    <div>
      <div class="hc-section-head">
        <div class="hc-section-title">Workforce Capability Insights</div>
        <div class="hc-section-sub">Kapabilitas organisasi & dampaknya terhadap kinerja</div>
      </div>

      <div class="hc-insights-grid">
        {{-- LEFT: Heatmap --}}
        <div class="hc-card">
          <div style="font-size:13px;font-weight:600;color:var(--hc-text);margin-bottom:4px">Peta Kapabilitas</div>
          <div style="font-size:11px;color:var(--hc-muted);margin-bottom:14px">Rata-rata Fulfillment per Kompetensi
          </div>
          <div class="hc-hm-wrap">
            <table class="hc-hm">
              <thead>
                <tr>
                  <th>Departemen / Kompetensi</th>
                  @foreach($heatmapCompetencies as $comp)
                    <th title="{{ $comp }}">{{ \Illuminate\Support\Str::limit($comp, 12) }}</th>
                  @endforeach
                </tr>
              </thead>
              <tbody>
                @foreach($heatmapData as $row)
                  <tr>
                    <td>{{ $row['dept'] }}</td>
                    @foreach($row['cells'] as $cell)
                      @php
                        $pct = $cell['pct'] ?? 0;
                        if ($pct === null) {
                          $bg = '#f3f4f6';
                          $fg = '#9ca3af';
                        } elseif ($pct >= 70) {
                          $bg = '#dcfce7';
                          $fg = '#166534';
                        } elseif ($pct >= 45) {
                          $bg = '#fef9c3';
                          $fg = '#854d0e';
                        } else {
                          $bg = '#fee2e2';
                          $fg = '#991b1b';
                        }
                      @endphp
                      <td style="background:{{ $bg }};color:{{ $fg }}">{{ $pct !== null ? $pct . '%' : '-' }}</td>
                    @endforeach
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>

        {{-- RIGHT: Line Chart --}}
        <div class="hc-card">
          <div style="font-size:13px;font-weight:600;color:var(--hc-text);margin-bottom:4px">Korelasi Learning vs KPI
          </div>
          <div style="font-size:11px;color:var(--hc-muted);margin-bottom:8px">Tingkat penyelesaian learning vs
            pencapaian KPI</div>

          @php
            $months = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'];
            $maxVal = 100;
            $chartW = 100;
            $chartH = 200;
            $padL = 30;
            $padB = 24;
            $padT = 10;
            $padR = 10;
            $plotW = $chartW - $padL - $padR; // percentages not used, using viewBox
            $vbW = 400;
            $vbH = 180;
            $pL = 40;
            $pR = 20;
            $pT = 15;
            $pB = 30;
            $areaW = $vbW - $pL - $pR;
            $areaH = $vbH - $pT - $pB;
            $stepX = count($learningVsKpi) > 1 ? $areaW / (count($learningVsKpi) - 1) : $areaW;
          @endphp

          <div class="hc-chart-area">
            <svg class="hc-chart-svg" viewBox="0 0 {{ $vbW }} {{ $vbH }}" preserveAspectRatio="none">
              {{-- Grid lines --}}
              @for($i = 0; $i <= 4; $i++)
                @php $y = $pT + ($areaH / 4) * $i;
                $label = 100 - ($i * 25); @endphp
                <line x1="{{ $pL }}" y1="{{ $y }}" x2="{{ $vbW - $pR }}" y2="{{ $y }}" stroke="#e5e7eb" stroke-width="0.5"
                  stroke-dasharray="4" />
                <text x="{{ $pL - 6 }}" y="{{ $y + 3 }}" text-anchor="end" font-size="9"
                  fill="#9ca3af">{{ $label }}</text>
              @endfor

              {{-- Month labels --}}
              @foreach($learningVsKpi as $idx => $d)
                @php $x = $pL + $idx * $stepX; @endphp
                <text x="{{ $x }}" y="{{ $vbH - 6 }}" text-anchor="middle" font-size="9"
                  fill="#9ca3af">{{ $months[$idx] ?? '' }}</text>
              @endforeach

              {{-- Learning line --}}
              @php
                $lPoints = [];
                $kPoints = [];
                foreach ($learningVsKpi as $idx => $d) {
                  $x = $pL + $idx * $stepX;
                  $lY = $pT + $areaH - ($d['learning'] / $maxVal * $areaH);
                  $kY = $pT + $areaH - ($d['kpi'] / $maxVal * $areaH);
                  $lPoints[] = round($x, 1) . ',' . round($lY, 1);
                  $kPoints[] = round($x, 1) . ',' . round($kY, 1);
                }
              @endphp
              <polyline points="{{ implode(' ', $lPoints) }}" fill="none" stroke="#2563eb" stroke-width="2.5"
                stroke-linecap="round" stroke-linejoin="round" />
              <polyline points="{{ implode(' ', $kPoints) }}" fill="none" stroke="#059669" stroke-width="2.5"
                stroke-linecap="round" stroke-linejoin="round" />

              {{-- Dots --}}
              @foreach($learningVsKpi as $idx => $d)
                @php
                  $x = $pL + $idx * $stepX;
                  $lY = $pT + $areaH - ($d['learning'] / $maxVal * $areaH);
                  $kY = $pT + $areaH - ($d['kpi'] / $maxVal * $areaH);
                @endphp
                <circle cx="{{ round($x, 1) }}" cy="{{ round($lY, 1) }}" r="3" fill="#2563eb" />
                <circle cx="{{ round($x, 1) }}" cy="{{ round($kY, 1) }}" r="3" fill="#059669" />
              @endforeach
            </svg>
          </div>

          <div class="hc-chart-legend">
            <span><span class="hc-chart-dot" style="background:#2563eb"></span> Penyelesaian Learning (%)</span>
            <span><span class="hc-chart-dot" style="background:#059669"></span> Pencapaian KPI (%)</span>
          </div>

          <div class="hc-insight-note">
            ✅ Terdapat korelasi positif antara penyelesaian learning dan pencapaian KPI.
          </div>
        </div>
      </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
    SECTION 3 — INDIKATOR RISIKO
    ══════════════════════════════════════════════════════════════ --}}
    <div>
      <div class="hc-section-head">
        <div class="hc-section-title">Indikator Risiko</div>
        <div class="hc-section-sub">Area yang memerlukan perhatian segera</div>
      </div>

      <div class="hc-risk-grid">
        @php
          $risks = [
            ['icon' => '⚠️', 'val' => $riskKompetensiKritis, 'label' => 'Kompetensi Kritis Rendah', 'sub' => 'karyawan tanpa sertifikasi', 'bg' => '#fef2f2', 'fg' => '#dc2626'],
            ['icon' => '📉', 'val' => $riskKpiBawahTarget, 'label' => 'KPI di Bawah Target', 'sub' => 'karyawan di bawah standar', 'bg' => '#fffbeb', 'fg' => '#d97706'],
            ['icon' => '⏰', 'val' => $riskLpOverdue, 'label' => 'Learning Path Overdue', 'sub' => 'program terlambat', 'bg' => '#fef2f2', 'fg' => '#dc2626'],
            ['icon' => '💤', 'val' => $riskEngagementRendah, 'label' => 'Engagement Rendah', 'sub' => 'tanpa aktivitas 90 hari', 'bg' => '#fefce8', 'fg' => '#d97706'],
            ['icon' => '🔗', 'val' => $riskKolaborasiRendah, 'label' => 'Eligible Rate Rendah', 'sub' => 'competency fulfillment <30%', 'bg' => '#faf5ff', 'fg' => '#7c3aed'],
          ];
        @endphp

        @foreach($risks as $risk)
          <div class="hc-card hc-risk" style="background:{{ $risk['bg'] }}">
            <div class="hc-risk-icon">{{ $risk['icon'] }}</div>
            <div class="hc-risk-val" style="color:{{ $risk['fg'] }}">{{ $risk['val'] }}</div>
            <div class="hc-risk-label" style="font-weight:bold">{{ $risk['label'] }}</div>
            <div style="font-size:10px;color:var(--hc-muted);margin-top:4px">{{ $risk['sub'] }}</div>
          </div>
        @endforeach
      </div>
    </div>

    {{-- ══════════════════════════════════════════════════════════════
    SECTION 4 — MANAGEMENT ATTENTION
    ══════════════════════════════════════════════════════════════ --}}
    <div>
      <div class="hc-section-head">
        <div class="hc-section-title">Management Attention</div>
        <div class="hc-section-sub">Ringkasan hal penting untuk pengambilan keputusan</div>
      </div>

      <div class="hc-mgmt-grid">
        {{-- Left: Prioritas Risiko --}}
        <div class="hc-card">
          <div
            style="font-size:13px;font-weight:600;color:#dc2626;margin-bottom:12px;display:flex;align-items:center;gap:6px">
            <span style="font-size:16px">🔴</span> Prioritas Risiko
          </div>
          <ul class="hc-mgmt-list hc-mgmt-risk">
            @foreach($prioritasRisiko as $item)
              <li>{{ $item }}</li>
            @endforeach
          </ul>
        </div>

        {{-- Right: Hal Positif --}}
        <div class="hc-card">
          <div
            style="font-size:13px;font-weight:600;color:#059669;margin-bottom:12px;display:flex;align-items:center;gap:6px">
            <span style="font-size:16px">🟢</span>Insight
          </div>
          <ul class="hc-mgmt-list hc-mgmt-pos">
            @foreach($halPositif as $item)
              <li>{{ $item }}</li>
            @endforeach
          </ul>
        </div>
      </div>
    </div>

  </div>
</x-filament-panels::page>