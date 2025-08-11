<!-- Plan Viewer Modal -->
<div class="modal fade" id="planViewerModal" tabindex="-1" aria-labelledby="planViewerModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-fullscreen">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="planViewerModalLabel">
          <i class="fas fa-drafting-compass me-2"></i>Plan Viewer
        </h5>
        <div class="d-flex align-items-center gap-2">
          <button class="btn btn-sm btn-outline-primary" onclick="zoomIn()">
            <i class="fas fa-search-plus"></i>
          </button>
          <button class="btn btn-sm btn-outline-primary" onclick="zoomOut()">
            <i class="fas fa-search-minus"></i>
          </button>
          <button class="btn btn-sm btn-outline-primary" onclick="resetZoom()">
            <i class="fas fa-expand-arrows-alt"></i>
          </button>
          <button class="btn btn-sm orange_btn" onclick="downloadPlan()">
            <i class="fas fa-download me-1"></i>Download
          </button>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
      </div>
      <div class="modal-body p-0">
        <div class="row h-100">
          <div class="col-md-9 p-0">
            <div id="planViewerContainer" class="h-100 position-relative overflow-auto bg-light">
              <img id="planImage" src="" alt="Plan" class="img-fluid" style="cursor: grab; transition: transform 0.3s;">
            </div>
          </div>
          <div class="col-md-3 border-start">
            <div class="p-3">
              <h6 class="fw-bold mb-3">Plan Information</h6>
              
              <div class="mb-3">
                <strong>Name:</strong>
                <p id="planInfoName" class="mb-2">-</p>
              </div>
              
              <div class="mb-3">
                <strong>Type:</strong>
                <p id="planInfoType" class="mb-2">-</p>
              </div>
              
              <div class="mb-3">
                <strong>Version:</strong>
                <p id="planInfoVersion" class="mb-2">-</p>
              </div>
              
              <div class="mb-3">
                <strong>File Size:</strong>
                <p id="planInfoSize" class="mb-2">-</p>
              </div>
              
              <div class="mb-3">
                <strong>Last Updated:</strong>
                <p id="planInfoUpdated" class="mb-2">-</p>
              </div>

              <hr>
              
              <h6 class="fw-bold mb-3">Tools</h6>
              <div class="d-grid gap-2">
                <button class="btn btn-sm btn-outline-primary" onclick="addAnnotation()">
                  <i class="fas fa-sticky-note me-1"></i>Add Note
                </button>
                <button class="btn btn-sm btn-outline-primary" onclick="measureTool()">
                  <i class="fas fa-ruler me-1"></i>Measure
                </button>
                <button class="btn btn-sm btn-outline-primary" onclick="printPlan()">
                  <i class="fas fa-print me-1"></i>Print
                </button>
                <button class="btn btn-sm btn-outline-danger" onclick="reportIssue()">
                  <i class="fas fa-exclamation-triangle me-1"></i>Report Issue
                </button>
              </div>

              <hr>
              
              <h6 class="fw-bold mb-3">Recent Comments</h6>
              <div id="planComments">
                <div class="mb-3">
                  <div class="bg-light p-2 rounded">
                    <strong class="small">John Smith</strong>
                    <small class="text-muted d-block">2 hours ago</small>
                    <p class="small mb-0 mt-1">Dimensions look correct for the main entrance.</p>
                  </div>
                </div>
              </div>
              
              <div class="mt-3">
                <textarea class="form-control form-control-sm" rows="2" placeholder="Add a comment..." id="planComment"></textarea>
                <button class="btn btn-sm orange_btn mt-2 w-100" onclick="addPlanComment()">
                  <i class="fas fa-comment me-1"></i>Add Comment
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<script>
let currentZoom = 1;
let isDragging = false;
let startX, startY, scrollLeft, scrollTop;

function zoomIn() {
  currentZoom = Math.min(currentZoom * 1.2, 3);
  document.getElementById('planImage').style.transform = `scale(${currentZoom})`;
}

function zoomOut() {
  currentZoom = Math.max(currentZoom / 1.2, 0.5);
  document.getElementById('planImage').style.transform = `scale(${currentZoom})`;
}

function resetZoom() {
  currentZoom = 1;
  document.getElementById('planImage').style.transform = `scale(${currentZoom})`;
}

function downloadPlan() {
  const planImage = document.getElementById('planImage');
  const link = document.createElement('a');
  link.href = planImage.src;
  link.download = document.getElementById('planInfoName').textContent + '.jpg';
  link.click();
}

function addAnnotation() {
  alert('Annotation tool would be implemented here');
}

function measureTool() {
  alert('Measurement tool would be implemented here');
}

function printPlan() {
  const planImage = document.getElementById('planImage');
  const printWindow = window.open('', '_blank');
  printWindow.document.write(`
    <html>
      <head><title>Print Plan</title></head>
      <body style="margin:0; text-align:center;">
        <img src="${planImage.src}" style="max-width:100%; height:auto;">
      </body>
    </html>
  `);
  printWindow.document.close();
  printWindow.print();
}

function reportIssue() {
  alert('Issue reporting form would open here');
}

function addPlanComment() {
  const comment = document.getElementById('planComment').value;
  if (comment.trim()) {
    const commentsDiv = document.getElementById('planComments');
    const newCommentHTML = `
      <div class="mb-3">
        <div class="bg-light p-2 rounded">
          <strong class="small">You</strong>
          <small class="text-muted d-block">Just now</small>
          <p class="small mb-0 mt-1">${comment}</p>
        </div>
      </div>
    `;
    commentsDiv.insertAdjacentHTML('beforeend', newCommentHTML);
    document.getElementById('planComment').value = '';
  }
}

// Pan functionality
document.addEventListener('DOMContentLoaded', function() {
  const container = document.getElementById('planViewerContainer');
  const image = document.getElementById('planImage');
  
  image.addEventListener('mousedown', (e) => {
    isDragging = true;
    image.style.cursor = 'grabbing';
    startX = e.pageX - container.offsetLeft;
    startY = e.pageY - container.offsetTop;
    scrollLeft = container.scrollLeft;
    scrollTop = container.scrollTop;
  });
  
  image.addEventListener('mouseleave', () => {
    isDragging = false;
    image.style.cursor = 'grab';
  });
  
  image.addEventListener('mouseup', () => {
    isDragging = false;
    image.style.cursor = 'grab';
  });
  
  image.addEventListener('mousemove', (e) => {
    if (!isDragging) return;
    e.preventDefault();
    const x = e.pageX - container.offsetLeft;
    const y = e.pageY - container.offsetTop;
    const walkX = (x - startX) * 2;
    const walkY = (y - startY) * 2;
    container.scrollLeft = scrollLeft - walkX;
    container.scrollTop = scrollTop - walkY;
  });
});
</script>