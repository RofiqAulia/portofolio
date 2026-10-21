@php
    $totalTiers = $tiers->count();
    $svgW = 620;
    $svgH = 320;
    $centerX = 265;
    $peakY = 20;
    $baseY = 295;
    $usableH = $baseY - $peakY;
    $baseHalfW = 120;
    $sliceH = $totalTiers > 0 ? ($usableH / $totalTiers) : 0;
@endphp

@if($tiers->isEmpty())
    <div class="text-center py-5 my-auto">
        <i class="bi bi-diagram-3 text-muted display-4 mb-2 opacity-50"></i>
        <p class="text-muted small mb-0">Belum ada data kompetensi yang diterbitkan.</p>
    </div>
@else
    <div class="pyramid-wrapper d-flex flex-column h-100 justify-content-center align-items-center py-2">
        <!-- SVG Pyramid Diagram dengan Keterangan Keahlian Dinamis -->
        <div class="pyramid-svg-container position-relative text-center w-100 my-auto">
            <svg viewBox="0 0 {{ $svgW }} {{ $svgH }}" class="w-100 pyramid-svg" style="max-height: 380px;" preserveAspectRatio="xMidYMid meet" role="img" aria-label="Diagram Piramida Keahlian">
                <defs>
                    <filter id="pyrShadow-{{ $type }}" x="-10%" y="-10%" width="120%" height="130%">
                        <feDropShadow dx="0" dy="3" stdDeviation="3.5" flood-color="#0A1B3D" flood-opacity="0.16" />
                    </filter>
                    <filter id="pyrPillShadow-{{ $type }}" x="-10%" y="-20%" width="120%" height="150%">
                        <feDropShadow dx="0" dy="1.5" stdDeviation="2" flood-color="#0A1B3D" flood-opacity="0.08" />
                    </filter>
                    @foreach($tiers as $tier)
                        @php
                            $i = $tier['index'];
                            $c = $tier['color'];
                        @endphp
                        <linearGradient id="pyrGrad-{{ $type }}-{{ $i }}" x1="0%" y1="0%" x2="100%" y2="100%">
                            <stop offset="0%" stop-color="{{ $c['from'] }}" />
                            <stop offset="100%" stop-color="{{ $c['to'] }}" />
                        </linearGradient>
                    @endforeach
                </defs>

                @foreach($tiers as $tier)
                    @php
                        $i = $tier['index'];
                        $c = $tier['color'];
                        $y1 = $peakY + ($i * $sliceH) + ($i > 0 ? 3 : 0);
                        $y2 = $peakY + (($i + 1) * $sliceH) - 3;
                        $w1 = (($y1 - $peakY) / $usableH) * $baseHalfW;
                        $w2 = (($y2 - $peakY) / $usableH) * $baseHalfW;
                        $ymid = ($y1 + $y2) / 2;
                        $wmid = ($w1 + $w2) / 2;
                        $tierId = "tier-{$type}-{$i}";

                        if ($i === 0) {
                            $pts = "{$centerX},{$y1} " . ($centerX + $w2) . ",{$y2} " . ($centerX - $w2) . ",{$y2}";
                        } else {
                            $pts = ($centerX - $w1) . ",{$y1} " . ($centerX + $w1) . ",{$y1} " . ($centerX + $w2) . ",{$y2} " . ($centerX - $w2) . ",{$y2}";
                        }
                    @endphp

                    <!-- Left Connector & Level Title Pill -->
                    <g class="pyramid-connector pyramid-connector-left" data-target-tier="{{ $tierId }}">
                        <line x1="118" y1="{{ $ymid }}" x2="{{ $centerX - $wmid - 6 }}" y2="{{ $ymid }}" stroke="{{ $c['from'] }}" stroke-width="1.5" stroke-dasharray="3,3" opacity="0.65" />
                        <circle cx="{{ $centerX - $wmid - 6 }}" cy="{{ $ymid }}" r="3.5" fill="{{ $c['from'] }}" />
                        <rect x="8" y="{{ $ymid - 14 }}" width="110" height="28" rx="14" fill="{{ $c['bg'] }}" stroke="{{ $c['from'] }}" stroke-width="1.2" />
                        <text x="63" y="{{ $ymid + 5 }}" text-anchor="middle" fill="{{ $c['from'] }}" font-family="'Plus Jakarta Sans', sans-serif" font-weight="700" font-size="11">
                            Tingkat {{ $tier['label'] }}
                        </text>
                    </g>

                    <!-- Pyramid Slice Shape -->
                    <polygon points="{{ $pts }}" 
                             fill="url(#pyrGrad-{{ $type }}-{{ $i }})" 
                             filter="url(#pyrShadow-{{ $type }})" 
                             class="pyramid-slice" 
                             data-target-tier="{{ $tierId }}"
                             role="button"
                             tabindex="0"
                             aria-label="Tingkat {{ $tier['label'] }}">
                        <title>{{ $tier['label'] }}: {{ $tier['skills']->pluck('name')->implode(', ') }}</title>
                    </polygon>

                    <!-- Center Label on Slice -->
                    <text x="{{ $centerX }}" y="{{ $ymid + 5 }}" 
                          text-anchor="middle" 
                          fill="#FFFFFF" 
                          font-family="'Plus Jakarta Sans', sans-serif" 
                          font-weight="800" 
                          font-size="{{ $totalTiers > 4 ? 10.5 : 12.5 }}" 
                          letter-spacing="0.6" 
                          pointer-events="none">
                        {{ strtoupper($tier['label']) }}
                    </text>

                    <!-- Right Connector & Skill Explanation Pill -->
                    <g class="pyramid-connector pyramid-connector-right" data-target-tier="{{ $tierId }}">
                        <line x1="{{ $centerX + $wmid + 6 }}" y1="{{ $ymid }}" x2="385" y2="{{ $ymid }}" stroke="{{ $c['from'] }}" stroke-width="1.5" stroke-dasharray="3,3" opacity="0.65" />
                        <circle cx="{{ $centerX + $wmid + 6 }}" cy="{{ $ymid }}" r="3.5" fill="{{ $c['from'] }}" />
                        <rect x="385" y="{{ $ymid - 14 }}" width="225" height="28" rx="14" fill="#FFFFFF" stroke="{{ $c['from'] }}" stroke-width="1.2" filter="url(#pyrPillShadow-{{ $type }})" />
                        <text x="397" y="{{ $ymid + 5 }}" 
                              text-anchor="start" 
                              fill="#0F172A" 
                              font-family="'Plus Jakarta Sans', sans-serif" 
                              font-weight="600" 
                              font-size="10.5">
                            {{ $tier['summary'] }}
                        </text>
                        <title>{{ $tier['label'] }}: {{ $tier['skills']->pluck('name')->implode(', ') }}</title>
                    </g>
                @endforeach
            </svg>
        </div>

        <!-- Mobile-First Tier Breakdown (< 768px / Mobile) -->
        <div class="d-md-none w-100 mt-2 px-1">
            <div class="d-flex flex-column gap-2">
                @foreach($tiers as $tier)
                    <div class="pyramid-tier-item p-2 rounded-3 border d-flex align-items-center justify-content-between gap-2" 
                         style="background-color: #FFFFFF; border-color: rgba(10, 27, 61, 0.08) !important;"
                         data-target-tier="tier-{{ $type }}-{{ $tier['index'] }}">
                        <span class="badge rounded-pill px-2.5 py-1 fw-bold small flex-shrink-0" 
                              style="background-color: {{ $tier['color']['bg'] }}; color: {{ $tier['color']['from'] }}; border: 1px solid {{ $tier['color']['from'] }}; font-size: 0.78rem;">
                            Tingkat {{ $tier['label'] }}
                        </span>
                        <span class="small text-dark fw-semibold text-end text-truncate" style="max-width: 65%; font-size: 0.82rem;">
                            {{ $tier['summary'] }}
                        </span>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
