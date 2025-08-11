<!-- Search Modal -->
<div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="searchModalLabel">
          <i class="fas fa-search me-2"></i>Search Projects
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="mb-4">
          <div class="input-group">
            <input type="text" class="form-control form-control-lg" id="searchInput" 
              placeholder="Search by project name, type, or location..." autofocus>
            <button class="btn orange_btn" type="button" onclick="performSearch()">
              <i class="fas fa-search"></i>
            </button>
          </div>
        </div>

        <div class="row mb-3">
          <div class="col-md-4">
            <label class="form-label fw-medium">Project Type</label>
            <select class="form-select" id="searchType">
              <option value="">All Types</option>
              <option value="commercial">Commercial</option>
              <option value="residential">Residential</option>
              <option value="industrial">Industrial</option>
              <option value="renovation">Renovation</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-medium">Status</label>
            <select class="form-select" id="searchStatus">
              <option value="">All Status</option>
              <option value="active">Active</option>
              <option value="completed">Completed</option>
              <option value="on_hold">On Hold</option>
            </select>
          </div>
          <div class="col-md-4">
            <label class="form-label fw-medium">Progress</label>
            <select class="form-select" id="searchProgress">
              <option value="">Any Progress</option>
              <option value="0-25">0-25%</option>
              <option value="26-50">26-50%</option>
              <option value="51-75">51-75%</option>
              <option value="76-100">76-100%</option>
            </select>
          </div>
        </div>

        <div id="searchResults" class="mt-4">
          <h6 class="fw-bold mb-3">Search Results</h6>
          <div id="resultsContainer">
            <div class="text-center text-muted py-4">
              <i class="fas fa-search fa-3x mb-3"></i>
              <p>Enter search terms to find projects</p>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn orange_btn" onclick="clearSearch()">
          <i class="fas fa-times me-2"></i>Clear Filters
        </button>
      </div>
    </div>
  </div>
</div>

<script>
function performSearch() {
  const searchTerm = document.getElementById('searchInput').value.toLowerCase();
  const searchType = document.getElementById('searchType').value.toLowerCase();
  const searchStatus = document.getElementById('searchStatus').value.toLowerCase();
  const searchProgress = document.getElementById('searchProgress').value;
  
  // Sample project data (in real app, this would come from API)
  const projects = [
    { name: 'Downtown Office Complex', type: 'commercial', status: 'active', progress: 68, location: 'Downtown' },
    { name: 'Residential Tower A', type: 'residential', status: 'active', progress: 45, location: 'North District' },
    { name: 'Shopping Mall Renovation', type: 'renovation', status: 'completed', progress: 100, location: 'City Center' },
    { name: 'Industrial Warehouse', type: 'industrial', status: 'active', progress: 32, location: 'Industrial Zone' },
    { name: 'Hospital Extension', type: 'commercial', status: 'active', progress: 78, location: 'Medical District' }
  ];
  
  let filteredProjects = projects.filter(project => {
    const matchesSearch = !searchTerm || 
      project.name.toLowerCase().includes(searchTerm) ||
      project.type.toLowerCase().includes(searchTerm) ||
      project.location.toLowerCase().includes(searchTerm);
    
    const matchesType = !searchType || project.type === searchType;
    const matchesStatus = !searchStatus || project.status === searchStatus;
    
    let matchesProgress = true;
    if (searchProgress) {
      const [min, max] = searchProgress.split('-').map(Number);
      matchesProgress = project.progress >= min && project.progress <= max;
    }
    
    return matchesSearch && matchesType && matchesStatus && matchesProgress;
  });
  
  displaySearchResults(filteredProjects);
}

function displaySearchResults(projects) {
  const container = document.getElementById('resultsContainer');
  
  if (projects.length === 0) {
    container.innerHTML = `
      <div class="text-center text-muted py-4">
        <i class="fas fa-search fa-3x mb-3"></i>
        <p>No projects found matching your criteria</p>
      </div>
    `;
    return;
  }
  
  const resultsHTML = projects.map(project => `
    <div class="card mb-3">
      <div class="card-body">
        <div class="row align-items-center">
          <div class="col-md-6">
            <h6 class="fw-bold mb-1">${project.name}</h6>
            <small class="text-muted">${project.type} • ${project.location}</small>
          </div>
          <div class="col-md-3">
            <span class="badge ${project.status === 'active' ? 'bg-success' : project.status === 'completed' ? 'bg-primary' : 'bg-warning'}">${project.status}</span>
          </div>
          <div class="col-md-3">
            <div class="progress" style="height: 6px;">
              <div class="progress-bar" style="width: ${project.progress}%"></div>
            </div>
            <small class="text-muted">${project.progress}% Complete</small>
          </div>
        </div>
      </div>
    </div>
  `).join('');
  
  container.innerHTML = resultsHTML;
}

function clearSearch() {
  document.getElementById('searchInput').value = '';
  document.getElementById('searchType').value = '';
  document.getElementById('searchStatus').value = '';
  document.getElementById('searchProgress').value = '';
  
  document.getElementById('resultsContainer').innerHTML = `
    <div class="text-center text-muted py-4">
      <i class="fas fa-search fa-3x mb-3"></i>
      <p>Enter search terms to find projects</p>
    </div>
  `;
}

// Auto-search on input
document.addEventListener('DOMContentLoaded', function() {
  const searchInput = document.getElementById('searchInput');
  if (searchInput) {
    searchInput.addEventListener('input', performSearch);
  }
  
  const filters = ['searchType', 'searchStatus', 'searchProgress'];
  filters.forEach(filterId => {
    const element = document.getElementById(filterId);
    if (element) {
      element.addEventListener('change', performSearch);
    }
  });
});
</script>