<?php
/**
 * Button Analysis Script
 * Analyzes all buttons in the project and generates a report
 */

$projectRoot = __DIR__;
$bladeFiles = [];
$results = [
    'total_buttons' => 0,
    'api_action_buttons' => 0,
    'api_buttons_missing_class' => [],
    'normal_buttons' => 0,
    'form_submit_buttons' => 0,
    'onclick_api_buttons' => 0,
    'protected_buttons' => 0,
    'unprotected_api_buttons' => []
];

// Find all blade files
function findBladeFiles($dir, &$files) {
    if (!is_dir($dir)) {
        return;
    }
    $items = scandir($dir);
    foreach ($items as $item) {
        if ($item == '.' || $item == '..') continue;
        $path = $dir . '/' . $item;
        if (is_dir($path)) {
            if (strpos($path, 'node_modules') === false && 
                strpos($path, 'vendor') === false && 
                strpos($path, '.git') === false) {
                findBladeFiles($path, $files);
            }
        } elseif (pathinfo($path, PATHINFO_EXTENSION) === 'blade.php') {
            $files[] = $path;
        }
    }
}

$viewsPath = $projectRoot . '/resources/views/website';
if (is_dir($viewsPath)) {
    findBladeFiles($viewsPath, $bladeFiles);
} else {
    echo "Error: Views directory not found at: $viewsPath\n";
    echo "Current directory: $projectRoot\n";
    exit(1);
}

echo "🔍 Analyzing " . count($bladeFiles) . " blade files...\n\n";

// API call patterns
$apiPatterns = [
    'api\.(create|add|update|delete|save|submit|upload|download|login|register|complete|mark|resolve|extend|updatePhase|updateActivity|updateManpower|updateSafety|updateSnag|updateInspection|updateProject|listActivities|listManpower|listSafety|getProject|getProfile|getNotifications|getSnags|getTasks|getInspections|getPhases|getUsers|resolveSnag|updateTaskStatus|updateSnag|createSnag|createTask|createInspection|createFolder|uploadFile|createProject|createPhase|addActivity|addManpowerEquipment|addSafetyItem|updateActivity|updateManpowerEquipment|updateSafetyItem|saveActivity|saveManpower|saveSafetyItem|saveActivitiesUpdate|saveManpowerUpdate|saveSafetyUpdate|saveProjectChanges|saveLocationEdit|saveTaskDrawing|saveMarkup|addComment|updateInspectionComment|markInspectionComplete|markAsResolved|addNewImages|downloadPlan|addPlanComment|performSearch|clearSearch|deleteProject|deleteAllNotifications|deleteProjectFromDashboard)',
    'saveSnagWithMarkup|saveSnagWithoutMarkup|saveTaskWithMarkup|saveTaskWithoutMarkup|submitInspectionWithImages|submitInspectionWithoutImages|submitInspectionWithDrawing|uploadFileWithMarkup|createProjectDirectly|createProjectWithMarkup',
    'resolveSnag|changeTaskStatus|changeSnagStatus|proceedWithUpload|applyFilters|applySorting|markAsRead|markNotificationAsRead'
];

// Function call patterns that make API calls
$apiFunctionPatterns = [
    'saveActivity|saveManpower|saveSafetyItem|saveActivitiesUpdate|saveManpowerUpdate|saveSafetyUpdate|saveProjectChanges|saveLocationEdit|saveTaskDrawing|saveMarkup|createFolder|addComment|updateInspectionComment|markInspectionComplete|markAsResolved|addNewImages|downloadPlan|addPlanComment|performSearch|clearSearch|deleteProject|deleteAllNotifications|deleteProjectFromDashboard|resolveSnag|proceedWithUpload|applyFilters|applySorting',
    'saveSnag|saveTask|submitInspection|uploadFile|createProject|createPhase'
];

foreach ($bladeFiles as $file) {
    $content = file_get_contents($file);
    $relativePath = str_replace($projectRoot . '/', '', $file);
    
    // Find all buttons
    preg_match_all('/<button[^>]*>/i', $content, $buttonMatches, PREG_OFFSET_CAPTURE);
    
    foreach ($buttonMatches[0] as $match) {
        $buttonHtml = $match[0];
        $buttonPos = $match[1];
        
        $results['total_buttons']++;
        
        // Check if button has api-action-btn class
        $hasApiActionClass = preg_match('/class=["\'][^"\']*api-action-btn[^"\']*["\']/', $buttonHtml);
        
        // Check if button has data-no-protect
        $hasNoProtect = preg_match('/data-no-protect/', $buttonHtml);
        
        // Check if button opens modal
        $opensModal = preg_match('/data-bs-toggle=["\']modal["\']/', $buttonHtml);
        
        // Check if button is form submit
        $isFormSubmit = preg_match('/type=["\']submit["\']/', $buttonHtml) || 
                       preg_match('/form=["\']/', $buttonHtml);
        
        // Check onclick handlers
        $onclickContent = '';
        if (preg_match('/onclick=["\']([^"\']+)["\']/', $buttonHtml, $onclickMatch)) {
            $onclickContent = $onclickMatch[1];
        }
        
        // Check if onclick contains API calls
        $makesApiCall = false;
        foreach ($apiFunctionPatterns as $pattern) {
            if (preg_match('/' . $pattern . '/i', $onclickContent)) {
                $makesApiCall = true;
                break;
            }
        }
        
        // Get button text/identifier
        $buttonId = '';
        if (preg_match('/id=["\']([^"\']+)["\']/', $buttonHtml, $idMatch)) {
            $buttonId = $idMatch[1];
        }
        
        $buttonText = '';
        if (preg_match('/>([^<]+)</', $buttonHtml, $textMatch)) {
            $buttonText = trim(strip_tags($textMatch[1]));
        }
        
        // Check if form submit button is in a form that makes API calls
        $isApiFormSubmit = false;
        if ($isFormSubmit && !$hasApiActionClass) {
            // Check surrounding context for API calls
            $contextStart = max(0, $buttonPos - 1000);
            $contextEnd = min(strlen($content), $buttonPos + 1000);
            $context = substr($content, $contextStart, $contextEnd - $contextStart);
            
            // Check for form IDs that typically make API calls
            $apiFormIds = ['addTaskForm', 'addSnagForm', 'createInspectionForm', 'addMemberForm', 'addPhotoForm', 'addSafetyChecklistForm', 'editSnagForm', 'uploadFileForm', 'uploadPlanForm', 'createProjectForm', 'createPhaseForm', 'addActivityForm', 'addManpowerForm', 'addSafetyForm'];
            foreach ($apiFormIds as $formId) {
                if (strpos($context, 'id="' . $formId . '"') !== false || strpos($context, "id='" . $formId . "'") !== false) {
                    // Check if form has submit handler with API calls
                    if (preg_match('/\.addEventListener\(["\']submit["\']|\.onsubmit|form.*onsubmit/i', $context)) {
                        // Look for API calls in the form handler
                        foreach ($apiPatterns as $pattern) {
                            if (preg_match('/' . $pattern . '/i', $context)) {
                                $isApiFormSubmit = true;
                                break 2;
                            }
                        }
                    }
                }
            }
        }
        
        // Analyze button
        if ($hasApiActionClass) {
            $results['api_action_buttons']++;
            $results['protected_buttons']++;
        } elseif (($makesApiCall || $isApiFormSubmit) && !$hasNoProtect && !$opensModal) {
            $results['unprotected_api_buttons'][] = [
                'file' => $relativePath,
                'button_html' => substr($buttonHtml, 0, 200),
                'id' => $buttonId,
                'text' => $buttonText,
                'type' => $isFormSubmit ? 'form_submit' : 'onclick_api',
                'line' => substr_count(substr($content, 0, $buttonPos), "\n") + 1
            ];
        }
        
        if ($isFormSubmit) {
            $results['form_submit_buttons']++;
        }
        
        if ($makesApiCall) {
            $results['onclick_api_buttons']++;
        }
        
        if (!$hasApiActionClass && !$makesApiCall && !$isFormSubmit) {
            $results['normal_buttons']++;
        }
    }
}

// Generate report
echo "=" . str_repeat("=", 70) . "\n";
echo "📊 BUTTON ANALYSIS REPORT\n";
echo "=" . str_repeat("=", 70) . "\n\n";

echo "📈 SUMMARY:\n";
echo "   Total Buttons Found: " . $results['total_buttons'] . "\n";
echo "   ✅ API Action Buttons (with api-action-btn class): " . $results['api_action_buttons'] . "\n";
echo "   ⚠️  API Buttons Missing Class: " . count($results['unprotected_api_buttons']) . "\n";
echo "   📝 Form Submit Buttons: " . $results['form_submit_buttons'] . "\n";
echo "   🔘 Onclick API Buttons: " . $results['onclick_api_buttons'] . "\n";
echo "   🔒 Protected Buttons: " . $results['protected_buttons'] . "\n";
echo "   🔓 Normal Buttons: " . $results['normal_buttons'] . "\n\n";

if (count($results['unprotected_api_buttons']) > 0) {
    echo "⚠️  UNPROTECTED API BUTTONS NEEDING FIX:\n";
    echo str_repeat("-", 70) . "\n";
    foreach ($results['unprotected_api_buttons'] as $index => $button) {
        echo ($index + 1) . ". File: " . $button['file'] . "\n";
        echo "   Line: " . $button['line'] . "\n";
        if ($button['id']) {
            echo "   ID: " . $button['id'] . "\n";
        }
        if ($button['text']) {
            echo "   Text: " . substr($button['text'], 0, 50) . "\n";
        }
        echo "   Type: " . $button['type'] . "\n";
        echo "   Button: " . htmlspecialchars(substr($button['button_html'], 0, 100)) . "...\n";
        echo "\n";
    }
} else {
    echo "✅ All API buttons are properly protected!\n\n";
}

echo "=" . str_repeat("=", 70) . "\n";
echo "Report generated successfully!\n";
