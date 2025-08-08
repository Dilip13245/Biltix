@extends('website.layout.app')

@section('title', 'Riverside Commercial Complex - Safety Checklist')

@section('content')
<div class="content-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
  <div>
    <h2>Safety Checklist</h2>
    <p>Ensure safety compliance and protocols</p>
  </div>
  <button class="btn orange_btn">
    <i class="fas fa-plus"></i>
    Add Checklist
  </button>
</div>

<div class="CarDs-grid">
  <!-- Safety Checklist Card 1 -->
  <div class="CustOm_Card wow fadeInUp" data-wow-delay="0s">
    <div class="carD-details">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <h3>Daily Safety Inspection</h3>
        <span class="badge bg-success">Completed</span>
      </div>
      <p class="text-muted mb-2">March 25, 2025</p>
      <div class="mb-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" checked disabled>
          <label class="form-check-label">All workers wearing safety helmets</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" checked disabled>
          <label class="form-check-label">Safety barriers in place</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" checked disabled>
          <label class="form-check-label">Emergency exits clear</label>
        </div>
      </div>
      <div class="d-flex justify-content-between align-items-center">
        <span class="small text-muted">Checked by: Mike Wilson</span>
        <button class="btn btn-sm btn-primary">View Full List</button>
      </div>
    </div>
  </div>

  <!-- Safety Checklist Card 2 -->
  <div class="CustOm_Card wow fadeInUp" data-wow-delay=".4s">
    <div class="carD-details">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <h3>Equipment Safety Check</h3>
        <span class="badge bg-warning">Pending</span>
      </div>
      <p class="text-muted mb-2">March 26, 2025</p>
      <div class="mb-3">
        <div class="form-check">
          <input class="form-check-input" type="checkbox" disabled>
          <label class="form-check-label">Crane inspection</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" disabled>
          <label class="form-check-label">Power tools check</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="checkbox" disabled>
          <label class="form-check-label">Scaffolding stability</label>
        </div>
      </div>
      <div class="d-flex justify-content-between align-items-center">
        <span class="small text-muted">Due: Tomorrow</span>
        <button class="btn btn-sm btn-primary">Start Check</button>
      </div>
    </div>
  </div>

  <!-- Safety Checklist Card 3 -->
  <div class="CustOm_Card wow fadeInUp" data-wow-delay=".8s">
    <div class="carD-details">
      <div class="d-flex justify-content-between align-items-start mb-2">
        <h3>Weekly Safety Meeting</h3>
        <span class="badge bg-info">Scheduled</span>
      </div>
      <p class="text-muted mb-2">March 27, 2025 - 9:00 AM</p>
      <div class="mb-3">
        <p class="text-muted">Topics to cover:</p>
        <ul class="text-muted">
          <li>New safety protocols</li>
          <li>Incident reporting</li>
          <li>Equipment updates</li>
        </ul>
      </div>
      <div class="d-flex justify-content-between align-items-center">
        <span class="small text-muted">Conference Room A</span>
        <button class="btn btn-sm btn-primary">View Agenda</button>
      </div>
    </div>
  </div>
</div>
@endsection