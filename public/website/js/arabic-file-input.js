// Simple Arabic File Input Localization
document.addEventListener('DOMContentLoaded', function() {
    const isArabic = document.documentElement.getAttribute('dir') === 'rtl';
    
    if (isArabic) {
        // Add CSS for Arabic file inputs
        const style = document.createElement('style');
        style.textContent = `
            [dir="rtl"] input[type="file"]::-webkit-file-upload-button {
                display: none;
            }
            
            [dir="rtl"] input[type="file"]::before {
                content: 'اختر الملفات';
                display: inline-block;
                background: #f8f9fa;
                border: 1px solid #dee2e6;
                border-radius: 4px;
                padding: 6px 12px;
                margin-left: 8px;
                cursor: pointer;
                font-size: 14px;
                color: #495057;
            }
            
            [dir="rtl"] input[type="file"]::after {
                content: 'لم يتم اختيار ملف';
                color: #6c757d;
                font-size: 14px;
                margin-right: 8px;
            }
            
            [dir="rtl"] input[type="file"]:hover::before {
                background: #e9ecef;
            }
        `;
        document.head.appendChild(style);
        
        // Update file input text on change
        document.addEventListener('change', function(e) {
            if (e.target.type === 'file') {
                updateFileInputText(e.target);
            }
        });
    }
});

function updateFileInputText(input) {
    // This will be handled by CSS pseudo-elements
    // The browser will automatically show selected file names
}