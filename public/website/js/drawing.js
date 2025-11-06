// Advanced Image Editor with Fabric.js
let canvas, fabricCanvas;
let currentTool = 'pen';
let currentColor = '#ff0000';
let currentSize = 3;
let backgroundImage = null;
let drawingHistory = [];
let historyStep = -1;
let cropMode = false;
let cropRect = null;
let isDrawing = false;
let drawingObject = null;
let startX, startY;

let drawingConfig = {
    title: 'Drawing',
    saveButtonText: 'Save',
    onSave: null,
    mode: 'blank'
};

let currentFiles = [];
let currentFileIndex = 0;
let fileBackgrounds = [];

function initializeDrawing(config = {}) {
    drawingConfig = { ...drawingConfig, ...config };
    
    document.getElementById('drawingModalTitle').textContent = drawingConfig.title;
    document.getElementById('saveButtonText').textContent = drawingConfig.saveButtonText;
    
    canvas = document.getElementById('canvas');
    
    if (fabricCanvas) {
        fabricCanvas.dispose();
    }
    
    fabricCanvas = new fabric.Canvas('canvas', {
        isDrawingMode: false,
        width: 800,
        height: 600,
        backgroundColor: '#ffffff'
    });
    
    fabricCanvas.freeDrawingBrush = new fabric.PencilBrush(fabricCanvas);
    fabricCanvas.freeDrawingBrush.color = currentColor;
    fabricCanvas.freeDrawingBrush.width = currentSize;
    
    fabricCanvas.on('object:modified', () => saveState());
    fabricCanvas.on('path:created', () => saveState());
    fabricCanvas.on('selection:created', handleSelection);
    fabricCanvas.on('selection:updated', handleSelection);
    fabricCanvas.on('selection:cleared', () => {
        document.getElementById('deleteBtnWrapper')?.classList.add('d-none');
    });
    
    // Zoom with mouse wheel
    fabricCanvas.on('mouse:wheel', function(opt) {
        const delta = opt.e.deltaY;
        let zoom = fabricCanvas.getZoom();
        zoom *= 0.999 ** delta;
        if (zoom > 5) zoom = 5;
        if (zoom < 0.5) zoom = 0.5;
        fabricCanvas.zoomToPoint({ x: opt.e.offsetX, y: opt.e.offsetY }, zoom);
        opt.e.preventDefault();
        opt.e.stopPropagation();
    });
    
    saveState();
    
    const saveBtn = document.getElementById('saveDrawingBtn');
    saveBtn.onclick = function() {
        if (drawingConfig.onSave) {
            if (currentFiles.length > 1) {
                saveCurrentFile();
                const allImageData = currentFiles.map((fileData) => {
                    return fileData.imageData || fileData.file;
                });
                drawingConfig.onSave(allImageData);
            } else {
                drawingConfig.onSave(fabricCanvas.toDataURL('image/png'));
            }
        }
    };
}

function handleSelection() {
    document.getElementById('deleteBtnWrapper')?.classList.remove('d-none');
}

function loadMultipleFiles(files) {
    currentFiles = Array.from(files).map(file => ({ file, imageData: null }));
    currentFileIndex = 0;
    fileBackgrounds = new Array(currentFiles.length).fill(null);
    
    if (currentFiles.length > 1) {
        document.getElementById('fileNavigation').style.display = 'block';
        updateFileNavigation();
    }
    
    loadCurrentFile();
}

function loadImageToCanvas(file) {
    if (!fabricCanvas) return;
    
    const reader = new FileReader();
    reader.onload = function(e) {
        fabric.Image.fromURL(e.target.result, function(img) {
            fabricCanvas.clear();
            fabricCanvas.backgroundColor = '#ffffff';
            drawingHistory = [];
            historyStep = -1;
            
            const scale = Math.min(
                fabricCanvas.width / img.width,
                fabricCanvas.height / img.height
            );
            
            img.scale(scale);
            img.set({
                left: (fabricCanvas.width - img.width * scale) / 2,
                top: (fabricCanvas.height - img.height * scale) / 2,
                selectable: false,
                evented: false,
                lockMovementX: true,
                lockMovementY: true
            });
            
            fabricCanvas.add(img);
            fabricCanvas.sendToBack(img);
            fabricCanvas.renderAll();
            
            backgroundImage = img;
            if (currentFiles.length > 0) {
                fileBackgrounds[currentFileIndex] = img;
            }
            
            saveState();
        });
    };
    reader.readAsDataURL(file);
}

function loadCurrentFile() {
    if (currentFiles.length === 0) return;
    
    const currentFileData = currentFiles[currentFileIndex];
    drawingHistory = [];
    historyStep = -1;
    
    if (currentFileData.imageData) {
        fabric.Image.fromURL(currentFileData.imageData, function(img) {
            fabricCanvas.clear();
            fabricCanvas.setBackgroundImage(img, fabricCanvas.renderAll.bind(fabricCanvas), {
                scaleX: fabricCanvas.width / img.width,
                scaleY: fabricCanvas.height / img.height
            });
            
            if (fileBackgrounds[currentFileIndex]) {
                backgroundImage = fileBackgrounds[currentFileIndex];
            }
            saveState();
        });
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
        currentFiles[currentFileIndex].imageData = fabricCanvas.toDataURL();
    }
}

function setTool(tool) {
    currentTool = tool;
    exitCropMode();
    
    document.querySelectorAll('.btn-outline-primary').forEach(btn => btn.classList.remove('active'));
    
    const mobileBtn = document.getElementById(tool + 'Tool');
    const desktopBtn = document.getElementById(tool + 'ToolDesktop');
    
    if (mobileBtn) mobileBtn.classList.add('active');
    if (desktopBtn) desktopBtn.classList.add('active');
    
    fabricCanvas.isDrawingMode = (tool === 'pen');
    fabricCanvas.selection = (tool === 'select');
    
    fabricCanvas.off('mouse:down');
    fabricCanvas.off('mouse:move');
    fabricCanvas.off('mouse:up');
    
    if (tool === 'pen') {
        fabricCanvas.defaultCursor = 'crosshair';
    } else if (tool === 'select') {
        fabricCanvas.defaultCursor = 'default';
    } else if (tool === 'text') {
        fabricCanvas.defaultCursor = 'text';
        fabricCanvas.on('mouse:down', handleTextClick);
    } else {
        fabricCanvas.defaultCursor = 'crosshair';
        fabricCanvas.on('mouse:down', startDrawingShape);
        fabricCanvas.on('mouse:move', drawShape);
        fabricCanvas.on('mouse:up', finishDrawingShape);
    }
}

// function startDrawingShape(o) {
//     if (o.target) return;
    
//     isDrawing = true;
//     const pointer = fabricCanvas.getPointer(o.e);
//     startX = pointer.x;
//     startY = pointer.y;
    
//     if (currentTool === 'circle') {
//         drawingObject = new fabric.Circle({
//             left: startX,
//             top: startY,
//             radius: 1,
//             fill: 'transparent',
//             stroke: currentColor,
//             strokeWidth: currentSize,
//             selectable: false,
//             evented: false
//         });
//     } else if (currentTool === 'rectangle') {
//         drawingObject = new fabric.Rect({
//             left: startX,
//             top: startY,
//             width: 1,
//             height: 1,
//             fill: 'transparent',
//             stroke: currentColor,
//             strokeWidth: currentSize,
//             selectable: false,
//             evented: false
//         });
//     } else if (currentTool === 'line') {
//         drawingObject = new fabric.Line([startX, startY, startX, startY], {
//             stroke: currentColor,
//             strokeWidth: currentSize,
//             selectable: false,
//             evented: false
//         });
//     } else if (currentTool === 'arrow') {
//         drawingObject = new fabric.Line([startX, startY, startX, startY], {
//             stroke: currentColor,
//             strokeWidth: currentSize,
//             selectable: false,
//             evented: false
//         });
//     }
    
//     if (drawingObject) {
//         fabricCanvas.add(drawingObject);
//     }
// }
function startDrawingShape(o) {
    if (o.target) return;

    isDrawing = true;
    const pointer = fabricCanvas.getPointer(o.e);
    startX = pointer.x;
    startY = pointer.y;

    if (currentTool === 'circle') {
        drawingObject = new fabric.Circle({
            left: startX,
            top: startY,
            radius: 1,
            fill: 'transparent',
            stroke: currentColor,
            strokeWidth: currentSize,
            selectable: false,
            evented: false
        });
    } else if (currentTool === 'rectangle') {
        drawingObject = new fabric.Rect({
            left: startX,
            top: startY,
            width: 1,
            height: 1,
            fill: 'transparent',
            stroke: currentColor,
            strokeWidth: currentSize,
            selectable: false,
            evented: false
        });
    } else if (currentTool === 'line' || currentTool === 'arrow') {
        // Center-aligned line
        drawingObject = new fabric.Line([startX, startY, startX, startY], {
            stroke: currentColor,
            strokeWidth: currentSize,
            originX: 'center',
            originY: 'center',
            selectable: false,
            evented: false
        });
    }

    if (drawingObject) {
        fabricCanvas.add(drawingObject);
    }
}

// function drawShape(o) {
//     if (!isDrawing || !drawingObject) return;
    
//     const pointer = fabricCanvas.getPointer(o.e);
    
//     if (currentTool === 'circle') {
//         const radius = Math.sqrt(Math.pow(pointer.x - startX, 2) + Math.pow(pointer.y - startY, 2)) / 2;
//         drawingObject.set({
//             radius: radius,
//             left: startX - radius,
//             top: startY - radius
//         });
//     } else if (currentTool === 'rectangle') {
//         const width = pointer.x - startX;
//         const height = pointer.y - startY;
        
//         if (width < 0) {
//             drawingObject.set({ left: pointer.x });
//         }
//         if (height < 0) {
//             drawingObject.set({ top: pointer.y });
//         }
        
//         drawingObject.set({
//             width: Math.abs(width),
//             height: Math.abs(height)
//         });
//     } else if (currentTool === 'line' || currentTool === 'arrow') {
//         drawingObject.set({
//             x2: pointer.x,
//             y2: pointer.y
//         });
//     }
    
//     fabricCanvas.renderAll();
// }
function drawShape(o) {
    if (!isDrawing || !drawingObject) return;

    const pointer = fabricCanvas.getPointer(o.e);
    const endX = pointer.x;
    const endY = pointer.y;

    if (currentTool === 'circle') {
        const radius = Math.sqrt(Math.pow(endX - startX, 2) + Math.pow(endY - startY, 2)) / 2;
        drawingObject.set({
            radius: radius,
            left: startX - radius,
            top: startY - radius
        });
    } else if (currentTool === 'rectangle') {
        const width = endX - startX;
        const height = endY - startY;
        if (width < 0) drawingObject.set({ left: endX });
        if (height < 0) drawingObject.set({ top: endY });
        drawingObject.set({ width: Math.abs(width), height: Math.abs(height) });
    } else if (currentTool === 'line' || currentTool === 'arrow') {
        // Update line end coordinates in real time
        drawingObject.set({ x2: endX, y2: endY });
    }

    fabricCanvas.renderAll();
}

// function finishDrawingShape() {
//     if (!isDrawing) return;
    
//     isDrawing = false;
    
//     if (drawingObject) {
//         if (currentTool === 'arrow') {
//             const line = drawingObject;
//             const x1 = line.x1;
//             const y1 = line.y1;
//             const x2 = line.x2;
//             const y2 = line.y2;
            
//             // Calculate line direction and length
//             const dx = x2 - x1;
//             const dy = y2 - y1;
//             const lineLength = Math.sqrt(dx * dx + dy * dy);
            
//             if (lineLength < 5) {
//                 drawingObject.setCoords();
//                 fabricCanvas.renderAll();
//                 saveState();
//                 drawingObject = null;
//                 setTool('select');
//                 return;
//             }
            
//             // Arrowhead size proportional to brush size
//             const headlen = Math.max(10, currentSize * 2.5);
            
//             // Normalized direction vector (unit vector along line)
//             const unitX = dx / lineLength;
//             const unitY = dy / lineLength;
            
//             // Perpendicular vector (90° clockwise from line direction)
//             // For a line going right, this should point down in screen coordinates
//             const perpX = unitY;
//             const perpY = -unitX;
            
//             // Arrowhead base position (line ends here, before arrowhead)
//             // Move back by headlen along the line direction
//             const arrowBaseX = x2 - (unitX * headlen);
//             const arrowBaseY = y2 - (unitY * headlen);
            
//             // Arrowhead width (half on each side of center line)
//             const arrowWidth = headlen * 0.5;
            
//             // Calculate arrowhead triangle three points:
//             // 1. Tip at line endpoint (x2, y2)
//             // 2. Left base point (perpendicular offset to one side)
//             // 3. Right base point (perpendicular offset to other side)
//             // Ensure triangle base is perfectly centered on line extension
//             const tipX = x2;
//             const tipY = y2;
            
//             // Calculate base points symmetrically around arrowBase
//             const leftBaseX = arrowBaseX + (perpX * arrowWidth);
//             const leftBaseY = arrowBaseY + (perpY * arrowWidth);
//             const rightBaseX = arrowBaseX - (perpX * arrowWidth);
//             const rightBaseY = arrowBaseY - (perpY * arrowWidth);
            
//             // Remove original line
//             fabricCanvas.remove(drawingObject);
            
//             // Create line that ends at arrowhead base (center of base)
//             const arrowLine = new fabric.Line([x1, y1, arrowBaseX, arrowBaseY], {
//                 stroke: currentColor,
//                 strokeWidth: currentSize,
//                 selectable: false,
//                 evented: false
//             });
            
//             // Create arrowhead triangle using Polygon for better center alignment
//             // Points: tip, left base, right base
//             // Verify base midpoint equals arrowBase for perfect centering
//             const baseMidX = (leftBaseX + rightBaseX) / 2;
//             const baseMidY = (leftBaseY + rightBaseY) / 2;
            
//             const arrowhead = new fabric.Polygon([
//                 {x: tipX, y: tipY},
//                 {x: leftBaseX, y: leftBaseY},
//                 {x: rightBaseX, y: rightBaseY}
//             ], {
//                 fill: currentColor,
//                 stroke: currentColor,
//                 strokeWidth: 0,
//                 selectable: false,
//                 evented: false
//             });
            
//             // Group line and arrowhead - Fabric.js will automatically handle coordinate transformation
//             const group = new fabric.Group([arrowLine, arrowhead], {
//                 selectable: false,
//                 evented: false
//             });
            
//             fabricCanvas.add(group);
//             fabricCanvas.renderAll();
//             group.setCoords();
//             drawingObject = group;
//         } else {
//             drawingObject.setCoords();
//         }
        
//         drawingObject.set({ 
//             selectable: true,
//             evented: true,
//             hasControls: true,
//             hasBorders: true
//         });
        
//         fabricCanvas.renderAll();
//         saveState();
//         drawingObject = null;
        
//         setTool('select');
//     }
// }
function finishDrawingShape() {
    if (!isDrawing) return;
    isDrawing = false;

    if (drawingObject) {
        if (currentTool === 'arrow') {
            const line = drawingObject;
            const { x1, y1, x2, y2 } = line;

            const dx = x2 - x1;
            const dy = y2 - y1;
            const lineLength = Math.sqrt(dx * dx + dy * dy);
            if (lineLength < 5) return;

            const headlen = Math.max(10, currentSize * 2.5);
            const unitX = dx / lineLength;
            const unitY = dy / lineLength;
            const perpX = unitY;
            const perpY = -unitX;

            const arrowBaseX = x2 - (unitX * headlen);
            const arrowBaseY = y2 - (unitY * headlen);
            const arrowWidth = headlen * 0.5;

            const leftBaseX = arrowBaseX + (perpX * arrowWidth);
            const leftBaseY = arrowBaseY + (perpY * arrowWidth);
            const rightBaseX = arrowBaseX - (perpX * arrowWidth);
            const rightBaseY = arrowBaseY - (perpY * arrowWidth);

            fabricCanvas.remove(line);

            const arrowLine = new fabric.Line([x1, y1, arrowBaseX, arrowBaseY], {
                stroke: currentColor,
                strokeWidth: currentSize,
                selectable: false,
                evented: false,
                originX: 'center',
                originY: 'center'
            });

            const arrowhead = new fabric.Polygon([
                { x: x2, y: y2 },
                { x: leftBaseX, y: leftBaseY },
                { x: rightBaseX, y: rightBaseY }
            ], {
                fill: currentColor,
                stroke: currentColor,
                strokeWidth: 0,
                selectable: false,
                evented: false,
                originX: 'center',
                originY: 'center'
            });

            const group = new fabric.Group([arrowLine, arrowhead], {
                selectable: false,
                evented: false,
                originX: 'center',
                originY: 'center'
            });

            fabricCanvas.add(group);
            drawingObject = group;
        }

        drawingObject.set({
            selectable: true,
            evented: true,
            hasControls: true,
            hasBorders: true
        });

        drawingObject.setCoords();
        fabricCanvas.renderAll();
        saveState();
        drawingObject = null;

        setTool('select');
    }
}

function handleTextClick(o) {
    if (o.target) return;
    
    const pointer = fabricCanvas.getPointer(o.e);
    const text = prompt('Enter text:');
    
    if (text && text.trim()) {
        const textObj = new fabric.IText(text, {
            left: pointer.x,
            top: pointer.y,
            fill: currentColor,
            fontSize: 20,
            fontFamily: 'Arial'
        });
        fabricCanvas.add(textObj);
        saveState();
        
        // Auto switch to select tool after adding text (like other drawing tools)
        setTool('select');
    }
}

function updateColor(e) {
    const picker = e ? e.target : (document.getElementById('colorPicker') || document.getElementById('colorPickerDesktop'));
    if (picker) {
        currentColor = picker.value;
        
        // If there's a selected object, update its color
        const activeObject = fabricCanvas.getActiveObject();
        if (activeObject) {
            // For text objects, update fill color
            if (activeObject.type === 'i-text' || activeObject.type === 'text' || activeObject.type === 'textbox') {
                activeObject.set('fill', currentColor);
            } 
            // For shapes (circle, rectangle, line, arrow), update stroke color
            else if (activeObject.type === 'circle' || activeObject.type === 'rect' || 
                     activeObject.type === 'line' || activeObject.type === 'path' || 
                     activeObject.type === 'group') {
                activeObject.set('stroke', currentColor);
                // For groups (like arrow), also update fill if it has triangle
                if (activeObject.type === 'group' && activeObject.getObjects) {
                    activeObject.getObjects().forEach(obj => {
                        if (obj.type === 'path' || obj.type === 'polygon') {
                            obj.set('fill', currentColor);
                        } else {
                            obj.set('stroke', currentColor);
                        }
                    });
                }
            }
            // For free drawing paths
            else if (activeObject.type === 'path') {
                activeObject.set('stroke', currentColor);
            }
            
            fabricCanvas.renderAll();
            saveState();
        }
        
        // Update brush color for pen tool
        if (fabricCanvas && fabricCanvas.freeDrawingBrush) {
            fabricCanvas.freeDrawingBrush.color = currentColor;
        }
        
        // Sync both color pickers
        document.querySelectorAll('#colorPicker, #colorPickerDesktop').forEach(p => {
            if (p !== picker) p.value = currentColor;
        });
    }
}

function updateSize(e) {
    const brushSize = e ? e.target : document.getElementById('brushSize');
    currentSize = brushSize ? parseInt(brushSize.value) : 3;
    
    const sizeDisplay = document.getElementById('brushSizeValue');
    if (sizeDisplay) {
        sizeDisplay.textContent = currentSize;
    }
    
    const brushSizeDot = document.getElementById('brushSizeDot');
    if (brushSize && brushSizeDot) {
        const min = parseInt(brushSize.min) || 1;
        const max = parseInt(brushSize.max) || 20;
        const percentage = ((currentSize - min) / (max - min)) * 100;
        brushSizeDot.style.left = percentage + '%';
    }
    
    if (fabricCanvas && fabricCanvas.freeDrawingBrush) {
        fabricCanvas.freeDrawingBrush.width = currentSize;
    }
    
    const activeObject = fabricCanvas.getActiveObject();
    if (activeObject) {
        if (activeObject.type === 'group') {
            const objects = activeObject.getObjects();
            const lineObj = objects.find(obj => obj.type === 'line');
            const polygonObj = objects.find(obj => obj.type === 'polygon');
            
            if (lineObj && polygonObj) {
                // Arrow: update line stroke and scale polygon
                lineObj.set('strokeWidth', currentSize);
                
                const headlen = Math.max(10, currentSize * 2.5);
                const baseHeadlen = Math.max(10, 3 * 2.5);
                const scaleRatio = headlen / baseHeadlen;
                
                // Reset scale to 1 first, then apply new scale
                polygonObj.set({ scaleX: scaleRatio, scaleY: scaleRatio });
                activeObject.addWithUpdate();
            } else {
                objects.forEach(obj => {
                    if (obj.type === 'line' || obj.type === 'path') {
                        obj.set('strokeWidth', currentSize);
                    }
                });
            }
        } else if (activeObject.type === 'circle' || activeObject.type === 'rect' || 
                   activeObject.type === 'line' || activeObject.type === 'path') {
            activeObject.set('strokeWidth', currentSize);
        }
        
        fabricCanvas.renderAll();
        saveState();
    }
}

function saveState() {
    historyStep++;
    if (historyStep < drawingHistory.length) {
        drawingHistory.length = historyStep;
    }
    drawingHistory.push(JSON.stringify(fabricCanvas.toJSON()));
}

function undoLastAction() {
    if (historyStep > 0) {
        historyStep--;
        fabricCanvas.loadFromJSON(drawingHistory[historyStep], function() {
            fabricCanvas.renderAll();
        });
    }
}

function clearCanvas() {
    fabricCanvas.clear();
    fabricCanvas.backgroundColor = '#ffffff';
    
    if (backgroundImage) {
        fabricCanvas.add(backgroundImage);
        fabricCanvas.sendToBack(backgroundImage);
    }
    
    fabricCanvas.renderAll();
    saveState();
}

function deleteSelected() {
    const activeObjects = fabricCanvas.getActiveObjects();
    if (activeObjects.length) {
        fabricCanvas.discardActiveObject();
        activeObjects.forEach(obj => fabricCanvas.remove(obj));
        fabricCanvas.renderAll();
        saveState();
    }
}

function enterCropMode() {
    if (cropMode) return;
    
    cropMode = true;
    fabricCanvas.isDrawingMode = false;
    fabricCanvas.selection = false;
    
    fabricCanvas.forEachObject(obj => {
        obj.selectable = false;
        obj.evented = false;
    });
    
    cropRect = new fabric.Rect({
        left: 100,
        top: 100,
        width: 600,
        height: 400,
        fill: 'rgba(0,0,0,0.3)',
        stroke: '#fff',
        strokeWidth: 2,
        strokeDashArray: [5, 5],
        selectable: true,
        hasControls: true,
        hasBorders: true
    });
    
    fabricCanvas.add(cropRect);
    fabricCanvas.setActiveObject(cropRect);
    fabricCanvas.renderAll();
    
    document.getElementById('cropControls')?.classList.remove('d-none');
}

function exitCropMode() {
    if (!cropMode) return;
    
    cropMode = false;
    
    if (cropRect) {
        fabricCanvas.remove(cropRect);
        cropRect = null;
    }
    
    fabricCanvas.forEachObject(obj => {
        if (obj !== backgroundImage) {
            obj.selectable = true;
            obj.evented = true;
        }
    });
    
    fabricCanvas.selection = true;
    fabricCanvas.renderAll();
    document.getElementById('cropControls')?.classList.add('d-none');
}

function applyCrop() {
    if (!cropRect) return;
    
    const left = cropRect.left;
    const top = cropRect.top;
    const width = cropRect.width * cropRect.scaleX;
    const height = cropRect.height * cropRect.scaleY;
    
    const croppedDataURL = fabricCanvas.toDataURL({
        left: left,
        top: top,
        width: width,
        height: height
    });
    
    fabricCanvas.clear();
    fabricCanvas.setWidth(width);
    fabricCanvas.setHeight(height);
    
    fabric.Image.fromURL(croppedDataURL, function(img) {
        fabricCanvas.setBackgroundImage(img, fabricCanvas.renderAll.bind(fabricCanvas));
        exitCropMode();
        saveState();
    });
}

function openDrawingModal(config) {
    const drawingModalElement = document.getElementById('drawingModal');
    if (!drawingModalElement) {
        console.error('Drawing modal not found in DOM');
        return;
    }
    
    currentFiles = [];
    currentFileIndex = 0;
    backgroundImage = null;
    fileBackgrounds = [];
    drawingHistory = [];
    historyStep = -1;
    
    const fileNavigation = document.getElementById('fileNavigation');
    if (fileNavigation) {
        fileNavigation.style.display = 'none';
    }
    
    const modal = new bootstrap.Modal(drawingModalElement);
    modal.show();
    
    document.getElementById('drawingModal').addEventListener('shown.bs.modal', function() {
        initializeDrawing(config);
        setTool('pen');
        
        const colorPickers = document.querySelectorAll('#colorPicker, #colorPickerDesktop');
        const brushSize = document.getElementById('brushSize');
        
        colorPickers.forEach(picker => {
            picker.addEventListener('input', updateColor);
            picker.addEventListener('change', updateColor);
        });
        
        if (brushSize) {
            brushSize.addEventListener('input', updateSize);
            brushSize.addEventListener('change', updateSize);
            // Initialize dot position
            updateSize({ target: brushSize });
        }
        
        if (colorPickers.length > 0) {
            currentColor = colorPickers[0].value;
            if (fabricCanvas.freeDrawingBrush) {
                fabricCanvas.freeDrawingBrush.color = currentColor;
            }
        }
        
    }, { once: true });
}

// Global functions
window.clearCanvas = clearCanvas;
window.undoLastAction = undoLastAction;
window.undoLast = undoLastAction;
window.setTool = setTool;
window.previousFile = previousFile;
window.nextFile = nextFile;
window.openDrawingModal = openDrawingModal;
window.loadMultipleFiles = loadMultipleFiles;
window.loadImageToCanvas = loadImageToCanvas;
window.deleteSelected = deleteSelected;
window.enterCropMode = enterCropMode;
window.exitCropMode = exitCropMode;
window.applyCrop = applyCrop;

window.resetZoom = function() {
    fabricCanvas.setZoom(1);
    fabricCanvas.viewportTransform = [1, 0, 0, 1, 0, 0];
    fabricCanvas.renderAll();
};
