{{-- Extend the master layout --}}
@extends('layouts.app')

{{-- Set the page title (goes into @yield('title') in the layout) --}}
@section('title', 'Dashboard')

{{-- Fill the content slot --}}
@section('content')

<div class="page-header">
    <div>
        <div class="page-title">Admin Dashboard</div>
        <div class="page-subtitle">Miss Philippines 2025 · Live event in progress</div>
        <div class="gold-line"></div>
    </div>
    <div class="ph-actions">
        <a href="{{ route('scoring.index') }}" class="btn btn-gold">▶ Open Scoring</a>
    </div>
</div>

{{-- ── STAT CARDS ── --}}
<div class="stats-grid">
    <div class="stat-card" data-icon="👥">
        <div class="stat-value">{{ $totalContestants }}</div>
        <div class="stat-label">Contestants</div>
        <div class="stat-sub">All active</div>
    </div>
    <div class="stat-card" data-icon="⚖️">
        <div class="stat-value">{{ $totalJudges }}</div>
        <div class="stat-label">Judges</div>
        <div class="stat-sub">Panel members</div>
    </div>
    <div class="stat-card" data-icon="📋">
        <div class="stat-value">{{ $totalScores }}</div>
        <div class="stat-label">Scores Submitted</div>
        <div class="stat-sub">Across all segments</div>
    </div>
    <div class="stat-card" data-icon="🏆">
        {{-- Optional chaining: if $topContestant is null, show 0 --}}
        <div class="stat-value">{{ $topContestant?->average_score ?? 0 }}</div>
        <div class="stat-label">Top Average</div>
        <div class="stat-sub">{{ $topContestant?->name ?? '—' }}</div>
    </div>
</div>

{{-- ── TWO COLUMN ROW ── --}}
<div class="two-col">

    {{-- Judge Scoring Status --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Judge Scoring Status</div>
            <span class="live-dot">LIVE</span>
        </div>
        <table class="tbl">
            <thead>
                <tr>
                    <th>Judge</th>
                    <th>Role</th>
                    <th>Scores Submitted</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                {{-- Loop through judges passed from DashboardController --}}
                @foreach($judges as $judge)
                <tr>
                    <td class="strong">{{ $judge->name }}</td>
                    <td>{{ ucfirst($judge->role) }}</td>
                    <td>{{ $judge->scores_count }}</td>
                    <td>
                        @if($judge->scores_count >= 8)
                            <span class="badge badge-active">Complete</span>
                        @elseif($judge->scores_count > 0)
                            <span class="badge badge-pending">In Progress</span>
                        @else
                            <span class="badge badge-pending">Pending</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Recent Activity --}}
    <div class="card">
        <div class="card-header">
            <div class="card-title">Recent Scores</div>
        </div>
        <div class="act-list">
            @forelse($recentScores as $score)
            <div class="act-item">
                <div class="act-icon">📝</div>
                <div>
                    <div class="act-text">
                        <strong>{{ $score->judge->name }}</strong>
                        submitted scores for
                        <strong>{{ $score->contestant->name }}</strong>
                        — {{ $score->segment }}
                        <span style="color:var(--gold-light);font-weight:600">
                            (avg: {{ $score->average }})
                        </span>
                    </div>
                    <div class="act-time">{{ $score->created_at->diffForHumans() }}</div>
                </div>
            </div>
            @empty
            {{-- @empty runs when the collection has no items --}}
            <div style="color:rgba(255,255,255,0.3);font-size:13px;padding:12px 0">
                No scores submitted yet.
            </div>
            @endforelse
        </div>
    </div>

</div>

@endsection
