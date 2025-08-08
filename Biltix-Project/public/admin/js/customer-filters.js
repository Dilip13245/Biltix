/**
 * Customer Management Filters
 * Handles AJAX filtering for customer data without page reload
 */

class CustomerFilters {
    constructor() {
        this.initializeEventListeners();
        this.initializeDataTable();
    }

    initializeDataTable() {
        this.table = $('#customer-table').DataTable({
            dom: "<'row mb-2'<'col-sm-12 col-md-4'l><'col-sm-12 col-md-4 text-center'B><'col-sm-12 col-md-4 text-md-end'f>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row mt-2'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
            buttons: [{
                extend: 'copy',
                text: '<i class="fa fa-copy"></i> Copy',
                className: 'btn btn-sm btn-outline-secondary'
            },
            {
                extend: 'csv',
                text: '<i class="fa fa-file-csv"></i> CSV',
                className: 'btn btn-sm btn-outline-primary'
            },
            {
                extend: 'excel',
                text: '<i class="fa fa-file-excel"></i> Excel',
                className: 'btn btn-sm btn-outline-success'
            },
            {
                extend: 'pdf',
                text: '<i class="fa fa-file-pdf"></i> PDF',
                className: 'btn btn-sm btn-outline-danger'
            },
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i> Print',
                className: 'btn btn-sm btn-outline-dark'
            }
            ],
            pageLength: 10,
            language: {
                "searchPlaceholder": "Type Here...",
                "paginate": {
                    "next": "&raquo;",
                    "previous": "&laquo;"
                }
            },
            columnDefs: [{
                targets: [4, 6, 8],
                orderable: false
            }]
        });
    }

    initializeEventListeners() {
        // Apply Filters Button
        $('#apply-filters').on('click', () => {
            this.applyFilters();
        });

        // Clear Filters Button
        $('#clear-filters').on('click', () => {
            this.clearFilters();
        });

        // Enter key on search filter
        $('#search-filter').on('keypress', (e) => {
            if (e.which === 13) {
                this.applyFilters();
            }
        });

        // Auto-apply filters on select change (optional)
        $('#status-filter, #order-filter, #language-filter').on('change', () => {
            // Uncomment the line below if you want auto-apply on select change
            // this.applyFilters();
        });
    }

    applyFilters() {
        const search = $('#search-filter').val();
        const status = $('#status-filter').val();
        const orderCount = $('#order-filter').val();
        const language = $('#language-filter').val();

        // Show loading state
        $('#apply-filters').prop('disabled', true).html('<i class="fas fa-spinner fa-spin me-1"></i> Loading...');

        $.ajax({
            url: '/admin/customers/filter',
            method: 'POST',
            data: {
                search: search,
                status: status,
                order_count: orderCount,
                language: language,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: (response) => {
                if (response.success) {
                    this.updateTable(response.customers);
                    toastr.success(`Found ${response.total} customers`);
                }
            },
            error: () => {
                toastr.error('Error applying filters');
            },
            complete: () => {
                // Reset button state
                $('#apply-filters').prop('disabled', false).html('<i class="fas fa-filter me-1"></i> Apply');
            }
        });
    }

    clearFilters() {
        $('#search-filter').val('');
        $('#status-filter').val('');
        $('#order-filter').val('');
        $('#language-filter').val('');

        // Reload original data
        location.reload();
    }

    updateTable(customers) {
        const tbody = $('#customer-tbody');
        tbody.empty();

        customers.forEach((customer) => {
            const profileImage = customer.profile_image && customer.profile_image !== 'null'
                ? `<img src="/storage/uploads/profile/${customer.profile_image}" class="rounded-circle" width="50" height="50" alt="Profile">`
                : `<img src="https://ui-avatars.com/api/?name=${encodeURIComponent(customer.first_name + ' ' + customer.last_name)}&background=009688&color=fff&size=110&rounded=true&bold=true" class="rounded-circle" width="50" height="50" alt="Avatar">`;

            const lastOrder = customer.orders && customer.orders.length > 0
                ? new Date(customer.orders[customer.orders.length - 1].created_at).toLocaleDateString()
                : 'No orders';

            const statusBadge = customer.is_active
                ? '<span class="badge bg-success">Active</span>'
                : '<span class="badge bg-danger">Inactive</span>';

            const row = `
                <tr class="text-center">
                    <td><strong>CUST${String(customer.id).padStart(3, '0')}</strong></td>
                    <td>${profileImage}</td>
                    <td>${customer.first_name} ${customer.last_name}</td>
                    <td>${customer.email}</td>
                    <td>${customer.phone}</td>
                    <td>${customer.language || '-'}</td>
                    <td>${statusBadge}</td>
                    <td>${lastOrder}</td>
                    <td>
                        <a href="/admin/customers/${customer.id}/orders" class="btn btn-sm btn-outline-info">
                            <i class="fa fa-shopping-cart me-1" data-bs-toggle="tooltip" title="View Orders"></i>
                            (${customer.orders ? customer.orders.length : 0})
                        </a>
                    </td>
                    <td>
                        <a href="/admin/customers/${customer.id}/reviews" class="btn btn-sm btn-outline-warning">
                            <i class="fa fa-star me-1" data-bs-toggle="tooltip" title="View Reviews"></i>
                            (${customer.reviews ? customer.reviews.length : 0})
                        </a>
                    </td>
                    <td>
                        <div class="d-flex flex-row gap-2 justify-content-center align-items-center">
                            <a href="/admin/customers/${customer.id}" class="btn btn-sm btn-outline-primary" title="View Customer">
                                <i class="fa fa-eye"></i>
                            </a>
                            <a href="/admin/customers/${customer.id}/edit" class="btn btn-sm btn-outline-warning" title="Edit Customer">
                                <i class="fa fa-edit"></i>
                            </a>
                            <button class="btn btn-sm btn-outline-danger" title="Delete Customer">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
            `;
            tbody.append(row);
        });

        // Reinitialize tooltips
        $('[data-bs-toggle="tooltip"]').tooltip();
    }
}

// Initialize when document is ready
$(document).ready(function () {
    new CustomerFilters();
}); 