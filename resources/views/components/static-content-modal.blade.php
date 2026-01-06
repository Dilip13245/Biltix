<!-- Static Content Modal Component -->
<div class="modal fade" id="staticContentModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <style>
                #staticContentModal .modal-header .btn-close {
                    position: static !important;
                    right: auto !important;
                    top: auto !important;
                    margin: 0 !important;
                }

                #staticContentModal .modal-header {
                    position: relative !important;
                }
            </style>
            <div class="modal-header bg-light border-bottom">
                @if (app()->getLocale() == 'ar')
                    <div class="d-flex justify-content-between align-items-center w-100">
                        <h5 class="modal-title fw-bold" id="staticContentTitle">{{ __('messages.loading') }}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                @else
                    <h5 class="modal-title fw-bold" id="staticContentTitle">{{ __('messages.loading') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                @endif
            </div>
            <div class="modal-body" id="staticContentBody" style="max-height: 70vh; overflow-y: auto;">
                <p>{{ __('messages.loading') }}</p>
            </div>
            <div class="modal-footer border-top bg-light">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    {{ __('messages.close') }}
                </button>
            </div>
        </div>
    </div>
</div>

<style>
    #staticContentBody {
        font-size: 14px;
        line-height: 1.8;
        color: #333;
    }

    #staticContentBody h1,
    #staticContentBody h2,
    #staticContentBody h3,
    #staticContentBody h4,
    #staticContentBody h5,
    #staticContentBody h6 {
        color: #1a1a1a;
        font-weight: 600;
        margin-top: 1.5rem;
        margin-bottom: 0.75rem;
        line-height: 1.4;
    }

    #staticContentBody h1 {
        font-size: 1.75rem;
        border-bottom: 2px solid #007bff;
        padding-bottom: 0.5rem;
    }

    #staticContentBody h2 {
        font-size: 1.5rem;
        border-bottom: 1px solid #dee2e6;
        padding-bottom: 0.5rem;
    }

    #staticContentBody h3 {
        font-size: 1.25rem;
    }

    #staticContentBody p {
        margin-bottom: 1rem;
        text-align: justify;
    }

    #staticContentBody ul,
    #staticContentBody ol {
        margin-bottom: 1rem;
        {{ is_rtl() ? 'margin-right: 1.5rem;' : 'margin-left: 1.5rem;' }}
    }

    #staticContentBody li {
        margin-bottom: 0.5rem;
        line-height: 1.6;
    }

    #staticContentBody strong,
    #staticContentBody b {
        color: #007bff;
        font-weight: 600;
    }

    #staticContentBody em,
    #staticContentBody i {
        color: #666;
        font-style: italic;
    }

    #staticContentBody blockquote {
        border-{{ is_rtl() ? 'right' : 'left' }}: 4px solid #007bff;
        padding-{{ is_rtl() ? 'right' : 'left' }}: 1rem;
        margin: 1rem 0;
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 4px;
        color: #555;
    }

    #staticContentBody table {
        width: 100%;
        border-collapse: collapse;
        margin: 1rem 0;
        font-size: 13px;
    }

    #staticContentBody table th {
        background-color: #007bff;
        color: white;
        padding: 0.75rem;
        text-align: {{ is_rtl() ? 'right' : 'left' }};
        font-weight: 600;
    }

    #staticContentBody table td {
        border: 1px solid #dee2e6;
        padding: 0.75rem;
        text-align: {{ is_rtl() ? 'right' : 'left' }};
    }

    #staticContentBody table tr:nth-child(even) {
        background-color: #f8f9fa;
    }

    #staticContentBody table tr:hover {
        background-color: #e7f3ff;
    }

    #staticContentBody a {
        color: #007bff;
        text-decoration: none;
        border-bottom: 1px dotted #007bff;
    }

    #staticContentBody a:hover {
        color: #0056b3;
        border-bottom-style: solid;
    }

    #staticContentBody hr {
        margin: 2rem 0;
        border: none;
        border-top: 2px solid #dee2e6;
    }

    #staticContentBody code {
        background-color: #f8f9fa;
        padding: 0.2rem 0.4rem;
        border-radius: 3px;
        font-family: 'Courier New', monospace;
        color: #d63384;
    }

    #staticContentBody pre {
        background-color: #f8f9fa;
        padding: 1rem;
        border-radius: 4px;
        overflow-x: auto;
        margin: 1rem 0;
        border: 1px solid #dee2e6;
    }

    #staticContentBody pre code {
        background-color: transparent;
        padding: 0;
        color: #333;
    }

    /* RTL Support */
    [dir="rtl"] #staticContentBody {
        text-align: right;
    }

    [dir="rtl"] #staticContentBody ul,
    [dir="rtl"] #staticContentBody ol {
        margin-left: 0;
        margin-right: 1.5rem;
    }
</style>

<script>
    async function showStaticContent(type) {
        try {
            const response = await api.makeRequest('general/static_content', { type: type }, 'GET');

            if (response.code === 200) {
                document.getElementById('staticContentTitle').textContent = response.data.title;
                document.getElementById('staticContentBody').innerHTML = response.data.content;
                
                const modal = new bootstrap.Modal(document.getElementById('staticContentModal'));
                modal.show();
            } else {
                toastr.error(response.message || '{{ __('messages.failed_to_load_content') }}');
            }
        } catch (error) {
            console.error('Error loading static content:', error);
            toastr.error('{{ __('messages.failed_to_load_content') }}');
        }
    }
</script>
