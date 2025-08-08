@extends('website.layout.app')

@section('title', 'Riverside Commercial Complex - Project Plans')

@section('content')
<div class="content-header d-flex align-items-center justify-content-between gap-2 flex-wrap">
  <div>
    <h2>Project Plans</h2>
    <p>View and markup architectural plans</p>
  </div>
  <button class="btn orange_btn">
    <i class="fas fa-arrow-up"></i>
    Upload Plan
  </button>
</div>

<div class="CarDs-grid">
  <!-- Plan Card 1 -->
  <div class="CustOm_Card wow fadeInUp" data-wow-delay="0s">
    <div class="plan-image">
      <img src="{{ asset('assets/images/place1.png') }}" alt="Ground Floor Plan">
    </div>
    <div class="carD-details">
      <h3>Ground Floor Plan</h3>
      <div class="plan-meta">
        <span class="revision">Rev. 3.2 • 2.4 MB</span>
        <div class="updated-row">
          <span class="updated">Updated 2 days ago</span>
          <a href="#"> <img src="{{ asset('assets/images/icons/edit.svg') }}" alt="edit"></a>
        </div>
      </div>
    </div>
  </div>

  <!-- Plan Card 2 -->
  <div class="CustOm_Card wow fadeInUp" data-wow-delay=".4s">
    <div class="plan-image">
      <img src="{{ asset('assets/images/place2.png') }}" alt="Second Floor Plan">
    </div>
    <div class="carD-details">
      <h3>Second Floor Plan</h3>
      <div class="plan-meta">
        <span class="revision">Rev. 2.1 • 1.8 MB</span>
        <div class="updated-row">
          <span class="updated">Updated 1 week ago</span>
          <a href="#"> <img src="{{ asset('assets/images/icons/edit.svg') }}" alt="edit"></a>
        </div>
      </div>
    </div>
  </div>

  <!-- Plan Card 3 -->
  <div class="CustOm_Card wow fadeInUp" data-wow-delay=".8s">
    <div class="plan-image">
      <img src="{{ asset('assets/images/place3.png') }}" alt="Front Elevation">
    </div>
    <div class="carD-details">
      <h3>Front Elevation</h3>
      <div class="plan-meta">
        <span class="revision">Rev. 1.5 • 3.2 MB</span>
        <div class="updated-row">
          <span class="updated">Updated 3 days ago</span>
          <a href="#"> <img src="{{ asset('assets/images/icons/edit.svg') }}" alt="edit"></a>
        </div>
      </div>
    </div>
  </div>

  <!-- Plan Card 4 -->
  <div class="CustOm_Card wow fadeInUp" data-wow-delay="1.2s">
    <div class="plan-image">
      <img src="{{ asset('assets/images/place4.png') }}" alt="Building Section A-A">
    </div>
    <div class="carD-details">
      <h3>Building Section A-A</h3>
      <div class="plan-meta">
        <span class="revision">Rev. 2.0 • 2.1 MB</span>
        <div class="updated-row">
          <span class="updated">Updated 5 days ago</span>
          <a href="#"> <img src="{{ asset('assets/images/icons/edit.svg') }}" alt="edit"></a>
        </div>
      </div>
    </div>
  </div>

  <!-- Plan Card 5 -->
  <div class="CustOm_Card wow fadeInUp" data-wow-delay="1.3s">
    <div class="plan-image">
      <img src="{{ asset('assets/images/place5.png') }}" alt="Basement Plan">
    </div>
    <div class="carD-details">
      <h3>Basement Plan</h3>
      <div class="plan-meta">
        <span class="revision">Rev. 1.8 • 1.5 MB</span>
        <div class="updated-row">
          <span class="updated">Updated 1 week ago</span>
          <a href="#"> <img src="{{ asset('assets/images/icons/edit.svg') }}" alt="edit"></a>
        </div>
      </div>
    </div>
  </div>

  <!-- Plan Card 6 -->
  <div class="CustOm_Card wow fadeInUp" data-wow-delay="1.6s">
    <div class="plan-image">
      <img src="{{ asset('assets/images/place6.png') }}" alt="Roof Plan">
    </div>
    <div class="carD-details">
      <h3>Roof Plan</h3>
      <div class="plan-meta">
        <span class="revision">Rev. 1.2 • 1.2 MB</span>
        <div class="updated-row">
          <span class="updated">Updated 2 weeks ago</span>
          <a href="#"> <img src="{{ asset('assets/images/icons/edit.svg') }}" alt="edit"></a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection