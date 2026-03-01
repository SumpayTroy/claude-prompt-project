@extends('layouts.app')
@section('title', 'Tabulation')

@section('content')

<div class="page-header">
    <div>
        <div class="page-title">Tabulation</div>
        <div class="page-subtitle">Aggregate scores across all judges · {{ $segment }}</div>
        <div class="gold-line"></div>
    </div>
    <div class="ph-actions">
        <button class="btn btn-outline">🔒 Lock Scores</button>
        <button class="btn btn-gold">✓ Finalize Round</button>
    </div>
</div>

{{-- Segment selector --}}
<div class="segment-picker">
    @foreach($segments as $seg)
        <a href="{{ route('tabulation.index', ['segment' => $seg]) }}"
           class="segment-chip {{ $segment === $seg ? 'active' : '' }}">
            {{ $seg }}
        </a>
    @endforeach
</div>

<div class="card">
    <div class="card-header">
        <div class="card-title">Score Matrix — {{ $segment }}</div>
        <span class="badge badge-pending">Pending Finalization</span>
    </div>

    <div style="overflow-x:auto">
        <table class="tbl">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Contestant</th>
                    {{-- One column per judge --}}
                    @foreach($judges as $judge)
                        <th>{{ Str::limit($judge->name, 15) }}</th>
                    @endforeach
                    <th>Average</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($contestants as $contestant)
                <tr>
                    <td>{{ $contestant->number }}</td>
                    <td class="strong">{{ $contestant->name }}</td>

                    {{-- Score for each judge --}}
                    @foreach($judges as $judge)
                    <td>
                        @if(isset($scoreMatrix[$contestant->id][$judge->id]))
                            {{ $scoreMatrix[$contestant->id][$judge->id]->average }}
                        @else
                            <span style="color:rgba(255,255,255,0.2)">—</span>
                        @endif
                    </td>
                    @endforeach

                    <td style="color:var(--gold-light);font-weight:700">
                        {{ $contestant->average_score }}
                    </td>
                    <td>
                        @if($contestant->average_score > 0)
                            <span class="badge badge-active">Verified</span>
                        @else
                            <span class="badge badge-pending">Pending</span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

@endsection
