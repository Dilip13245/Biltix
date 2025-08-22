// Common Drawing Functionality
let canvas, ctx, isDrawing = false, currentTool = 'pen';
let startX, startY;
let drawingConfig = {
    title: 'Drawing',
    saveButtonText: 'Save',
    onSave: null,
    mode: 'blank' // 'blank' or 'image'
};

// Multiple files and undo functionality
let currentFiles = [];
let currentFileIndex = 0;
let undoStack = [];
let maxUndoSteps = 20;

function initializeDrawing(config = {}) {
    // Merge config
    drawingConfig = { ...drawingConfig, ...config };
    
    // Update modal title and button text
    document.getElementById('drawingModalTitle').textContent = drawingConfig.title;
    document.getElementById('saveButtonText').textContent = drawingConfig.saveButtonText;
    
    // Initialize canvas
    canvas = document.getElementById('canvas');
    ctx = canvas.getContext('2d');
    
    // Set canvas size
    canvas.width = 800;
    canvas.height = 600;
    
    // Set white background for blank mode
    if (drawingConfig.mode === 'blank') {
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
        saveCanvasState();
    }
    
    // Add event listeners
    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);
    
    // Set up save button
    const saveBtn = document.getElementById('saveDrawingBtn');
    saveBtn.onclick = function() {
        if (drawingConfig.onSave) {
            const allImages = currentFiles.map((file, index) => {
                if (index === currentFileIndex) {
                    return canvas.toDataURL();
                }
                return file.markupData || null;
            });
            drawingConfig.onSave(allImages.length > 0 ? allImages : canvas.toDataURL());
        }
    };
}

function loadMultipleFiles(files) {
    currentFiles = Array.from(files).map(file => ({ file, markupData: null }));
    currentFileIndex = 0;
    
    if (currentFiles.length > 1) {
        document.getElementById('fileNavigation').style.display = 'block';
        updateFileNavigation();
    }
    
    loadCurrentFile();
}

function loadImageToCanvas(file) {
    const img = new Image();
    const reader = new FileReader();
    
    reader.onload = function(e) {
        img.onload = function() {
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0);
            saveCanvasState();
        };
        img.src = e.target.result;
    };
    reader.readAsDataURL(file);
}

function loadCurrentFile() {
    if (currentFiles.length === 0) return;
    
    const currentFileData = currentFiles[currentFileIndex];
    
    if (currentFileData.markupData) {
        const img = new Image();
        img.onload = function() {
            canvas.width = img.width;
            canvas.height = img.height;
            ctx.drawImage(img, 0, 0);
            saveCanvasState();
        };
        img.src = currentFileData.markupData;
    } else {
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
        saveCurrentFileMarkup();
        currentFileIndex--;
        loadCurrentFile();
    }
}

function nextFile() {
    if (currentFileIndex < currentFiles.length - 1) {
        saveCurrentFileMarkup();
        currentFileIndex++;
        loadCurrentFile();
    }
}

function saveCurrentFileMarkup() {
    if (currentFiles.length > 0) {
        currentFiles[currentFileIndex].markupData = canvas.toDataURL();
    }
}

function setTool(tool) {
    currentTool = tool;
    document.querySelectorAll('.btn-outline-primary').forEach(btn => btn.classList.remove('active'));
    document.getElementById(tool + 'Tool').classList.add('active');
}

function startDrawing(e) {
    isDrawing = true;
    const rect = canvas.getBoundingClientRect();
    startX = e.clientX - rect.left;
    startY = e.clientY - rect.top;
    
    if (currentTool === 'pen') {
        ctx.beginPath();
        ctx.moveTo(startX, startY);
    }
}

function draw(e) {
    if (!isDrawing) return;
    
    const rect = canvas.getBoundingClientRect();
    const currentX = e.clientX - rect.left;
    const currentY = e.clientY - rect.top;
    
    ctx.strokeStyle = document.getElementById('colorPicker').value;
    ctx.lineWidth = document.getElementById('brushSize').value;
    ctx.lineCap = 'round';
    
    if (currentTool === 'pen') {
        ctx.lineTo(currentX, currentY);
        ctx.stroke();
    }
}

function stopDrawing() {
    if (!isDrawing) return;
    isDrawing = false;
    
    if (currentTool === 'circle') {
        drawCircle();
    } else if (currentTool === 'arrow') {
        drawArrow();
    } else if (currentTool === 'text') {
        addText();
    }
    
    saveCanvasState();
}

function drawCircle() {
    const radius = 30;
    ctx.beginPath();
    ctx.arc(startX, startY, radius, 0, 2 * Math.PI);
    ctx.strokeStyle = document.getElementById('colorPicker').value;
    ctx.lineWidth = document.getElementById('brushSize').value;
    ctx.stroke();
}

function drawArrow() {
    const endX = startX + 50;
    const endY = startY;
    
    ctx.beginPath();
    ctx.moveTo(startX, startY);
    ctx.lineTo(endX, endY);
    ctx.lineTo(endX - 10, endY - 5);
    ctx.moveTo(endX, endY);
    ctx.lineTo(endX - 10, endY + 5);
    ctx.strokeStyle = document.getElementById('colorPicker').value;
    ctx.lineWidth = document.getElementById('brushSize').value;
    ctx.stroke();
}

function addText() {
    const text = prompt('Enter text:');
    if (text) {
        ctx.font = '16px Arial';
        ctx.fillStyle = document.getElementById('colorPicker').value;
        ctx.fillText(text, startX, startY);
    }
}

function saveCanvasState() {
    undoStack.push(canvas.toDataURL());
    if (undoStack.length > maxUndoSteps) {
        undoStack.shift();
    }
}

function undoLastAction() {
    if (undoStack.length > 1) {
        undoStack.pop();
        const previousState = undoStack[undoStack.length - 1];
        
        const img = new Image();
        img.onload = function() {
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.drawImage(img, 0, 0);
        };
        img.src = previousState;
    }
}

function clearCanvas() {
    if (drawingConfig.mode === 'blank') {
        ctx.fillStyle = 'white';
        ctx.fillRect(0, 0, canvas.width, canvas.height);
    } else {
        if (currentFiles.length > 0) {
            loadImageToCanvas(currentFiles[currentFileIndex].file);
        }
    }
    saveCanvasState();
}

function openDrawingModal(config) {
    currentFiles = [];
    currentFileIndex = 0;
    undoStack = [];
    document.getElementById('fileNavigation').style.display = 'none';
    
    const modal = new bootstrap.Modal(document.getElementById('drawingModal'));
    modal.show();
    
    document.getElementById('drawingModal').addEventListener('shown.bs.modal', function() {
        initializeDrawing(config);
        setTool('pen');
    }, { once: true });
}