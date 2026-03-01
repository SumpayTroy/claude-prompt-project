@extends('layouts.app')
@section('title', 'Scoring Panel')

@section('content')

<div class="page-header">
    <div>
        <div class="page-title">
            {{ auth()->user()->isJudge() ? 'Score Contestants' : 'Scoring Panel' }}
        </div>
        <div class="page-subtitle">
            {{ auth()->user()->name }} · {{ $segment }} · 4 criteria
        </div>
        <div class="gold-line"></div>
    </div>
</div>

{{-- ── SEGMENT PICKER ── --}}
{{-- Clicking a segment reloads the page with ?segment=X in the URL --}}
<div class="segment-picker">
    @foreach($segments as $seg)
        <a href="{{ route('scoring.index', ['contestant' => $selected?->id, 'segment' => $seg]) }}"
           class="segment-chip {{ $segment === $seg ? 'active' : '' }}">
            {{ $seg }}
        </a>
    @endforeach
</div>

<div class="scoring-layout">

    {{-- ── LEFT: Contestant List ── --}}
    <div class="card" style="height:fit-content">
        <div class="card-title" style="margin-bottom:16px">Select Contestant</div>
        <div class="contestant-select-list">
            @foreach($contestants as $c)
            <a href="{{ route('scoring.index', ['contestant' => $c->id, 'segment' => $segment]) }}"
               class="contestant-select-item {{ $selected?->id === $c->id ? 'selected' : '' }}"
               style="text-decoration:none">
                <div class="mini-avatar">{{ $c->emoji }}</div>
                <div>
                    <div class="cname">{{ $c->name }}</div>
                    <div class="crep">#{{ $c->number }} · {{ $c->region }}</div>
                </div>
                @if($selected?->id === $c->id)
                    <span class="ccheck">✓</span>
                @endif
            </a>
            @endforeach
        </div>
    </div>

    {{-- ── RIGHT: Scoring Form ── --}}
    <div class="scoring-right">

        {{-- Contestant header banner --}}
        @if($selected)
        <div class="judge-header-card">
            <div class="judge-contestant-avatar">{{ $selected->emoji }}</div>
            <div>
                <div class="jh-label">Now Scoring</div>
                <div class="jh-name">{{ $selected->name }}</div>
                <div class="jh-meta">#{{ $selected->number }} · {{ $selected->region }} · {{ $segment }}</div>
            </div>
        </div>

        {{-- THE SCORE FORM --}}
        <form method="POST" action="{{ route('scoring.store') }}">
            @csrf

            {{-- Hidden fields — sent with the form but not visible to user --}}
            <input type="hidden" name="contestant_id" value="{{ $selected->id }}">
            <input type="hidden" name="segment" value="{{ $segment }}">

            {{-- ── CRITERIA CARDS ── --}}
            @php
                // Define criteria with weights and descriptions
                $criteria = [
                    ['key' => 'beauty',        'name' => 'Beauty & Poise',        'weight' => '25%', 'desc' => 'Physical appearance, stage presence, and overall poise'],
                    ['key' => 'intelligence',  'name' => 'Intelligence & Wit',     'weight' => '25%', 'desc' => 'Quick thinking, communication, and academic bearing'],
                    ['key' => 'talent',        'name' => 'Talent & Performance',   'weight' => '30%', 'desc' => 'Showcase of individual talent in chosen discipline'],
                    ['key' => 'qa',            'name' => 'Q&A / Impromptu Speech', 'weight' => '20%', 'desc' => 'Clarity, depth, and relevance of responses'],
                ];
                $pips = [50, 55, 60, 65, 70, 75, 80, 85, 90, 95, 100];
            @endphp

            @foreach($criteria as $cr)
            <div class="criteria-card">
                <div class="criteria-header">
                    <div class="criteria-name">{{ $cr['name'] }}</div>
                    <span class="criteria-weight">{{ $cr['weight'] }}</span>
                </div>
                <div class="criteria-desc">{{ $cr['desc'] }}</div>

                {{-- Score display — updates via JS when a pip is clicked --}}
                <div class="score-display" id="disp-{{ $cr['key'] }}">
                    {{ $existing?->{$cr['key']} ?? 85 }}
                    <span>/ 100</span>
                </div>

                {{-- Hidden input that actually gets submitted in the form --}}
                <input type="hidden"
                       name="{{ $cr['key'] }}"
                       id="input-{{ $cr['key'] }}"
                       value="{{ $existing?->{$cr['key']} ?? 85 }}">

                {{-- Score dot buttons — clicking updates the hidden input --}}
                <div class="score-dots">
                    @foreach($pips as $val)
                    <button type="button"
                            class="score-dot {{ ($existing?->{$cr['key']} ?? 85) == $val ? 'selected' : '' }}"
                            onclick="pickScore('{{ $cr['key'] }}', {{ $val }}, this)">
                        {{ $val }}
                    </button>
                    @endforeach
                </div>
            </div>
            @endforeach

            {{-- ── TOTAL SCORE BANNER ── --}}
            <div class="total-score-banner">
                <div>
                    <div class="total-label">Computed Average Score</div>
                    <div class="total-sub">Based on all 4 criteria with weights</div>
                </div>
                <div style="text-align:right">
                    <div class="total-value" id="total-display">
                        {{ $existing?->average ?? '—' }}
                    </div>
                    <div class="total-out">out of 100</div>
                </div>
            </div>

            <button type="submit" class="btn btn-gold" style="width:100%;justify-content:center;padding:15px;margin-top:4px">
                ✓ Submit Scores
            </button>
        </form>

        @else
            <div class="card" style="text-align:center;padding:40px;color:rgba(255,255,255,0.3)">
                Select a contestant from the list to begin scoring.
            </div>
        @endif

    </div>
</div>

@endsection
