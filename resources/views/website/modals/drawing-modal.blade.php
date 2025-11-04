<!-- Common Drawing Modal -->
<div class="modal fade" id="drawingModal" tabindex="-1" aria-labelledby="drawingModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header" style="{{ is_rtl() ? 'flex-direction: row-reverse;' : '' }}">
                <h5 class="modal-title" id="drawingModalLabel">
                    <i class="fas fa-pencil-alt {{ margin_end(2) }}"></i><span
                        id="drawingModalTitle">{{ __('messages.drawing') }}</span>
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0">
                <div class="d-flex h-100 flex-column flex-md-row">
                    <!-- Drawing Tools Sidebar -->
                    <div class="drawing-tools-sidebar bg-light p-2 p-md-3 order-2 order-md-1"
                        style="width: 100%; max-width: 280px; border-top: 1px solid #dee2e6; border-right: none; overflow-y: auto;">
                        <h6 class="fw-bold mb-3 d-none d-md-block">{{ __('messages.drawing_tools') }}</h6>

                        <!-- Tool Selection -->
                        <div class="mb-2 mb-md-3">
                            <div class="btn-group w-100 d-md-none mb-2" role="group">
                                <button type="button" class="btn btn-outline-primary btn-sm active" id="penTool"
                                    onclick="setTool('pen')" title="Pen">
                                    <i class="fas fa-pen"></i>
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="selectTool"
                                    onclick="setTool('select')" title="Select">
                                    <i class="fas fa-mouse-pointer"></i>
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="circleTool"
                                    onclick="setTool('circle')" title="Circle">
                                    <i class="fas fa-circle"></i>
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="rectangleTool"
                                    onclick="setTool('rectangle')" title="Rectangle">
                                    <i class="fas fa-square"></i>
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="arrowTool"
                                    onclick="setTool('arrow')" title="Arrow">
                                    <i class="fas fa-arrow-right"></i>
                                </button>
                                <button type="button" class="btn btn-outline-primary btn-sm" id="textTool"
                                    onclick="setTool('text')" title="Text">
                                    <i class="fas fa-font"></i>
                                </button>
                                <input type="color" class="btn" id="colorPicker" value="#ff0000"
                                    style="width: 40px; padding: 2px;">
                            </div>
                            <div class="d-none d-md-block">
                                <label class="form-label fw-medium">{{ __('messages.tools') }}</label>
                                <div class="btn-group-vertical w-100 mb-2" role="group">
                                    <button type="button" class="btn btn-outline-primary btn-sm active" id="penToolDesktop"
                                        onclick="setTool('pen')">
                                        <i class="fas fa-pen {{ margin_end(2) }}"></i>{{ __('messages.pen') }}
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="selectToolDesktop"
                                        onclick="setTool('select')">
                                        <i class="fas fa-mouse-pointer {{ margin_end(2) }}"></i>Select
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="circleToolDesktop"
                                        onclick="setTool('circle')">
                                        <i class="fas fa-circle {{ margin_end(2) }}"></i>{{ __('messages.circle') }}
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="rectangleToolDesktop"
                                        onclick="setTool('rectangle')">
                                        <i class="fas fa-square {{ margin_end(2) }}"></i>Rectangle
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="lineToolDesktop"
                                        onclick="setTool('line')">
                                        <i class="fas fa-minus {{ margin_end(2) }}"></i>Line
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="arrowToolDesktop"
                                        onclick="setTool('arrow')">
                                        <i class="fas fa-arrow-right {{ margin_end(2) }}"></i>{{ __('messages.arrow') }}
                                    </button>
                                    <button type="button" class="btn btn-outline-primary btn-sm" id="textToolDesktop"
                                        onclick="setTool('text')">
                                        <i class="fas fa-font {{ margin_end(2) }}"></i>{{ __('messages.text') }}
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Color Picker Desktop -->
                        <div class="mb-3 d-none d-md-block">
                            <label for="colorPickerDesktop"
                                class="form-label fw-medium">{{ __('messages.color') }}</label>
                            <input type="color" class="form-control form-control-color" id="colorPickerDesktop"
                                value="#ff0000">
                        </div>

                        <!-- Brush Size -->
                        <div class="mb-3 d-none d-md-block">
                            <label for="brushSize" class="form-label fw-medium">{{ __('messages.brush_size') }}: <span id="brushSizeValue">3</span></label>
                            <input type="range" class="form-range" id="brushSize" min="1" max="20"
                                value="3" style="background: linear-gradient(to right, #0d6efd 0%, #0d6efd 15%, #dee2e6 15%, #dee2e6 100%);">
                            <div class="d-flex justify-content-between">
                                <small>1</small>
                                <small>20</small>
                            </div>
                        </div>

                        <!-- Crop Tool -->
                        <div class="mb-3 d-none d-md-block">
                            <label class="form-label fw-medium">Crop</label>
                            <button type="button" class="btn btn-outline-secondary btn-sm w-100 mb-2" onclick="enterCropMode()">
                                <i class="fas fa-crop {{ margin_end(2) }}"></i>Start Crop
                            </button>
                            <div id="cropControls" class="d-none">
                                <button type="button" class="btn btn-success btn-sm w-100 mb-1" onclick="applyCrop()">
                                    <i class="fas fa-check {{ margin_end(2) }}"></i>Apply Crop
                                </button>
                                <button type="button" class="btn btn-secondary btn-sm w-100" onclick="exitCropMode()">
                                    <i class="fas fa-times {{ margin_end(2) }}"></i>Cancel
                                </button>
                            </div>
                        </div>



                        <!-- Transform -->
                        <div class="mb-3 d-none d-md-block">
                            <label class="form-label fw-medium">{{ __('messages.transform') }}</label>
                            <div class="d-flex gap-1 mb-2">
                                <button type="button" class="btn btn-outline-secondary btn-sm flex-fill" onclick="rotateObject(-90)" title="{{ __('messages.rotate_left') }}">
                                    <i class="fas fa-undo"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm flex-fill" onclick="rotateObject(90)" title="{{ __('messages.rotate_right') }}">
                                    <i class="fas fa-redo"></i>
                                </button>
                            </div>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-outline-secondary btn-sm flex-fill" onclick="flipHorizontal()" title="{{ __('messages.flip_horizontal') }}">
                                    <i class="fas fa-arrows-alt-h"></i>
                                </button>
                                <button type="button" class="btn btn-outline-secondary btn-sm flex-fill" onclick="flipVertical()" title="{{ __('messages.flip_vertical') }}">
                                    <i class="fas fa-arrows-alt-v"></i>
                                </button>
                            </div>
                        </div>

                        <!-- File Navigation -->
                        <div class="mb-3" id="fileNavigation" style="display: none;">
                            <label class="form-label fw-medium">{{ __('messages.files') }}</label>
                            <div class="d-flex gap-2 mb-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="prevFileBtn"
                                    onclick="previousFile()">
                                    <i class="fas fa-chevron-left"></i>
                                </button>
                                <span class="flex-grow-1 text-center small" id="fileCounter">1 / 1</span>
                                <button type="button" class="btn btn-sm btn-outline-secondary" id="nextFileBtn"
                                    onclick="nextFile()">
                                    <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                            <div class="small text-muted text-center" id="currentFileName">image.jpg</div>
                        </div>

                        <!-- Actions -->
                        <div class="mb-2 mb-md-3">
                            <div class="d-flex gap-2 d-md-block"
                                style="{{ is_rtl() ? 'flex-direction: row-reverse;' : '' }}">
                                <button type="button" class="btn btn-danger btn-sm flex-fill mb-2 d-none" id="deleteBtn"
                                    onclick="deleteSelected()">
                                    <i class="fas fa-trash d-md-none"></i><i
                                        class="fas fa-trash {{ margin_end(2) }} d-none d-md-inline"></i><span
                                        class="d-none d-md-inline">Delete</span>
                                </button>
                                <button type="button" class="btn btn-info btn-sm flex-fill mb-2"
                                    onclick="undoLastAction()">
                                    <i class="fas fa-undo d-md-none"></i><i
                                        class="fas fa-undo {{ margin_end(2) }} d-none d-md-inline"></i><span
                                        class="d-none d-md-inline">{{ __('messages.undo') }}</span>
                                </button>
                                <button type="button" class="btn btn-warning btn-sm flex-fill mb-0"
                                    onclick="clearCanvas()">
                                    <i class="fas fa-eraser d-md-none"></i><i
                                        class="fas fa-eraser {{ margin_end(2) }} d-none d-md-inline"></i><span
                                        class="d-none d-md-inline">{{ __('messages.clear_all') }}</span>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Drawing Canvas Area -->
                    <div class="flex-grow-1 d-flex align-items-center justify-content-center bg-white order-1 order-md-2"
                        style="min-height: 300px; overflow: auto;">
                        <canvas id="canvas" width="800" height="600"
                            style="border: 1px solid #dee2e6; max-width: 100%; max-height: 100%;"></canvas>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary"
                    data-bs-dismiss="modal" style="padding: 0.7rem 1.5rem;">{{ __('messages.cancel') }}</button>
                <button type="button" class="btn orange_btn" id="saveDrawingBtn">
                    <i class="fas fa-save {{ margin_end(2) }}"></i><span
                        id="saveButtonText">{{ __('messages.save') }}</span>
                </button>
            </div>
        </div>
    </div>
</div>
