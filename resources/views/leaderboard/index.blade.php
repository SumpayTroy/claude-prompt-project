@extends('layouts.app')
@section('title', 'Leaderboard')

@section('content')

<div class="page-header">
    <div>
        <div class="page-title">
            {{ auth()->user()->role === 'audience' ? 'Live Results 🏆' : 'Leaderboard' }}
        </div>
        <div class="page-subtitle">Miss Philippines 2025 · Unofficial Standings</div>
        <div class="gold-line"></div>
    </div>
</div>

{{-- Stats row (hidden from audience) --}}
@unless(auth()->user()->role === 'audience')
<div class="stats-grid" style="grid-template-columns:repeat(3,1fr);margin-bottom:20px">
    @php
        $criteriaNames = ['Beauty & Poise', 'Intelligence & Wit', 'Talent & Performance'];
    @endphp
    @foreach($criteriaNames as $cName)
    <div class="stat-card">
        <div class="stat-label">{{ $cName }}</div>
        <div class="stat-value" style="font-size:28px">
            {{ $contestants->first()?->average_score ?? '—' }}
        </div>
        <div class="stat-sub">Highest · {{ $contestants->first()?->name ?? '—' }}</div>
    </div>
    @endforeach
</div>
@endunless

<div class="card">
    <div class="card-header">
        <div class="card-title">🏆 Official Rankings</div>
        <span class="live-dot">Updated Live</span>
    </div>

    <div class="lb-list">
        @foreach($contestants as $index => $contestant)
        @php
            $rank = $index + 1;
            $medal = match($rank) { 1 => '🥇', 2 => '🥈', 3 => '🥉', default => "#$rank" };
            $rankClass = match(true) { $rank===1 => 'rank-1', $rank===2 => 'rank-2', $rank===3 => 'rank-3', default => '' };
            // Bar width as % of top score
            $barWidth = $topScore > 0 ? round(($contestant->average_score / $topScore) * 100, 1) : 0;
        @endphp

        <div class="lb-item {{ $rankClass }}">
            <div class="lb-rank">{{ $medal }}</div>
            <div class="lb-avatar">{{ $contestant->emoji }}</div>
            <div class="lb-info">
                <div class="lb-name">{{ $contestant->name }}</div>
                <div class="lb-rep">#{{ $contestant->number }} · {{ $contestant->region }}</div>
            </div>
            <div class="lb-bar-col">
                <div class="lb-bar-bg">
                    <div class="lb-bar-fg" style="width:{{ $barWidth }}%"></div>
                </div>
            </div>
            <div class="lb-score-col">
                <div class="lb-score">{{ $contestant->average_score }}</div>
                <div class="lb-score-lbl">avg score</div>
            </div>
        </div>
        @endforeach
    </div>
</div>

@endsection
