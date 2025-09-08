<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Role-Based Permission Tester</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .role-card { border-left: 4px solid #007bff; }
        .role-card.consultant { border-left-color: #28a745; }
        .role-card.contractor { border-left-color: #ffc107; }
        .role-card.project_manager { border-left-color: #17a2b8; }
        .role-card.site_engineer { border-left-color: #fd7e14; }
        .role-card.stakeholder { border-left-color: #6f42c1; }
        
        .permission-badge.full { background: #28a745; }
        .permission-badge.limited { background: #ffc107; color: #000; }
        .permission-badge.view-only { background: #17a2b8; }
        .permission-badge.no-access { background: #dc3545; }
        
        .test-result { margin: 10px 0; padding: 10px; border-radius: 5px; }
        .test-result.pass { background: #d4edda; border: 1px solid #c3e6cb; }
        .test-result.fail { background: #f8d7da; border: 1px solid #f5c6cb; }
        
        .page-section { margin: 20px 0; padding: 15px; border: 1px solid #dee2e6; border-radius: 8px; }
    </style>
</head>
<body>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <h1 class="mb-4"><i class="fas fa-shield-alt text-primary"></i> Role-Based Permission Tester</h1>
                
                <!-- Role Selector -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5><i class="fas fa-user-cog"></i> Select Role to Test</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-2 mb-2">
                                <button class="btn btn-success w-100" onclick="testRole('consultant')">
                                    <i class="fas fa-user-tie"></i><br>Consultant
                                </button>
                            </div>
                            <div class="col-md-2 mb-2">
                                <button class="btn btn-warning w-100" onclick="testRole('contractor')">
                                    <i class="fas fa-hard-hat"></i><br>Contractor
                                </button>
                            </div>
                            <div class="col-md-2 mb-2">
                                <button class="btn btn-info w-100" onclick="testRole('project_manager')">
                                    <i class="fas fa-tasks"></i><br>Project Manager
                                </button>
                            </div>
                            <div class="col-md-2 mb-2">
                                <button class="btn btn-warning w-100" onclick="testRole('site_engineer')">
                                    <i class="fas fa-tools"></i><br>Site Engineer
                                </button>
                            </div>
                            <div class="col-md-2 mb-2">
                                <button class="btn btn-secondary w-100" onclick="testRole('stakeholder')">
                                    <i class="fas fa-eye"></i><br>Stakeholder
                                </button>
                            </div>
                            <div class="col-md-2 mb-2">
                                <button class="btn btn-primary w-100" onclick="testAllRoles()">
                                    <i class="fas fa-users"></i><br>Test All Roles
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Test Results -->
                <div id="testResults" class="d-none">
                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 id="testTitle"><i class="fas fa-clipboard-check"></i> Test Results</h5>
                            <button class="btn btn-sm btn-outline-primary" onclick="exportResults()">
                                <i class="fas fa-download"></i> Export Results
                            </button>
                        </div>
                        <div class="card-body">
                            <div id="resultsContent"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Role permissions matrix (from your config)
        const rolePermissions = {
            'consultant': {
                'projects': ['create', 'edit', 'delete', 'view'],
                'tasks': ['create', 'assign', 'update', 'complete', 'delete', 'view'],
                'plans': ['upload', 'markup', 'annotate', 'view', 'delete'],
                'files': ['upload', 'download', 'delete', 'view'],
                'snags': ['create', 'assign', 'resolve', 'comment', 'view'],
                'inspections': ['create', 'conduct', 'approve', 'view'],
                'daily_logs': ['create', 'edit', 'view'],
                'team': ['add', 'remove', 'assign', 'view']
            },
            'contractor': {
                'projects': ['create', 'edit', 'delete', 'view'],
                'tasks': ['create', 'assign', 'update_status', 'view'],
                'plans': ['upload', 'markup', 'annotate', 'view', 'delete'],
                'files': ['upload', 'download', 'delete', 'view'],
                'snags': ['create', 'assign', 'resolve', 'comment', 'view'],
                'inspections': ['create', 'conduct', 'approve', 'view'],
                'daily_logs': ['create', 'edit', 'view'],
                'team': ['add', 'remove', 'assign', 'view']
            },
            'project_manager': {
                'projects': ['create', 'edit', 'delete', 'view'],
                'tasks': ['create', 'assign', 'update', 'complete', 'delete', 'view'],
                'plans': ['upload', 'markup', 'annotate', 'view', 'delete'],
                'files': ['upload', 'download', 'delete', 'view'],
                'snags': ['create', 'assign', 'resolve', 'comment', 'view'],
                'inspections': ['create', 'conduct', 'approve', 'view'],
                'daily_logs': ['create', 'edit', 'view'],
                'team': ['add', 'remove', 'assign', 'view']
            },
            'site_engineer': {
                'projects': [],
                'tasks': ['view_assigned'],
                'plans': ['annotate', 'view'],
                'files': ['upload_logs', 'view'],
                'snags': ['create', 'view'],
                'inspections': ['create', 'view'],
                'daily_logs': ['create', 'edit', 'view'],
                'team': ['view']
            },
            'stakeholder': {
                'projects': [],
                'tasks': ['view'],
                'plans': ['view'],
                'files': ['download', 'view'],
                'snags': ['view'],
                'inspections': ['view'],
                'daily_logs': ['view'],
                'team': ['view']
            }
        };

        // Pages and their required permissions
        const pagePermissions = {
            'Dashboard': {
                'create_project': 'projects:create'
            },
            'Plans': {
                'upload_plan': 'plans:upload',
                'delete_plan': 'plans:delete',
                'markup_plan': 'plans:markup'
            },
            'Tasks': {
                'create_task': 'tasks:create',
                'assign_task': 'tasks:assign',
                'update_status': 'tasks:update_status'
            },
            'Inspections': {
                'create_inspection': 'inspections:create',
                'conduct_inspection': 'inspections:conduct'
            },
            'Snags': {
                'create_snag': 'snags:create',
                'resolve_snag': 'snags:resolve'
            },
            'Daily Logs': {
                'create_log': 'daily_logs:create',
                'edit_log': 'daily_logs:edit'
            },
            'Project Files': {
                'upload_file': 'files:upload',
                'delete_file': 'files:delete'
            },
            'Team Members': {
                'add_member': 'team:add',
                'remove_member': 'team:remove'
            }
        };

        function hasPermission(role, module, action) {
            const permissions = rolePermissions[role];
            if (!permissions || !permissions[module]) return false;
            return permissions[module].includes(action);
        }

        function testRole(role) {
            document.getElementById('testResults').classList.remove('d-none');
            document.getElementById('testTitle').innerHTML = `<i class="fas fa-clipboard-check"></i> Test Results for: <span class="badge bg-primary">${role.replace('_', ' ').toUpperCase()}</span>`;
            
            let html = '';
            
            // Test each page
            for (const [pageName, permissions] of Object.entries(pagePermissions)) {
                html += `<div class="page-section">`;
                html += `<h6><i class="fas fa-file-alt"></i> ${pageName} Page</h6>`;
                
                for (const [actionName, permission] of Object.entries(permissions)) {
                    const [module, action] = permission.split(':');
                    const canAccess = hasPermission(role, module, action);
                    
                    html += `<div class="test-result ${canAccess ? 'pass' : 'fail'}">`;
                    html += `<i class="fas fa-${canAccess ? 'check' : 'times'}"></i> `;
                    html += `<strong>${actionName.replace('_', ' ').toUpperCase()}:</strong> `;
                    html += `<span class="badge ${canAccess ? 'bg-success' : 'bg-danger'}">${canAccess ? 'ALLOWED' : 'DENIED'}</span>`;
                    html += `<small class="text-muted ms-2">(${permission})</small>`;
                    html += `</div>`;
                }
                
                html += `</div>`;
            }
            
            document.getElementById('resultsContent').innerHTML = html;
        }

        function testAllRoles() {
            document.getElementById('testResults').classList.remove('d-none');
            document.getElementById('testTitle').innerHTML = `<i class="fas fa-clipboard-check"></i> Complete Role Comparison`;
            
            let html = '<div class="table-responsive">';
            html += '<table class="table table-bordered table-hover">';
            html += '<thead class="table-dark">';
            html += '<tr><th>Page / Action</th>';
            
            // Header with roles
            for (const role of Object.keys(rolePermissions)) {
                html += `<th class="text-center">${role.replace('_', ' ').toUpperCase()}</th>`;
            }
            html += '</tr></thead><tbody>';
            
            // Test each page and action
            for (const [pageName, permissions] of Object.entries(pagePermissions)) {
                for (const [actionName, permission] of Object.entries(permissions)) {
                    const [module, action] = permission.split(':');
                    
                    html += '<tr>';
                    html += `<td><strong>${pageName}</strong><br><small class="text-muted">${actionName.replace('_', ' ')}</small></td>`;
                    
                    // Check each role
                    for (const role of Object.keys(rolePermissions)) {
                        const canAccess = hasPermission(role, module, action);
                        html += `<td class="text-center">`;
                        html += `<span class="badge ${canAccess ? 'bg-success' : 'bg-danger'}">`;
                        html += `<i class="fas fa-${canAccess ? 'check' : 'times'}"></i> ${canAccess ? 'YES' : 'NO'}`;
                        html += `</span></td>`;
                    }
                    html += '</tr>';
                }
            }
            
            html += '</tbody></table></div>';
            
            // Add summary
            html += '<div class="mt-4"><h6>Summary:</h6>';
            html += '<div class="row">';
            for (const role of Object.keys(rolePermissions)) {
                let allowedCount = 0;
                let totalCount = 0;
                
                for (const [pageName, permissions] of Object.entries(pagePermissions)) {
                    for (const [actionName, permission] of Object.entries(permissions)) {
                        const [module, action] = permission.split(':');
                        totalCount++;
                        if (hasPermission(role, module, action)) allowedCount++;
                    }
                }
                
                const percentage = Math.round((allowedCount / totalCount) * 100);
                html += `<div class="col-md-2 mb-2">`;
                html += `<div class="card role-card ${role}">`;
                html += `<div class="card-body text-center">`;
                html += `<h6>${role.replace('_', ' ').toUpperCase()}</h6>`;
                html += `<div class="h4 text-primary">${allowedCount}/${totalCount}</div>`;
                html += `<div class="progress">`;
                html += `<div class="progress-bar" style="width: ${percentage}%">${percentage}%</div>`;
                html += `</div></div></div></div>`;
            }
            html += '</div></div>';
            
            document.getElementById('resultsContent').innerHTML = html;
        }

        function exportResults() {
            const results = document.getElementById('resultsContent').innerHTML;
            const blob = new Blob([`
                <!DOCTYPE html>
                <html><head><title>Role Permission Test Results</title>
                <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
                </head><body><div class="container py-4">
                <h1>Role Permission Test Results</h1>
                ${results}
                </div></body></html>
            `], {type: 'text/html'});
            
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'role-permission-test-results.html';
            a.click();
            URL.revokeObjectURL(url);
        }

        // Auto-test all roles on page load
        document.addEventListener('DOMContentLoaded', function() {
            // You can uncomment this to auto-run tests
            // testAllRoles();
        });
    </script>
</body>
</html>