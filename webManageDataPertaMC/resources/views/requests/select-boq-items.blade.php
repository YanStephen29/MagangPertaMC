<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                    Select BOQ Items for Request
                </h2>
                <p class="text-sm text-gray-600 mt-1">Project: {{ $project->title_project }} ({{ $project->no_IO }})</p>
            </div>
            <div class="mt-3 sm:mt-0">
                <a href="{{ route('projects.tools.index', $project) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to Tools
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="w-full px-3 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">



                    <!-- Selection Summary -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mb-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-green-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012-2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                                <span class="text-green-800 font-medium">Items choosen: <span id="selectedCount">0</span></span>
                            </div>
                            <div class="flex gap-2">
                                <button type="button" id="selectAllBtn" class="text-green-600 hover:text-green-800 text-sm font-medium">
                                    Select All Item Available
                                </button>
                                <span class="text-gray-400">|</span>
                                <button type="button" id="deselectAllBtn" class="text-red-600 hover:text-red-800 text-sm font-medium">
                                    Delete All Selection
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Form -->
                    <form action="{{ route('requests.process-boq-selection', $document->no_request) }}" method="POST" id="boqSelectionForm">
                        @csrf
                        
                        @php
                            // Filter sections that have selectable details (with harga_satuan, quantity, unit)
                            $sectionsWithSelectableDetails = $boq->sections->filter(function($section) {
                                return $section->details->filter(function($detail) {
                                    return isset($detail->harga_satuan) && isset($detail->quantity) && !empty($detail->unit);
                                })->count() > 0;
                            });
                        @endphp

                        @if($sectionsWithSelectableDetails->count() > 0)
                            <div class="bg-white rounded-lg border border-gray-200 overflow-hidden">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gradient-to-r from-green-50 to-blue-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-bold text-green-700 uppercase tracking-wider border-r border-gray-200">Description</th>
                                                <th class="px-6 py-3 text-right text-xs font-bold text-green-700 uppercase tracking-wider border-r border-gray-200">Available Qty</th>
                                                <th class="px-6 py-3 text-right text-xs font-bold text-green-700 uppercase tracking-wider border-r border-gray-200">Price/Unit</th>
                                                <th class="px-6 py-3 text-center text-xs font-bold text-green-700 uppercase tracking-wider w-16">
                                                    <input type="checkbox" id="selectAllCheckbox" class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500 focus:ring-2">
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-100">
                                            @foreach($sectionsWithSelectableDetails as $sectionIndex => $section)
                                                {{-- Section Row --}}
                                                <tr class="section-row">
                                                    <td class="px-6 py-4 whitespace-nowrap border-r border-gray-200">
                                                        <div class="flex items-center">
                                                            @php
                                                                // Ambil root details (parent_no = null) yang selectable
                                                                $rootDetails = $section->details()
                                                                    ->whereNull('parent_no')
                                                                    ->whereNotNull('harga_satuan')
                                                                    ->whereNotNull('quantity') 
                                                                    ->whereNotNull('unit')
                                                                    ->orderBy('no')
                                                                    ->get();
                                                            @endphp
                                                            @if($rootDetails->count() > 0)
                                                                <button type="button" class="section-toggle mr-3" 
                                                                        onclick="toggleSection({{ $section->id }})" 
                                                                        id="section-btn-{{ $section->id }}">
                                                                    <svg class="w-4 h-4 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                                    </svg>
                                                                </button>
                                                            @else
                                                                <div class="w-8 mr-3"></div>
                                                            @endif
                                                            <div class="font-bold text-gray-900 text-base">
                                                                {{ $sectionIndex + 1 }}. {{ $section->nama }}
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right border-r border-gray-200">
                                                        <span class="text-sm text-gray-500">-</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right border-r border-gray-200">
                                                        <div class="text-sm font-medium text-green-600">
                                                            {{ $section->formatted_total_harga }}
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                                        <span class="text-gray-400">Section</span>
                                                    </td>
                                                </tr>

                                                {{-- Root Details (parent_no = null) - Initially Hidden --}}
                                                @foreach($rootDetails as $detailIndex => $detail)
                                                    <tr class="detail-row level-1 hidden" 
                                                        data-section="{{ $section->id }}" 
                                                        data-detail="{{ $detail->no }}"
                                                        id="detail-{{ $detail->no }}">
                                                        <td class="px-6 py-3 whitespace-nowrap border-r border-gray-200">
                                                            <div class="flex items-center" style="padding-left: 24px;">
                                                                @php
                                                                    // Cek apakah detail ini punya children
                                                                    $children = $detail->children()
                                                                        ->whereNotNull('harga_satuan')
                                                                        ->whereNotNull('quantity')
                                                                        ->whereNotNull('unit')
                                                                        ->get();
                                                                @endphp
                                                                @if($children->count() > 0)
                                                                    <button type="button" class="detail-toggle mr-3" 
                                                                            onclick="toggleDetail({{ $detail->no }})" 
                                                                            id="detail-btn-{{ $detail->no }}">
                                                                        <svg class="w-3 h-3 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                                                        </svg>
                                                                    </button>
                                                                @else
                                                                    <div class="w-6 mr-3"></div>
                                                                @endif
                                                                <span class="text-sm font-medium text-green-600 bg-green-50 px-2 py-1 rounded mr-3">
                                                                    {{ $sectionIndex + 1 }}.{{ $detailIndex + 1 }}
                                                                </span>
                                                                <div class="text-sm font-medium text-gray-900">
                                                                    {{ $detail->nama_detail }}
                                                                </div>
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-3 whitespace-nowrap text-right border-r border-gray-200">
                                                            <div class="text-sm @if($detail->getAvailableQuantity() <= 0) text-red-600 font-semibold @else text-gray-900 @endif">
                                                                {{ number_format($detail->getAvailableQuantity()) }} {{ $detail->unit }}
                                                                @if($detail->getAvailableQuantity() <= 0)
                                                                    <span class="text-xs text-red-700 bg-red-100 px-1 rounded ml-1">OUT OF STOCK</span>
                                                                @elseif($detail->getAvailableQuantity() < $detail->quantity)
                                                                    <span class="text-xs text-red-500">({{ number_format($detail->quantity - $detail->getAvailableQuantity()) }} used)</span>
                                                                @endif
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-3 whitespace-nowrap text-right border-r border-gray-200">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
                                                            </div>
                                                        </td>
                                                        <td class="px-6 py-3 whitespace-nowrap text-center">
                                                            @if($detail->harga_satuan > 0)
                                                                <input type="checkbox" name="selected_details[]" value="{{ $detail->no }}" 
                                                                       class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500"
                                                                       @if($detail->getAvailableQuantity() <= 0) data-warning="out-of-stock" @endif>
                                                                @if($detail->getAvailableQuantity() <= 0)
                                                                    <div class="text-xs text-orange-600 mt-1">⚠️ Will be HOLD</div>
                                                                @endif
                                                            @else
                                                                <span class="text-gray-400 text-sm">N/A</span>
                                                            @endif
                                                        </td>
                                                    </tr>

                                                    {{-- Children Details (recursive) --}}
                                                    @if($children->count() > 0)
                                                        @foreach($children as $childIndex => $child)
                                                            @include('partials.detail-children', [
                                                                'detail' => $child,
                                                                'sectionIndex' => $sectionIndex + 1,
                                                                'parentNumber' => ($sectionIndex + 1) . '.' . ($detailIndex + 1),
                                                                'childIndex' => $childIndex + 1,
                                                                'level' => 2,
                                                                'project' => $project
                                                            ])
                                                        @endforeach
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @else
                            <div class="text-center py-12 bg-gray-50 rounded-lg border border-gray-200">
                                <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-gray-100 mb-4">
                                    <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                </div>
                                <h3 class="mt-2 text-lg font-medium text-gray-900">There Are No Items Available for Selection</h3>
                                <p class="mt-1 text-sm text-gray-500">This BOQ does not have any details with complete price/unit, quantity, and unit information.</p>
                            </div>
                        @endif

                        <!-- Form Actions -->
                        <div class="mt-8 flex flex-col sm:flex-row justify-end gap-4">
                            <a href="{{ route('projects.tools.index', $project) }}" 
                               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md transition-colors duration-200 text-center">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-2 rounded-md shadow-md transform hover:scale-105 transition-all duration-200 disabled:opacity-50 disabled:cursor-not-allowed"
                                    id="submitBtn" disabled>
                                Continue to Request Form
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

<style>
.section-details.hidden,
.detail-children.hidden {
    display: none !important;
}

/* Button styles untuk toggle arrows */
.section-toggle, .detail-toggle {
    display: inline-flex !important;
    align-items: center;
    justify-content: center;
    padding: 4px;
    border: none;
    background: transparent;
    cursor: pointer !important;
    border-radius: 4px;
    transition: all 0.2s ease;
    min-width: 24px;
    min-height: 24px;
}

.section-toggle:hover, .detail-toggle:hover {
    background-color: rgba(59, 130, 246, 0.1);
    transform: scale(1.1);
}

.section-toggle:active, .detail-toggle:active {
    transform: scale(0.95);
}

.section-toggle svg, .detail-toggle svg {
    pointer-events: none;
    transition: transform 0.2s ease;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('input[name="selected_details[]"]:not([disabled])');
    const allCheckboxes = document.querySelectorAll('input[name="selected_details[]"]');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const selectedCountSpan = document.getElementById('selectedCount');
    const submitBtn = document.getElementById('submitBtn');
    const selectAllBtn = document.getElementById('selectAllBtn');
    const deselectAllBtn = document.getElementById('deselectAllBtn');

    // Store unit information for each detail
    const detailUnits = {};
    @foreach($sectionsWithSelectableDetails as $section)
        @php
            $selectableDetails = $section->details->filter(function($detail) {
                return isset($detail->harga_satuan) && isset($detail->quantity) && !empty($detail->unit) && $detail->harga_satuan > 0;
            });
        @endphp
        @foreach($selectableDetails as $detail)
            detailUnits['{{ $detail->no }}'] = '{{ $detail->unit }}';
        @endforeach
    @endforeach

    let selectedUnit = null; // Track the unit of selected items
    let validationWarning = null; // Reference to warning element

    function showUnitWarning(message) {
        hideUnitWarning();
        validationWarning = document.createElement('div');
        validationWarning.className = 'bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4';
        validationWarning.innerHTML = `
            <div class="flex items-center">
                <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
                <div>
                    <h4 class="text-yellow-800 font-medium">⚠️ Peringatan Unit</h4>
                    <p class="text-yellow-700 text-sm mt-1">${message}</p>
                </div>
            </div>
        `;
        
        // Insert after the selection summary
        const selectionSummary = document.querySelector('.bg-green-50');
        selectionSummary.insertAdjacentElement('afterend', validationWarning);
    }

    function hideUnitWarning() {
        if (validationWarning) {
            validationWarning.remove();
            validationWarning = null;
        }
    }

    function showUnitInfo() {
        hideUnitWarning();
        if (selectedUnit) {
            const infoElement = document.createElement('div');
            infoElement.className = 'bg-blue-50 border border-blue-200 rounded-lg p-4 mb-4';
            infoElement.innerHTML = `
                <div class="flex items-center">
                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div>
                        <h4 class="text-blue-800 font-medium">✅ Consistent Unit</h4>
                        <p class="text-blue-700 text-sm mt-1">All selected items use the unit: <strong>${selectedUnit}</strong></p>
                    </div>
                </div>
            `;
            
            const selectionSummary = document.querySelector('.bg-green-50');
            selectionSummary.insertAdjacentElement('afterend', infoElement);
            validationWarning = infoElement; // Store reference for cleanup
        }
    }

    function updateSelection() {
        const checkedBoxes = document.querySelectorAll('input[name="selected_details[]"]:checked');
        const selectedCount = checkedBoxes.length;
        
        selectedCountSpan.textContent = selectedCount;
        
        // Check unit consistency
        let isUnitConsistent = true;
        let currentUnit = null;
        
        checkedBoxes.forEach(checkbox => {
            const detailNo = checkbox.value;
            const unit = detailUnits[detailNo];
            
            if (currentUnit === null) {
                currentUnit = unit;
            } else if (currentUnit !== unit) {
                isUnitConsistent = false;
            }
        });

        if (selectedCount === 0) {
            selectedUnit = null;
            submitBtn.disabled = true;
            hideUnitWarning();
        } else if (!isUnitConsistent) {
            selectedUnit = null;
            submitBtn.disabled = true;
            showUnitWarning('The selected items have different units. Please select items with the same unit to combine them.');
        } else {
            selectedUnit = currentUnit;
            submitBtn.disabled = false;
            showUnitInfo();
        }
        
        // Update select all checkbox state
        if (selectAllCheckbox) {
            if (selectedCount === 0) {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = false;
            } else if (selectedCount === checkboxes.length) {
                selectAllCheckbox.checked = true;
                selectAllCheckbox.indeterminate = false;
            } else {
                selectAllCheckbox.checked = false;
                selectAllCheckbox.indeterminate = true;
            }
        }
    }

    // Add event listeners to all checkboxes with unit validation
    allCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const detailNo = this.value;
            const unit = detailUnits[detailNo];
            
            if (this.checked) {
                // If this is the first selection, set the selected unit
                if (selectedUnit === null) {
                    selectedUnit = unit;
                }
                // If unit doesn't match, prevent selection
                else if (unit !== selectedUnit) {
                    this.checked = false;
                    showUnitWarning(`This item has a unit of "${unit}" which is different from the already selected unit "${selectedUnit}". Please select items with the same unit.`);
                    return; // Don't update selection
                }
            }
            
            updateSelection();
        });
    });

    // Select all checkbox functionality (in table header) - with unit validation
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener('change', function() {
            const isChecked = this.checked;
            
            if (isChecked) {
                // Use the same logic as selectAllBtn
                const itemsByUnit = {};
                checkboxes.forEach(checkbox => {
                    const detailNo = checkbox.value;
                    const unit = detailUnits[detailNo];
                    if (!itemsByUnit[unit]) itemsByUnit[unit] = [];
                    itemsByUnit[unit].push(checkbox);
                });
                
                // Find the unit with the most items
                let maxUnit = null;
                let maxCount = 0;
                Object.keys(itemsByUnit).forEach(unit => {
                    if (itemsByUnit[unit].length > maxCount) {
                        maxCount = itemsByUnit[unit].length;
                        maxUnit = unit;
                    }
                });
                
                // Select all items with the most common unit
                if (maxUnit) {
                    checkboxes.forEach(checkbox => {
                        const detailNo = checkbox.value;
                        const unit = detailUnits[detailNo];
                        checkbox.checked = (unit === maxUnit);
                    });
                    
                    if (maxCount < checkboxes.length) {
                        showUnitWarning(`Dipilih ${maxCount} items dengan unit "${maxUnit}". ${checkboxes.length - maxCount} items dengan unit lain tidak dipilih karena unit berbeda.`);
                    }
                }
            } else {
                // Uncheck all
                checkboxes.forEach(checkbox => {
                    checkbox.checked = false;
                });
            }
            
            updateSelection();
        });
    }

    // Select all button functionality (only items with consistent units)
    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            // Group items by unit
            const itemsByUnit = {};
            checkboxes.forEach(checkbox => {
                const detailNo = checkbox.value;
                const unit = detailUnits[detailNo];
                if (!itemsByUnit[unit]) itemsByUnit[unit] = [];
                itemsByUnit[unit].push(checkbox);
            });
            
            // Find the unit with the most items
            let maxUnit = null;
            let maxCount = 0;
            Object.keys(itemsByUnit).forEach(unit => {
                if (itemsByUnit[unit].length > maxCount) {
                    maxCount = itemsByUnit[unit].length;
                    maxUnit = unit;
                }
            });
            
            // Select all items with the most common unit
            if (maxUnit) {
                checkboxes.forEach(checkbox => {
                    const detailNo = checkbox.value;
                    const unit = detailUnits[detailNo];
                    checkbox.checked = (unit === maxUnit);
                });
                
                if (maxCount < checkboxes.length) {
                    showUnitWarning(`Choose ${maxCount} items with the unit "${maxUnit}". ${checkboxes.length - maxCount} items with different units were not selected.`);
                }
            }
            
            updateSelection();
        });
    }

    // Deselect all functionality
    if (deselectAllBtn) {
        deselectAllBtn.addEventListener('click', function() {
            allCheckboxes.forEach(checkbox => {
                checkbox.checked = false;
            });
            updateSelection();
        });
    }

    // Initial update
    updateSelection();
});

// Simple Toggle Functions
function toggleSection(sectionId) {
    console.log('toggleSection called with ID:', sectionId);
    
    try {
        // Find all root details for this section
        const rootDetails = document.querySelectorAll(`[data-section="${sectionId}"].level-1`);
        const sectionBtn = document.getElementById(`section-btn-${sectionId}`);
        const icon = sectionBtn ? sectionBtn.querySelector('svg') : null;
        
        console.log('Found root details:', rootDetails.length);
        console.log('Section button found:', !!sectionBtn);
        console.log('Icon found:', !!icon);
        
        if (rootDetails.length > 0) {
            const isHidden = rootDetails[0].classList.contains('hidden');
            console.log('Current state - hidden:', isHidden);
            
            rootDetails.forEach(detail => {
                if (isHidden) {
                    detail.classList.remove('hidden');
                    console.log('Showing detail:', detail.dataset.detail);
                } else {
                    detail.classList.add('hidden');
                    console.log('Hiding detail:', detail.dataset.detail);
                    // Also hide all descendants
                    hideAllDescendants(detail.dataset.detail);
                }
            });
            
            // Rotate icon
            if (icon) {
                icon.style.transform = isHidden ? 'rotate(90deg)' : 'rotate(0deg)';
                console.log('Icon rotated to:', isHidden ? '90deg' : '0deg');
            }
            
            console.log(isHidden ? 'Section expanded' : 'Section collapsed');
        } else {
            console.log('No root details found for section:', sectionId);
        }
    } catch (error) {
        console.error('Error in toggleSection:', error);
    }
}

function toggleDetail(detailNo) {
    console.log('toggleDetail called with detailNo:', detailNo);
    
    try {
        // Find direct children of this detail
        const children = document.querySelectorAll(`[data-parent="${detailNo}"]`);
        const detailBtn = document.getElementById(`detail-btn-${detailNo}`);
        const icon = detailBtn ? detailBtn.querySelector('svg') : null;
        
        console.log('Found children:', children.length);
        console.log('Detail button found:', !!detailBtn);
        console.log('Icon found:', !!icon);
        
        if (children.length > 0) {
            const isHidden = children[0].classList.contains('hidden');
            console.log('Current state - hidden:', isHidden);
            
            children.forEach(child => {
                if (isHidden) {
                    child.classList.remove('hidden');
                    console.log('Showing child:', child.dataset.detail);
                } else {
                    child.classList.add('hidden');
                    console.log('Hiding child:', child.dataset.detail);
                    // Also hide all descendants of this child
                    hideAllDescendants(child.dataset.detail);
                }
            });
            
            // Rotate icon
            if (icon) {
                icon.style.transform = isHidden ? 'rotate(90deg)' : 'rotate(0deg)';
                console.log('Icon rotated to:', isHidden ? '90deg' : '0deg');
            }
            
            console.log(isHidden ? 'Detail expanded' : 'Detail collapsed');
        } else {
            console.log('No children found for detail:', detailNo);
        }
    } catch (error) {
        console.error('Error in toggleDetail:', error);
    }
}

function hideAllDescendants(parentDetailNo) {
    // Recursively hide all descendants
    const descendants = document.querySelectorAll(`[data-parent="${parentDetailNo}"]`);
    descendants.forEach(descendant => {
        descendant.classList.add('hidden');
        
        // Reset icon
        const btn = document.getElementById(`detail-btn-${descendant.dataset.detail}`);
        if (btn) {
            const icon = btn.querySelector('svg');
            if (icon) icon.style.transform = 'rotate(0deg)';
        }
        
        // Recursively hide children of this descendant
        hideAllDescendants(descendant.dataset.detail);
    });
}

</script>