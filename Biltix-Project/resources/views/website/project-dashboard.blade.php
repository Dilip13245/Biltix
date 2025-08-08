@extends('website.layout.app')

@section('title', $project->name . ' - Dashboard')

@section('content')
<div class="content-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
  <div>
    <h2>{{ $project->name }}</h2>
    <p>Project Dashboard - {{ $project->type ?? 'Construction Project' }}</p>
  </div>
  <div class="status-badge bg2">
    <span class="status-dot"></span>
    {{ ucfirst($project->status) }}
  </div>
</div>

<div class="CarDs-grid">
  <!-- Project Stats Cards -->
  <div class="CustOm_Card wow fadeInUp" data-wow-delay="0s">
    <div class="carD-details">
      <h3>Project Progress</h3>
      <div class="progress-circle">
        <span class="progress-text">{{ $project->progress ?? 67 }}%</span>
      </div>
      <p class="text-muted">Overall completion</p>
    </div>
  </div>

  <div class="CustOm_Card wow fadeInUp" data-wow-delay=".2s">
    <div class="carD-details">
      <h3>Active Tasks</h3>
      <div class="stat-number">12</div>
      <p class="text-muted">Tasks in progress</p>
    </div>
  </div>

  <div class="CustOm_Card wow fadeInUp" data-wow-delay=".4s">
    <div class="carD-details">
      <h3>Team Members</h3>
      <div class="stat-number">{{ $project->team_count ?? 8 }}</div>
      <p class="text-muted">Active members</p>
    </div>
  </div>

  <div class="CustOm_Card wow fadeInUp" data-wow-delay=".6s">
    <div class="carD-details">
      <h3>Next Inspection</h3>
      <div class="stat-date">Mar 25</div>
      <p class="text-muted">Foundation check</p>
    </div>
  </div>
</div>
@endsection