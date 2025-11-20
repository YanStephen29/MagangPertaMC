@php
    $indentLevel = ($level - 1) * 24;
    $currentNumber = $parentNumber ? $parentNumber . '.' . $detailIndex : $sectionIndex . '.' . $detailIndex;
@endphp

<tr class="detail-row hover:bg-red-50 transition-colors duration-200 border-l-4 border-red-200 {{ isset($isChild) && $isChild ? 'detail-children hidden' : '' }}" 
    data-section-id="{{ $sectionId }}" 
    data-current-number="{{ $currentNumber }}"
    data-detail-no="{{ $detail->no }}"
    data-level="{{ $level }}"
    data-parent="{{ $parentNumber ?? '' }}"
    {{ isset($isChild) && $isChild ? 'id=detail-child-' . $sectionId . '-' . str_replace('.', '-', $parentNumber) . '-' . $detail->no : '' }}>
    
    <td class="px-6 py-3 whitespace-nowrap border-r border-gray-200">
        <div class="flex items-center" style="padding-left: {{ $indentLevel }}px;">
            @if($detail->children && $detail->children->count() > 0)
                <button class="detail-toggle mr-3 p-1 text-gray-400 hover:text-red-600 hover:bg-red-100 rounded-full focus:outline-none transition-all duration-200"
                        data-current-number="{{ $currentNumber }}" data-section-id="{{ $sectionId }}">
                    <svg class="w-3 h-3 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                    </svg>
                </button>
            @else
                <div class="w-6 mr-3"></div>
            @endif
            
            <div class="flex items-center mr-3">
                @if($level > 1)
                    <div class="flex items-center mr-2 text-gray-400">
                        @for($i = 2; $i <= $level; $i++)
                            <span class="mr-1 text-gray-300">│</span>
                        @endfor
                        <span class="mr-2 text-red-400">├─</span>
                    </div>
                @endif
                
                <span class="text-sm font-medium text-red-600 bg-red-50 px-2 py-1 rounded">
                    {{ $currentNumber }}
                </span>
            </div>
            
            {{-- Detail name --}}
            <div class="text-sm font-medium text-gray-900">
                {{ $detail->nama_detail }}
                @if($detail->children && $detail->children->count() > 0)
                    <span class="ml-2 text-xs text-gray-500">({{ $detail->children->count() }} sub-items)</span>
                @endif
            </div>
        </div>
    </td>
    
    <td class="px-6 py-3 whitespace-nowrap border-r border-gray-200">
        <div class="text-sm text-gray-700">
            {{ $detail->note ?? '-' }}
        </div>
    </td>
    
    <td class="px-6 py-3 whitespace-nowrap text-right border-r border-gray-200">
        <div class="text-sm text-gray-900">
            {{ $detail->quantity ?? '-' }}
        </div>
    </td>
    
    <td class="px-6 py-3 whitespace-nowrap text-center border-r border-gray-200">
        <div class="text-sm text-gray-900">
            {{ $detail->unit ?? '-' }}
        </div>
    </td>
    
    <td class="px-6 py-3 whitespace-nowrap text-right border-r border-gray-200">
        <div class="text-sm text-gray-900">
            {{ $detail->harga_satuan ? 'Rp ' . number_format($detail->harga_satuan, 0, ',', '.') : '-' }}
        </div>
    </td>
    
    <td class="px-6 py-3 whitespace-nowrap text-right border-r border-gray-200">
        <div class="text-sm font-medium text-green-600">
            {{ $detail->formatted_harga_total ?? '-' }}
        </div>
    </td>
    
    <td class="px-6 py-3 whitespace-nowrap text-right border-r border-gray-200">
        @php
            $usedQuantity = $detail->getActualUsedQuantity();
        @endphp
        <div class="text-sm font-medium {{ $usedQuantity > 0 ? 'text-orange-600' : 'text-gray-500' }}">
            {{ number_format($usedQuantity, 0) }}
        </div>
    </td>
    
    <td class="px-6 py-3 whitespace-nowrap text-right">
        @php
            $remainingFunds = $detail->getRemainingFunds();
        @endphp
        <span class="text-sm font-medium {{ $remainingFunds < 0 ? 'text-red-600' : 'text-purple-600' }}">
            Rp {{ number_format($remainingFunds, 0, ',', '.') }}
        </span>
    </td>
</tr>

{{-- Recursive children rendering --}}
@if($detail->children && $detail->children->count() > 0)
    @php
        // Sort children by 'no' and reset array indexes for proper sequential numbering
        $sortedChildren = $detail->children->sortBy('no')->values();
    @endphp
    @foreach($sortedChildren as $childIndex => $child)
        @include('partials.manage-detail-row', [
            'detail' => $child,
            'sectionIndex' => $sectionIndex,
            'detailIndex' => $childIndex + 1,
            'level' => $level + 1,
            'sectionId' => $sectionId,
            'parentNumber' => $currentNumber,
            'isChild' => true
        ])
    @endforeach
@endif