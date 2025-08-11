@extends('website.layout.app')

@section('title', 'Riverside Commercial Complex - Project Gallery')

@section('content')
<div class="content-header border-0 shadow-none mb-4">
  <div class="d-flex align-items-center justify-content-between gap-2 flex-wrap">
    <div>
      <h2>Photo Gallery</h2>
      <p>Construction progress documentation</p>
    </div>
    <button class="btn btn-primary py-2 px-md-4" data-bs-toggle="modal" data-bs-target="#addPhotoModal">
      <i class="fas fa-plus"></i>
      Add New
    </button>
  </div>
  <div class="gallery-filters d-flex align-items-center gap-3 flex-wrap  mt-3 mt-md-4">
    <select class="form-select w-auto">
      <option>All Categories</option>
      <option>Foundation</option>
      <option>Structure</option>
      <option>Finishing</option>
    </select>
    <div class="date-filter d-flex align-items-center border rounded-3 px-4 py-2 bg-light">
      <i class="fa-solid fa-calendar-days me-2"></i>
      <span>May 25, 2025</span>
    </div>
    <div class="total-photos ">
      <span>Total: </span>
      <a href="#" class="fw-bold text-primary text-decoration-none">247 photos</a>
    </div>
  </div>
</div>
<div class="CarDs-grid">
  <!-- Plan Card 1 -->
  <div class="CustOm_Card wow fadeInUp" data-wow-delay="0s">
    <div class="plan-image">
      <img src="{{ asset('assets/images/Construction1.png') }}" alt="Construction1">
    </div>
    <div class="carD-details">
      <h3>Foundation Pour - Phase 1</h3>
      <div class="d-flex align-items-center justify-content-between gap-2 gam-md-3 flex-wrap">
        <p class="revision fw-normal">Jan 15, 2024</p>
        <p class="revision fw-normal">by John Smith</p>
      </div>
    </div>
  </div>

  <!-- Plan Card 2 -->
  <div class="CustOm_Card wow fadeInUp" data-wow-delay=".4s">
    <div class="plan-image">
      <img src="{{ asset('assets/images/Construction2.png') }}" alt="Construction2">
    </div>
    <div class="carD-details">
      <h3>Second Floor Plan</h3>
      <div class="d-flex align-items-center justify-content-between gap-2 gam-md-3 flex-wrap">
        <p class="revision fw-normal">Jan 18, 2024</p>
        <p class="revision fw-normal">by Mike Johnson</p>
      </div>
    </div>
  </div>

  <!-- Plan Card 3 -->
  <div class="CustOm_Card wow fadeInUp" data-wow-delay=".8s">
    <div class="plan-image">
      <img src="{{ asset('assets/images/Construction3.png') }}" alt="Construction3">
    </div>
    <div class="carD-details">
      <h3>Front Elevation</h3>
      <div class="d-flex align-items-center justify-content-between gap-2 gam-md-3 flex-wrap">
        <p class="revision fw-normal">Jan 20, 2024</p>
        <p class="revision fw-normal">by Sarah Davis</p>
      </div>
    </div>
  </div>

  <!-- Plan Card 4 -->
  <div class="CustOm_Card wow fadeInUp" data-wow-delay="1.2s">
    <div class="plan-image">
      <img src="{{ asset('assets/images/Construction4.png') }}" alt="Construction4">
    </div>
    <div class="carD-details">
      <h3>Building Section A-A</h3>
      <div class="d-flex align-items-center justify-content-between gap-2 gam-md-3 flex-wrap">
        <p class="revision fw-normal">Jan 22, 2024</p>
        <p class="revision fw-normal">by Tom Wilson</p>
      </div>
    </div>
  </div>

  <!-- Plan Card 5 -->
  <div class="CustOm_Card wow fadeInUp" data-wow-delay="1.3s">
    <div class="plan-image">
      <img src="{{ asset('assets/images/Construction5.png') }}" alt="Construction5">
    </div>
    <div class="carD-details">
      <h3>Basement Plan</h3>
      <div class="d-flex align-items-center justify-content-between gap-2 gam-md-3 flex-wrap">
        <p class="revision fw-normal">Jan 25, 2024</p>
        <p class="revision fw-normal">by Alex Brown</p>
      </div>
    </div>
  </div>

  <!-- Plan Card 6 -->
  <div class="CustOm_Card wow fadeInUp" data-wow-delay="1.6s">
    <div class="plan-image">
      <img src="{{ asset('assets/images/Construction6.png') }}" alt="Construction6">
    </div>
    <div class="carD-details">
      <h3>Roof Plan</h3>
      <div class="d-flex align-items-center justify-content-between gap-2 gam-md-3 flex-wrap">
        <p class="revision fw-normal">Jan 28, 2024</p>
        <p class="revision fw-normal">by Lisa Garcia</p>
      </div>
    </div>
  </div>
</div>
@include('website.modals.add-photo-modal')

<script>
// Add Photo Form Handler
document.addEventListener('DOMContentLoaded', function() {
  const addPhotoForm = document.getElementById('addPhotoForm');
  if (addPhotoForm) {
    addPhotoForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Show loading state
      const submitBtn = document.querySelector('#addPhotoModal .btn.orange_btn');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Uploading...';
      submitBtn.disabled = true;
      
      // Simulate photo upload
      setTimeout(() => {
        alert('Photos uploaded successfully!');
        bootstrap.Modal.getInstance(document.getElementById('addPhotoModal')).hide();
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        addPhotoForm.reset();
        document.getElementById('photoPreview').style.display = 'none';
        
        // Refresh page or add new photos to the gallery
        location.reload();
      }, 3000);
    });
  }
  
  // Filter functionality
  const categoryFilter = document.querySelector('select.form-select');
  if (categoryFilter) {
    categoryFilter.addEventListener('change', function() {
      const filterValue = this.value.toLowerCase();
      const photoCards = document.querySelectorAll('.CustOm_Card');
      
      photoCards.forEach(card => {
        const cardTitle = card.querySelector('h3').textContent.toLowerCase();
        if (filterValue === 'all categories' || cardTitle.includes(filterValue)) {
          card.style.display = 'block';
        } else {
          card.style.display = 'none';
        }
      });
    });
  }
  
  // Photo click to view full size
  const photoImages = document.querySelectorAll('.plan-image img');
  photoImages.forEach(img => {
    img.addEventListener('click', function() {
      openPhotoViewer(this.src, this.alt);
    });
  });
});

function openPhotoViewer(imageSrc, title) {
  // Create a simple photo viewer
  const modal = document.createElement('div');
  modal.className = 'modal fade';
  modal.innerHTML = `
    <div class="modal-dialog modal-xl">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">${title}</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body text-center">
          <img src="${imageSrc}" class="img-fluid" alt="${title}">
        </div>
      </div>
    </div>
  `;
  
  document.body.appendChild(modal);
  const bsModal = new bootstrap.Modal(modal);
  bsModal.show();
  
  modal.addEventListener('hidden.bs.modal', function() {
    document.body.removeChild(modal);
  });
}
</script>

@endsection