@extends('layouts.app')
@section('title', 'Contestants')

@section('content')

<div class="page-header">
    <div>
        <div class="page-title">Contestants</div>
        <div class="page-subtitle">{{ $contestants->count() }} registered · Miss Philippines 2025</div>
        <div class="gold-line"></div>
    </div>
</div>

<div class="contestants-grid">
    {{-- Loop through each contestant from the database --}}
    @foreach($contestants as $contestant)
    <div class="contestant-card">
        <div class="contestant-number">#{{ $contestant->number }}</div>
        <div class="contestant-avatar">{{ $contestant->emoji }}</div>
        <div class="contestant-name">{{ $contestant->name }}</div>
        <div class="contestant-rep">{{ $contestant->region }}</div>

        {{-- Score bar width = average score % --}}
        <div class="score-bar-wrap">
            <div class="score-bar" style="width: {{ $contestant->average_score }}%"></div>
        </div>
        <div class="score-text">{{ $contestant->average_score }} avg</div>

        <div class="c-actions">
            <a href="{{ route('scoring.index', ['contestant' => $contestant->id]) }}"
               class="btn btn-gold btn-xs">Score</a>
        </div>
    </div>
    @endforeach
</div>

@endsection
