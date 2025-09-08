// Simple HTML5 Canvas Drawing System
let canvas, ctx, isDrawing = false;
let currentTool = 'pen';
let currentColor = '#ff0000';
let currentSize = 3;
let backgroundImage = null;
let drawingHistory = [];
let historyStep = -1;

let drawingConfig = {
    title: 'Drawing',
    saveButtonText: 'Save',
    onSave: null,
    mode: 'blank'
};

let currentFiles = [];
let currentFileIndex = 0;
let fileBackgrounds = []; // Store background for each file

function initializeDrawing(config = {}) {
    drawingConfig = { ...drawingConfig, ...config };
    
    document.getElementById('drawingModalTitle').textContent = drawingConfig.title;
    document.getElementById('saveButtonText').textContent = drawingConfig.saveButtonText;
    
    canvas = document.getElementById('canvas');
    ctx = canvas.getContext('2d');
    
    // Set canvas size
    canvas.width = 800;
    canvas.height = 600;
    
    // Make canvas responsive
    canvas.style.maxWidth = '100%';
    canvas.style.height = 'auto';
    
    // Set initial background
    ctx.fillStyle = 'white';
    ctx.fillRect(0, 0, canvas.width, canvas.height);
    
    // Save initial state
    saveState();
    
    // Add event listeners for mouse
    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);
    canvas.addEventListener('click', handleCanvasClick);
    
    // Add touch support for mobile
    canvas.addEventListener('touchstart', handleTouch);
    canvas.addEventListener('touchmove', handleTouch);
    canvas.addEventListener('touchend', handleTouchEnd);
    
    // Set up save button
    const saveBtn = document.getElementById('saveDrawingBtn');
    saveBtn.onclick = function() {
        if (drawingConfig.onSave) {
            if (currentFiles.length > 1) {
                // Save current file first
                saveCurrentFile();
                
                // Return all marked up images
                const allImageData = currentFiles.map(fileData => fileData.imageData || canvas.toDataURL('image/png'));
                drawingConfig.onSave(allImageData);
            } else {
                // Single file
                const dataURL = canvas.toDataURL('image/png');
                drawingConfig.onSave(dataURL);
            }
        }
    };
}

function loadMultipleFiles(files) {
    currentFiles = Array.from(files).map(file => ({ 
        file, 
        imageData: null
    }));
    currentFileIndex = 0;
    fileBackgrounds = new Array(currentFiles.length).fill(null);
    
    if (currentFiles.length > 1) {
        document.getElementById('fileNavigation').style.display = 'block';
        updateFileNavigation();
    }
    
    loadCurrentFile();
}

function loadImageToCanvas(file) {
    if (!canvas) return;
    
    const reader = new FileReader();
    reader.onload = function(e) {
        const img = new Image();
        img.onload = function() {
            // Clear canvas
            ctx.fillStyle = 'white';
            ctx.fillRect(0, 0, canvas.width, canvas.height);
            
            // Calculate scaling
            const scaleX = canvas.width / img.width;
            const scaleY = canvas.height / img.height;
            const scale = Math.min(scaleX, scaleY);
            
            const scaledWidth = img.width * scale;
            const scaledHeight = img.height * scale;
            const x = (canvas.width - scaledWidth) / 2;
            const y = (canvas.height - scaledHeight) / 2;
            
            // Draw image
            ctx.drawImage(img, x, y, scaledWidth, scaledHeight);
            
            // Store background image for current file
            backgroundImage = ctx.getImageData(0, 0, canvas.width, canvas.height);
            if (currentFiles.length > 0) {
                fileBackgrounds[currentFileIndex] = ctx.getImageData(0, 0, canvas.width, canvas.height);
            }
            
            // Save state
            saveState();
        };
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
}

function loadCurrentFile() {
    if (currentFiles.length === 0) return;
    
    const currentFileData = currentFiles[currentFileIndex];
    
    if (currentFileData.imageData) {
        // Load saved image data
        const img = new Image();
        img.onload = function() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0);
            
            // Restore background for this file
            if (fileBackgrounds[currentFileIndex]) {
                backgroundImage = fileBackgrounds[currentFileIndex];
            }
        };
        img.src = currentFileData.imageData;
    } else {
        // Load fresh file
        loadImageToCanvas(currentFileData.file);
    }
    
    updateFileNavigation();
}

function updateFileNavigation() {
    if (currentFiles.length <= 1) return;
    
    document.getElementById('fileCounter').textContent = `${currentFileIndex + 1} / ${currentFiles.length}`;
    document.getElementById('currentFileName').textContent = currentFiles[currentFileIndex].file.name;
    document.getElementById('prevFileBtn').disabled = currentFileIndex === 0;
    document.getElementById('nextFileBtn').disabled = currentFileIndex === currentFiles.length - 1;
}

function previousFile() {
    if (currentFileIndex > 0) {
        saveCurrentFile();
        currentFileIndex--;
        loadCurrentFile();
    }
}

function nextFile() {
    if (currentFileIndex < currentFiles.length - 1) {
        saveCurrentFile();
        currentFileIndex++;
        loadCurrentFile();
    }
}

function saveCurrentFile() {
    if (currentFiles.length > 0) {
        currentFiles[currentFileIndex].imageData = canvas.toDataURL();
    }
}

function setTool(tool) {
    currentTool = tool;
    document.querySelectorAll('.btn-outline-primary').forEach(btn => btn.classList.remove('active'));
    
    const mobileBtn = document.getElementById(tool + 'Tool');
    const desktopBtn = document.getElementById(tool + 'ToolDesktop');
    
    if (mobileBtn) mobileBtn.classList.add('active');
    if (desktopBtn) desktopBtn.classList.add('active');
    
    // Update cursor
    canvas.style.cursor = tool === 'pen' ? 'crosshair' : 'pointer';
}

function startDrawing(e) {
    if (currentTool !== 'pen') return;
    
    isDrawing = true;
    const coords = getCanvasCoordinates(e);
    
    ctx.beginPath();
    ctx.moveTo(coords.x, coords.y);
}

function draw(e) {
    if (!isDrawing || currentTool !== 'pen') return;
    
    const coords = getCanvasCoordinates(e);
    
    ctx.lineWidth = currentSize;
    ctx.lineCap = 'round';
    ctx.strokeStyle = currentColor;
    
    ctx.lineTo(coords.x, coords.y);
    ctx.stroke();
    ctx.beginPath();
    ctx.moveTo(coords.x, coords.y);
}

function stopDrawing() {
    if (isDrawing) {
        isDrawing = false;
        saveState();
    }
}

function handleTouch(e) {
    e.preventDefault();
    const touch = e.touches[0];
    const mouseEvent = new MouseEvent(e.type === 'touchstart' ? 'mousedown' : 
                                     e.type === 'touchmove' ? 'mousemove' : 'mouseup', {
        clientX: touch.clientX,
        clientY: touch.clientY
    });
    canvas.dispatchEvent(mouseEvent);
}

function handleTouchEnd(e) {
    e.preventDefault();
    
    // Handle drawing end
    stopDrawing();
    
    // Handle shape creation for non-pen tools
    if (currentTool !== 'pen' && e.changedTouches && e.changedTouches[0]) {
        const touch = e.changedTouches[0];
        const coords = getCanvasCoordinates({
            clientX: touch.clientX,
            clientY: touch.clientY
        });
        
        switch(currentTool) {
            case 'circle':
                drawCircle(coords.x, coords.y);
                break;
            case 'arrow':
                drawArrow(coords.x, coords.y);
                break;
            case 'text':
                addText(coords.x, coords.y);
                break;
        }
        
        saveState();
    }
}

function getCanvasCoordinates(e) {
    const rect = canvas.getBoundingClientRect();
    const scaleX = canvas.width / rect.width;
    const scaleY = canvas.height / rect.height;
    return {
        x: (e.clientX - rect.left) * scaleX,
        y: (e.clientY - rect.top) * scaleY
    };
}

function handleCanvasClick(e) {
    if (currentTool === 'pen') return;
    
    const coords = getCanvasCoordinates(e);
    
    switch(currentTool) {
        case 'circle':
            drawCircle(coords.x, coords.y);
            break;
        case 'arrow':
            drawArrow(coords.x, coords.y);
            break;
        case 'text':
            addText(coords.x, coords.y);
            break;
    }
    
    saveState();
}

function drawCircle(x, y) {
    ctx.beginPath();
    ctx.arc(x, y, 30, 0, 2 * Math.PI);
    ctx.strokeStyle = currentColor;
    ctx.lineWidth = currentSize;
    ctx.stroke();
}

function drawArrow(x, y) {
    const endX = x + 60;
    const endY = y;
    
    ctx.beginPath();
    ctx.moveTo(x, y);
    ctx.lineTo(endX, endY);
    ctx.lineTo(endX - 10, endY - 5);
    ctx.moveTo(endX, endY);
    ctx.lineTo(endX - 10, endY + 5);
    ctx.strokeStyle = currentColor;
    ctx.lineWidth = currentSize;
    ctx.stroke();
}

function addText(x, y) {
    const text = prompt('Enter text:');
    if (text && text.trim()) {
        ctx.font = '16px Arial';
        ctx.fillStyle = currentColor;
        ctx.textBaseline = 'top';
        
        // Handle multi-line text
        const lines = text.split('\n');
        lines.forEach((line, index) => {
            ctx.fillText(line, x, y + (index * 20));
        });
    }
}

function updateColor() {
    const colorPicker = document.getElementById('colorPicker') || document.getElementById('colorPickerDesktop');
    currentColor = colorPicker ? colorPicker.value : '#ff0000';
}

function updateSize() {
    const brushSize = document.getElementById('brushSize');
    currentSize = brushSize ? parseInt(brushSize.value) : 3;
}

function saveState() {
    historyStep++;
    if (historyStep < drawingHistory.length) {
        drawingHistory.length = historyStep;
    }
    drawingHistory.push(canvas.toDataURL());
}

function undoLastAction() {
    console.log('Undo called');
    
    // Use same logic as clear - restore background and remove drawings
    const currentBackground = fileBackgrounds[currentFileIndex] || backgroundImage;
    
    if (currentBackground) {
        // Restore background image only
        ctx.putImageData(currentBackground, 0, 0);
        console.log('Background restored for undo on file', currentFileIndex);
    } else {
        // Clear to white background
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        console.log('Canvas cleared to white for undo');
    }
    
    saveState();
}

function clearCanvas() {
    console.log('Clear canvas called');
    
    // Use background for current file if available
    const currentBackground = fileBackgrounds[currentFileIndex] || backgroundImage;
    
    if (currentBackground) {
        // Restore background image only
        ctx.putImageData(currentBackground, 0, 0);
        console.log('Background restored for file', currentFileIndex);
    } else {
        // Clear to white background
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        console.log('Canvas cleared to white');
    }
    
    saveState();
}

function openDrawingModal(config) {
    currentFiles = [];
    currentFileIndex = 0;
    backgroundImage = null;
    fileBackgrounds = [];
    drawingHistory = [];
    historyStep = -1;
    
    document.getElementById('fileNavigation').style.display = 'none';
    
    const modal = new bootstrap.Modal(document.getElementById('drawingModal'));
    modal.show();
    
    document.getElementById('drawingModal').addEventListener('shown.bs.modal', function() {
        initializeDrawing(config);
        setTool('pen');
        
        // Add event listeners
        const colorPickers = document.querySelectorAll('#colorPicker, #colorPickerDesktop');
        const brushSize = document.getElementById('brushSize');
        
        colorPickers.forEach(picker => {
            picker.addEventListener('change', updateColor);
        });
        
        if (brushSize) {
            brushSize.addEventListener('input', updateSize);
        }
        
        // Initialize color and size
        updateColor();
        updateSize();
        
    }, { once: true });
}

// Make functions globally accessible
window.clearCanvas = clearCanvas;
window.undoLastAction = undoLastAction;
window.setTool = setTool;
window.previousFile = previousFile;
window.nextFile = nextFile;
window.openDrawingModal = openDrawingModal;
window.loadMultipleFiles = loadMultipleFiles;
window.loadImageToCanvas = loadImageToCanvas;