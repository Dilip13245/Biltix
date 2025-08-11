@extends('website.layout.app')

@section('title', 'Riverside Commercial Complex - Snag List')

@section('content')
<div class="content-header d-flex justify-content-between align-items-center gap-3 flex-wrap">
  <div>
    <h2>Snag List</h2>
    <p>View and manage construction snags</p>
  </div>
  <button class="btn orange_btn py-2" data-bs-toggle="modal" data-bs-target="#addSnagModal">
    <i class="fas fa-plus"></i>
    Add New Snag
  </button>
</div>
<section class="px-md-4">
  <div class="container-fluid ">
    <div class="row  wow fadeInUp" data-wow-delay="0.9s">
      <div class="col-12">
        <div class="card B_shadow">
          <div class="card-body px-md-3 py-md-4">
            <div class="row">
              <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                <label class="fw-medium mb-2">Status</label>
                <select class="form-select w-100">
                  <option>All Status</option>
                  <option>Active</option>
                  <option>Completed</option>
                </select>
              </div>
              <div class="col-lg-3 col-md-4 col-sm-6 col-6">
                <label class="fw-medium mb-2">Category</label>
                <select class="form-select w-100">
                  <option>All Category</option>
                </select>
              </div>
              <div class="col-lg-3 col-md-4 col-sm-6 col-12 mt-3 mt-md-0">
                <label class="fw-medium mb-2">Search</label>
                <form class="serchBar position-relative serchBar2 ">
                  <input class="form-control " type="search" placeholder="Search Snags " aria-label="Search">
                  <span class="search_icon"><img src="{{ asset('assets/images/icons/search.svg') }}" alt="search"></span>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 mt-4">
        <div class="card B_shadow">
          <div class="card-body py-md-4 px-0">
            <div class="snag-list">
              <!-- Snag Item 1 -->
              <div class="items_listing d-flex flex-wrap align-items-start gap-3 mb-4 px-md-4 px-2 pt-2">
                <span class="stat-icon bg2 ms-0"><svg width="14" height="19" viewBox="0 0 14 19" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M11.4091 1.81829C11.6165 1.33665 11.4618 0.774146 11.0364 0.464771C10.611 0.155396 10.0309 0.183521 9.63368 0.528052L0.633684 8.40305C0.282121 8.71243 0.155559 9.20813 0.320793 9.64407C0.486028 10.08 0.907903 10.3753 1.37548 10.3753H5.2954L2.59189 16.6823C2.38446 17.164 2.53915 17.7265 2.96454 18.0359C3.38993 18.3452 3.97001 18.3171 4.36728 17.9726L13.3673 10.0976C13.7188 9.78821 13.8454 9.29251 13.6802 8.85657C13.5149 8.42063 13.0966 8.12883 12.6255 8.12883H8.70556L11.4091 1.81829Z"
                      fill="#F58D2E" />
                  </svg>
                </span>
                <div class="flex-grow-1">
                  <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                    <h5 class="mb-0 fw-semibold">Electrical outlet not working in Room 205</h5>
                    <span class="badge badge2">High Priority</span>
                    <span class="badge badge4">In Progress</span>
                  </div>
                  <p class="mb-2 text-muted small">Power outlet on the north wall is not functioning. Tested
                    with
                    multiple devices.</p>
                  <div class="d-flex flex-wrap gap-3 text-muted small">
                    <span><i class="fas fa-user"></i> Reported by: John Smith</span>
                    <span><i class="fas fa-calendar-alt"></i> Jan 15, 2024</span>
                    <span><i class="fas fa-building"></i> Building A, Floor 2</span>
                  </div>
                </div>
                <div class="ms-auto">
                  <a href="#" class="text-secondary" title="Edit"><i class="fas fa-pen-to-square fa-lg"></i></a>
                </div>
              </div>
              <!-- Snag Item 2 -->
              <div class="items_listing d-flex flex-wrap align-items-start gap-3 mb-4 px-md-4 px-2 pt-2">
                <span class="stat-icon bg2 ms-0"><svg width="22" height="19" viewBox="0 0 22 19" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M15.4121 8.59961C14.4207 8.76836 13.366 8.47305 12.5996 7.70664L11.2602 6.36719C10.7328 5.83984 10.4375 5.12969 10.4375 4.38438V3.95898L7.63555 2.42969C7.44922 2.32773 7.3332 2.12734 7.34375 1.91289C7.3543 1.69844 7.48086 1.50859 7.67773 1.4207L9.33711 0.682422C9.98398 0.397656 10.6836 0.25 11.3938 0.25H12.0301C13.3203 0.25 14.5613 0.742187 15.5 1.62461L17.068 3.10117C17.9188 3.90273 18.2352 5.05938 18.0031 6.12461L18.5586 6.68359L18.8398 6.40234C19.1703 6.07188 19.7047 6.07188 20.0316 6.40234L20.8754 7.24609C21.2059 7.57656 21.2059 8.11094 20.8754 8.43789L17.7816 11.5316C17.4512 11.8621 16.9168 11.8621 16.5898 11.5316L15.7461 10.6879C15.4156 10.3574 15.4156 9.82305 15.7461 9.49609L16.0273 9.21484L15.4121 8.59961ZM1.83828 13.5074L10.0473 6.66953C10.1703 6.8418 10.3109 7.00703 10.4621 7.16172L11.8016 8.50117C12.0125 8.71211 12.2375 8.89492 12.4766 9.05312L5.61758 17.2867C5.10781 17.8984 4.35195 18.25 3.55742 18.25C2.07383 18.25 0.875 17.0477 0.875 15.5676C0.875 14.773 1.23008 14.0172 1.83828 13.5074Z"
                      fill="#F58D2E" />
                  </svg>
                </span>
                <div class="flex-grow-1">
                  <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                    <h5 class="mb-0 fw-semibold">Concrete crack in main lobby wall</h5>
                    <span class="badge badge3">Medium Priority</span>
                    <span class="badge badge5">Open</span>
                  </div>
                  <p class="mb-2 text-muted small">Visible crack approximately 2 feet long on the east wall of
                    the
                    main lobby.</p>
                  <div class="d-flex flex-wrap gap-3 text-muted small">
                    <span><i class="fas fa-user"></i> Reported by: Sarah Johnson</span>
                    <span><i class="fas fa-calendar-alt"></i> Jan 14, 2024</span>
                    <span><i class="fas fa-building"></i> Building A, Ground Floor</span>
                  </div>
                </div>
                <div class="ms-auto">
                  <a href="#" class="text-secondary" title="Edit"><i class="fas fa-pen-to-square fa-lg"></i></a>
                </div>
              </div>
              <!-- Snag Item 3 -->
              <div class="items_listing d-flex flex-wrap align-items-start gap-3 mb-4 px-md-4 px-2 pt-2">
                <span class="stat-icon bg2 ms-0"><svg width="14" height="19" viewBox="0 0 14 19" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                      d="M7 18.25C3.27344 18.25 0.25 15.2266 0.25 11.5C0.25 8.29375 4.82734 2.27852 6.10703 0.661328C6.31797 0.397656 6.63086 0.25 6.96836 0.25H7.03164C7.36914 0.25 7.68203 0.397656 7.89297 0.661328C9.17266 2.27852 13.75 8.29375 13.75 11.5C13.75 15.2266 10.7266 18.25 7 18.25ZM3.625 12.0625C3.625 11.7531 3.37188 11.5 3.0625 11.5C2.75312 11.5 2.5 11.7531 2.5 12.0625C2.5 14.2387 4.26133 16 6.4375 16C6.74687 16 7 15.7469 7 15.4375C7 15.1281 6.74687 14.875 6.4375 14.875C4.88359 14.875 3.625 13.6164 3.625 12.0625Z"
                      fill="#F58D2E" />
                  </svg>
                </span>
                <div class="flex-grow-1">
                  <div class="d-flex flex-wrap align-items-center gap-2 mb-1">
                    <h5 class="mb-0 fw-semibold">Water leak under bathroom sink</h5>
                    <span class="badge badge1">Low Priority</span>
                    <span class="badge badge1">Approved</span>
                  </div>
                  <p class="mb-2 text-muted small">Minor water drip detected under the sink in the executive
                    bathroom.</p>
                  <div class="d-flex flex-wrap gap-3 text-muted small">
                    <span><i class="fas fa-user"></i> Reported by: Mike Wilson</span>
                    <span><i class="fas fa-calendar-alt"></i> Jan 12, 2024</span>
                    <span><i class="fas fa-building"></i> Building B, Floor 3</span>
                  </div>
                </div>
                <div class="ms-auto">
                  <a href="#" class="text-secondary" title="Edit"><i class="fas fa-pen-to-square fa-lg"></i></a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
@include('website.modals.add-snag-modal')

<script>
// Add Snag Form Handler
document.addEventListener('DOMContentLoaded', function() {
  const addSnagForm = document.getElementById('addSnagForm');
  if (addSnagForm) {
    addSnagForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Show loading state
      const submitBtn = document.querySelector('#addSnagModal .btn.orange_btn');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Adding...';
      submitBtn.disabled = true;
      
      // Simulate snag creation
      setTimeout(() => {
        alert('Snag added successfully!');
        bootstrap.Modal.getInstance(document.getElementById('addSnagModal')).hide();
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        addSnagForm.reset();
        
        location.reload();
      }, 2000);
    });
  }
  
  // Filter functionality
  const statusFilter = document.querySelector('select.form-select');
  const categoryFilter = document.querySelectorAll('select.form-select')[1];
  const searchInput = document.querySelector('input[type="search"]');
  
  function filterSnags() {
    const statusValue = statusFilter.value.toLowerCase();
    const categoryValue = categoryFilter ? categoryFilter.value.toLowerCase() : '';
    const searchValue = searchInput.value.toLowerCase();
    const snagItems = document.querySelectorAll('.items_listing');
    
    snagItems.forEach(item => {
      const title = item.querySelector('h5').textContent.toLowerCase();
      const description = item.querySelector('p').textContent.toLowerCase();
      const statusBadge = item.querySelector('.badge:last-of-type').textContent.toLowerCase();
      
      const matchesStatus = statusValue === 'all status' || statusBadge.includes(statusValue);
      const matchesSearch = !searchValue || title.includes(searchValue) || description.includes(searchValue);
      
      if (matchesStatus && matchesSearch) {
        item.style.display = 'flex';
      } else {
        item.style.display = 'none';
      }
    });
  }
  
  if (statusFilter) statusFilter.addEventListener('change', filterSnags);
  if (categoryFilter) categoryFilter.addEventListener('change', filterSnags);
  if (searchInput) searchInput.addEventListener('input', filterSnags);
  
  // Edit snag functionality
  const editButtons = document.querySelectorAll('.fa-pen-to-square');
  editButtons.forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      const snagItem = this.closest('.items_listing');
      const title = snagItem.querySelector('h5').textContent;
      const description = snagItem.querySelector('p').textContent;
      const priority = snagItem.querySelector('.badge').textContent;
      const status = snagItem.querySelector('.badge:last-of-type').textContent;
      
      alert(`Edit Snag: ${title}\n\nPriority: ${priority}\nStatus: ${status}\nDescription: ${description}\n\nEdit form would open here with:\n• Editable fields\n• Status update options\n• Photo management\n• Comment history`);
    });
  });
});
</script>

@endsection