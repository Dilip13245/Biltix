// Searchable Dropdown Component
class SearchableDropdown {
    constructor(selectElement, options = {}) {
        this.select = selectElement;
        this.options = {
            placeholder: options.placeholder || 'Search...',
            noResultsText: options.noResultsText || 'No results found',
            ...options
        };
        this.init();
    }

    init() {
        this.createWrapper();
        this.createSearchInput();
        this.createDropdownList();
        this.bindEvents();
        this.populateOptions();
    }

    createWrapper() {
        this.wrapper = document.createElement('div');
        this.wrapper.className = 'searchable-dropdown';
        this.select.parentNode.insertBefore(this.wrapper, this.select);
        this.wrapper.appendChild(this.select);
        this.select.style.display = 'none';
    }

    createSearchInput() {
        this.searchInput = document.createElement('input');
        this.searchInput.type = 'text';
        this.searchInput.className = this.select.className;
        this.searchInput.placeholder = this.options.placeholder;
        this.searchInput.readOnly = true;
        this.wrapper.appendChild(this.searchInput);
    }

    createDropdownList() {
        this.dropdown = document.createElement('div');
        this.dropdown.className = 'searchable-dropdown-list';
        this.dropdown.style.display = 'none';
        this.wrapper.appendChild(this.dropdown);
    }

    populateOptions() {
        this.dropdown.innerHTML = '';
        if (!this.select.options || this.select.options.length === 0) return;

        const options = Array.from(this.select.options);

        options.forEach(option => {
            if (option.value === '') return;

            const item = document.createElement('div');
            item.className = 'searchable-dropdown-item';
            item.textContent = option.textContent;
            item.dataset.value = option.value;

            // Copy data attributes
            if (option.attributes) {
                Array.from(option.attributes).forEach(attr => {
                    if (attr.name.startsWith('data-')) {
                        item.setAttribute(attr.name, attr.value);
                    }
                });
            }

            item.addEventListener('click', () => this.selectOption(option, item));
            this.dropdown.appendChild(item);
        });
    }

    bindEvents() {
        this.searchInput.addEventListener('click', () => this.toggleDropdown());
        this.searchInput.addEventListener('input', () => this.filterOptions());

        document.addEventListener('click', (e) => {
            if (!this.wrapper.contains(e.target)) {
                this.hideDropdown();
            }
        });
    }

    toggleDropdown() {
        if (this.dropdown.style.display === 'none') {
            this.showDropdown();
        } else {
            this.hideDropdown();
        }
    }

    showDropdown() {
        this.dropdown.style.display = 'block';
        this.searchInput.readOnly = false;
        this.searchInput.focus();
    }

    hideDropdown() {
        this.dropdown.style.display = 'none';
        this.searchInput.readOnly = true;
    }

    filterOptions() {
        const searchTerm = this.searchInput.value.toLowerCase();
        const items = this.dropdown.querySelectorAll('.searchable-dropdown-item');
        let hasResults = false;

        items.forEach(item => {
            const text = item.textContent.toLowerCase();
            if (text.includes(searchTerm)) {
                item.style.display = 'block';
                hasResults = true;
            } else {
                item.style.display = 'none';
            }
        });

        if (!hasResults && searchTerm) {
            this.showNoResults();
        } else {
            this.hideNoResults();
        }
    }

    selectOption(option, item) {
        this.select.value = option.value;
        this.searchInput.value = option.textContent;
        this.hideDropdown();

        // Trigger change event
        this.select.dispatchEvent(new Event('change'));
    }

    showNoResults() {
        if (!this.noResultsItem) {
            this.noResultsItem = document.createElement('div');
            this.noResultsItem.className = 'searchable-dropdown-no-results';
            this.noResultsItem.textContent = this.options.noResultsText;
        }
        this.dropdown.appendChild(this.noResultsItem);
    }

    hideNoResults() {
        if (this.noResultsItem && this.noResultsItem.parentNode) {
            this.noResultsItem.parentNode.removeChild(this.noResultsItem);
        }
    }

    updateOptions() {
        this.populateOptions();
    }
}

// CSS Styles
const style = document.createElement('style');
style.textContent = `
.searchable-dropdown {
    position: relative;
}

.searchable-dropdown-list {
    position: absolute;
    top: 100%;
    left: 0;
    right: 0;
    background: white;
    border: 1px solid #ddd;
    border-top: none;
    border-radius: 0 0 4px 4px;
    max-height: 200px;
    overflow-y: auto;
    z-index: 1000;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.searchable-dropdown-item {
    padding: 8px 12px;
    cursor: pointer;
    border-bottom: 1px solid #f0f0f0;
}

.searchable-dropdown-item:hover {
    background-color: #f8f9fa;
}

.searchable-dropdown-item:last-child {
    border-bottom: none;
}

.searchable-dropdown-no-results {
    padding: 8px 12px;
    color: #666;
    font-style: italic;
}
`;
document.head.appendChild(style);

// Auto-initialize function
window.initSearchableDropdowns = function () {
    // Get locale from HTML dir attribute or meta tag
    const isRTL = document.documentElement.getAttribute('dir') === 'rtl';
    const locale = isRTL ? 'ar' : 'en';

    // Get translated placeholder text from data attribute or use default
    document.querySelectorAll('.searchable-select').forEach(select => {
        if (!select.searchableDropdown) {
            // Check if placeholder is set via data attribute
            const placeholder = select.getAttribute('data-placeholder') ||
                (isRTL ? 'بحث...' : 'Search...');
            const noResultsText = select.getAttribute('data-no-results') ||
                (isRTL ? 'لا توجد نتائج' : 'No results found');

            select.searchableDropdown = new SearchableDropdown(select, {
                placeholder: placeholder,
                noResultsText: noResultsText
            });
        }
    });
};

// Export for manual use
window.SearchableDropdown = SearchableDropdown;