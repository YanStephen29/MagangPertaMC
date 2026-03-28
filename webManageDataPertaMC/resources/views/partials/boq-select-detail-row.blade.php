{{-- BOQ Select Detail Row with Full Hierarchy - Same as manage-detail-row but with checkboxes --}}
@php
    $indentLevel = ($level - 1) * 24; // 24px per level
    // Use sequential numbering instead of database 'no' field for proper ordering
    $currentNumber = $parentNumber ? $parentNumber . '.' . $detailIndex : ($sectionIndex + 1) . '.' . $detailIndex;
    
    // Check if this detail is selectable (has harga_satuan, quantity, unit)
    $isSelectable = isset($detail->harga_satuan) && isset($detail->quantity) && !empty($detail->unit);
    
    // Calculate usage data (only if selectable)
    $totalQuantity = $detail->quantity ?? 0;
    $availableQuantity = $isSelectable ? $detail->getAvailableQuantity() : $totalQuantity;
    $usedQuantity = $totalQuantity - $availableQuantity;
    $isAvailable = $availableQuantity > 0;
@endphp

{{-- Only show this row if it's selectable --}}
@if($isSelectable)
<tr class="detail-row hover:bg-green-50 transition-colors duration-200 border-l-4 border-green-200 {{ !$isAvailable ? 'opacity-50' : '' }} {{ isset($isChild) && $isChild ? 'detail-children hidden' : '' }}" 
    data-detail-no="{{ $detail->no }}"
    data-level="{{ $level }}"
    data-current-number="{{ $currentNumber }}"
    data-section-id="{{ $sectionId }}"
    data-parent="{{ $parentNumber ?? '' }}"
    {{ isset($isChild) && $isChild ? 'id=detail-child-' . $sectionId . '-' . str_replace('.', '-', $parentNumber) . '-' . $detail->no : '' }}>
    
    <td class="px-6 py-3 whitespace-nowrap border-r border-gray-200">
        <div class="flex items-center" style="padding-left: {{ $indentLevel }}px;">
            {{-- Toggle button for children (if any) --}}
            @php
                $selectableChildren = $detail->children ? $detail->children->filter(function($child) {
                    return isset($child->harga_satuan) && isset($child->quantity) && !empty($child->unit);
                }) : collect();
            @endphp
            
            @if($selectableChildren->count() > 0)
                <button type="button" class="detail-toggle mr-3 p-1 text-gray-400 hover:text-green-600 hover:bg-green-100 rounded-full focus:outline-none transition-all duration-200"
                        data-current-number="{{ $currentNumber }}" data-section-id="{{ $sectionId }}">
                    <svg class="w-3 h-3 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            @else
                <div class="w-6 mr-3"></div>
            @endif
            
            {{-- Level indicator and numbering --}}
            <div class="flex items-center mr-3">
                @if($level > 1)
                    <div class="flex items-center mr-2 text-gray-400">
                        @for($i = 2; $i <= $level; $i++)
                            <span class="mr-1 text-gray-300">│</span>
                        @endfor
                        <span class="mr-2 text-green-400">├─</span>
                    </div>
                @endif
                
                <span class="text-sm font-medium text-green-600 bg-green-50 px-2 py-1 rounded">
                    {{ $currentNumber }}
                </span>
            </div>
            
            {{-- Detail name --}}
            <div class="text-sm font-medium text-gray-900">
                {{ $detail->nama_detail }}
                @if($selectableChildren->count() > 0)
                    <span class="ml-2 text-xs text-gray-500">({{ $selectableChildren->count() }} sub-items)</span>
                @endif
                
                {{-- Status indicators --}}
                @if(!$isAvailable)
                    <div class="text-xs text-red-600 mt-1">
                        ⚠️ Stok habis
                    </div>
                @elseif($isSelectable && $availableQuantity <= ($totalQuantity * 0.2))
                    <div class="text-xs text-orange-600 mt-1">
                        ⚠️ Stok terbatas ({{ number_format($availableQuantity) }} tersisa)
                    </div>
                @endif
            </div>
        </div>
    </td>
    
    <td class="px-6 py-3 whitespace-nowrap text-right border-r border-gray-200">
        <div class="text-sm text-gray-900">
            {{ number_format($availableQuantity) }} / {{ number_format($totalQuantity) }} {{ $detail->unit }}
        </div>
        <div class="text-xs text-gray-500">tersedia / total</div>
    </td>
    
    <td class="px-6 py-3 whitespace-nowrap text-right border-r border-gray-200">
        <div class="text-sm font-medium text-gray-900">
            Rp {{ number_format($detail->harga_satuan, 0, ',', '.') }}
        </div>
    </td>
    
    <td class="px-6 py-3 whitespace-nowrap text-center">
        <label class="inline-flex items-center cursor-pointer">
            <input type="checkbox" 
                   name="selected_details[]" 
                   value="{{ $detail->no }}" 
                   class="detail-checkbox h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500 focus:ring-2 {{ !$isAvailable ? 'cursor-not-allowed' : '' }}" 
                   data-detail="{{ $detail->no }}"
                   {{ !$isAvailable ? 'disabled' : '' }}>
        </label>
    </td>
</tr>
@endif

{{-- Recursively show children if they exist and are selectable --}}
@if($detail->children && $detail->children->count() > 0)
    @php
        $childDetails = $detail->children->filter(function($child) {
            return isset($child->harga_satuan) && isset($child->quantity) && !empty($child->unit);
        })->sortBy('no')->values();
    @endphp
    
    @if($childDetails->count() > 0)
        <tbody id="detail-children-{{ $sectionId }}-{{ str_replace('.', '-', $currentNumber) }}" class="detail-children hidden">
            @foreach($childDetails as $childIndex => $child)
                @include('partials.boq-select-detail-row', [
                    'detail' => $child,
                    'sectionIndex' => $sectionIndex,
                    'detailIndex' => $childIndex + 1,
                    'level' => $level + 1,
                    'sectionId' => $sectionId,
                    'parentNumber' => $currentNumber,
                    'project' => $project,
                    'isChild' => true
                ])
            @endforeach
        </tbody>
    @endif
@endif