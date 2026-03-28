@php
    $indentLevel = ($level - 1) * 24; // 24px per level
    $currentNumber = $parentNumber . '.' . $childIndex;
    
    // Cek apakah detail ini punya children
    $children = $detail->children()
        ->whereNotNull('harga_satuan')
        ->whereNotNull('quantity')
        ->whereNotNull('unit')
        ->get();
@endphp

<tr class="detail-row level-{{ $level }} hidden" 
    data-parent="{{ $detail->parent_no }}" 
    data-detail="{{ $detail->no }}"
    id="detail-{{ $detail->no }}">
    <td class="px-6 py-3 whitespace-nowrap border-r border-gray-200">
        <div class="flex items-center" style="padding-left: {{ $indentLevel }}px;">
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
            
            {{-- Level indicators --}}
            @if($level > 2)
                <div class="flex items-center mr-2 text-gray-400">
                    @for($i = 2; $i < $level; $i++)
                        <span class="mr-1 text-gray-300">│</span>
                    @endfor
                    <span class="mr-2 text-green-400">├─</span>
                </div>
            @endif
            
            <span class="text-sm font-medium text-green-600 bg-green-50 px-2 py-1 rounded mr-3">
                {{ $currentNumber }}
            </span>
            <div class="text-sm font-medium text-gray-900">
                {{ $detail->nama_detail }}
            </div>
        </div>
    </td>
    <td class="px-6 py-3 whitespace-nowrap text-right border-r border-gray-200">
        <div class="text-sm text-gray-900">
            {{ number_format($detail->quantity) }} {{ $detail->unit }}
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
                   class="h-4 w-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
        @else
            <span class="text-gray-400 text-sm">N/A</span>
        @endif
    </td>
</tr>

{{-- Recursive children --}}
@if($children->count() > 0)
    @foreach($children as $subChildIndex => $subChild)
        @include('partials.detail-children', [
            'detail' => $subChild,
            'sectionIndex' => $sectionIndex,
            'parentNumber' => $currentNumber,
            'childIndex' => $subChildIndex + 1,
            'level' => $level + 1,
            'project' => $project
        ])
    @endforeach
@endif