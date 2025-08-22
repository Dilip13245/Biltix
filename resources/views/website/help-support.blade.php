@extends('website.layout.app')

@section('title', 'Riverside Commercial Complex - Help & Support')

@section('content')
<div class="content-header border-0 shadow-none mb-4">
  <h2>Help & Support</h2>
  <p>Need assistance? Send us a message and we'll get back to you.</p>
</div>
<section class="px-md-4">
  <div class="container-fluid">
    <div class="row">
      <!-- Help & Support Section -->
      <div class="col-12 mb-4 wow fadeInUp" data-wow-delay="0.3s">
        <div class="card B_shadow">
          <div class="border-bottom pb-3 px-md-4 px-2 py-3 py-md-4">
            <h5 class="fw-semibold black_color mb-3">
              <svg width="18" height="19" class="me-1" viewBox="0 0 18 19" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path
                  d="M9 1.9375C4.96055 1.9375 1.6875 5.21055 1.6875 9.25V10.6562C1.6875 11.1238 1.31133 11.5 0.84375 11.5C0.376172 11.5 0 11.1238 0 10.6562V9.25C0 4.27891 4.02891 0.25 9 0.25C13.9711 0.25 18 4.27891 18 9.25V14.316C18 16.0246 16.6148 17.4098 14.9027 17.4098L11.025 17.4062C10.7332 17.909 10.1883 18.25 9.5625 18.25H8.4375C7.50586 18.25 6.75 17.4941 6.75 16.5625C6.75 15.6309 7.50586 14.875 8.4375 14.875H9.5625C10.1883 14.875 10.7332 15.216 11.025 15.7188L14.9062 15.7223C15.6832 15.7223 16.3125 15.093 16.3125 14.316V9.25C16.3125 5.21055 13.0395 1.9375 9 1.9375ZM5.0625 7.5625H5.625C6.24727 7.5625 6.75 8.06523 6.75 8.6875V12.625C6.75 13.2473 6.24727 13.75 5.625 13.75H5.0625C3.82148 13.75 2.8125 12.741 2.8125 11.5V9.8125C2.8125 8.57148 3.82148 7.5625 5.0625 7.5625ZM12.9375 7.5625C14.1785 7.5625 15.1875 8.57148 15.1875 9.8125V11.5C15.1875 12.741 14.1785 13.75 12.9375 13.75H12.375C11.7527 13.75 11.25 13.2473 11.25 12.625V8.6875C11.25 8.06523 11.7527 7.5625 12.375 7.5625H12.9375Z"
                  fill="#4477C4" />
              </svg>
              {{ __('messages.help_support') }}
            </h5>
          </div>
          <div class="card-body p-md-4">
            <form id="supportForm">
              @csrf
              <div class="mb-3">
                <label for="fullName" class="form-label fw-medium">Full Name</label>
                <input type="text" class="form-control Input_control" id="fullName" name="full_name" required
                  placeholder="Enter your full name">
              </div>

              <div class="mb-3">
                <label for="email" class="form-label fw-medium">Email Address</label>
                <input type="email" class="form-control Input_control" id="email" name="email" required
                  placeholder="Enter your email address">
              </div>

              <div class="mb-4">
                <label for="message" class="form-label fw-medium">Message</label>
                <textarea class="form-control Input_control" id="message" name="message" rows="4" required
                  placeholder="Describe your issue or question in detail..."></textarea>
              </div>

              <button type="submit" class="btn orange_btn w-100">
                <i class="fas fa-paper-plane me-2"></i>Submit Request
              </button>
            </form>

            <div class="quick-help-links">
              <h6 class="fw-bold mb-3">Quick Help</h6>
              <a href="#" onclick="showFAQ()"><i class="fas fa-question-circle"></i> Frequently Asked Questions</a>
              <a href="#" onclick="showDocumentation()"><i class="fas fa-file-alt"></i> User Documentation</a>
              <a href="#" onclick="showTutorials()"><i class="fas fa-video"></i> Video Tutorials</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

<script>
// Support Form Handler
document.addEventListener('DOMContentLoaded', function() {
  const supportForm = document.getElementById('supportForm');
  if (supportForm) {
    supportForm.addEventListener('submit', function(e) {
      e.preventDefault();
      
      // Show loading state
      const submitBtn = this.querySelector('.btn.orange_btn');
      const originalText = submitBtn.innerHTML;
      submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Sending...';
      submitBtn.disabled = true;
      
      // Simulate form submission
      setTimeout(() => {
        alert('Support request submitted successfully! We will get back to you within 24 hours.');
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
        supportForm.reset();
      }, 2000);
    });
  }
});

function showFAQ() {
  alert('FAQ section would open here with common questions and answers.');
}

function showDocumentation() {
  alert('User documentation would open here with detailed guides.');
}

function showTutorials() {
  alert('Video tutorials section would open here.');
}
</script>

@endsection