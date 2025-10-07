@foreach($details as $index => $detail)
    @php
        $hasChildren = $detail->children && $detail->children->count() > 0;
        $indentLevel = $level * 24; // 24px per level
        // Use sequential index numbering for proper ordering
        $currentNumber = isset($parentNumber) ? $parentNumber . '.' . ($index + 1) : ($index + 1);
    @endphp
    
    <tr class="detail-row hover:bg-red-50 transition-colors duration-200 border-l-4 border-red-200 {{ isset($isChild) && $isChild ? 'detail-children hidden' : '' }}" 
        data-section-id="{{ $section->id }}" 
        data-current-number="{{ $currentNumber }}"
        data-detail-no="{{ $detail->no }}"
        data-level="{{ $level }}"
        data-parent="{{ $parentNumber ?? '' }}">
    
        <td class="px-6 py-3 whitespace-nowrap border-r border-gray-200">
            <div class="flex items-center" style="padding-left: {{ $indentLevel }}px;">
                {{-- Toggle button for children --}}
                @if($hasChildren)
                    <button class="detail-toggle mr-3 p-1 text-gray-400 hover:text-red-600 hover:bg-red-100 rounded-full focus:outline-none transition-all duration-200"
                            data-current-number="{{ $currentNumber }}" data-section-id="{{ $section->id }}">
                        <svg class="w-3 h-3 transform transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                @else
                    <div class="w-6 mr-3"></div>
                @endif
                
                {{-- Level indicator and numbering --}}
                <div class="flex items-center mr-3">
                    @if($level > 0)
                        <div class="flex items-center mr-2 text-gray-400">
                            @for($i = 1; $i <= $level; $i++)
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
                    @if($hasChildren)
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

        <!-- Actions Column -->
        <td class="px-6 py-3 whitespace-nowrap text-center">
            <div class="flex items-center justify-center space-x-2">
                <a href="{{ route('sections.details.edit', [$project, $section, $detail]) }}" 
                   class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white text-xs font-medium rounded-lg shadow-md transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
                
                <form action="{{ route('sections.details.destroy', [$project, $section, $detail]) }}" 
                      method="POST" class="inline-block"
                      onsubmit="return confirm('⚠️ Are you sure you want to delete this detail?\n\nThis action cannot be undone and will remove:\n- This detail item\n- All sub-items (if any)\n- All associated data\n\nContinue?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" 
                            class="inline-flex items-center px-3 py-1.5 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white text-xs font-medium rounded-lg shadow-md transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2">
                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Delete
                    </button>
                </form>
            </div>
        </td>
    </tr>

    {{-- Recursive children rendering --}}
    @if($hasChildren)
        @php
            // Sort children by 'no' and reset array indexes for proper sequential numbering
            $sortedChildren = $detail->children->sortBy('no')->values();
        @endphp
        @include('partials.detail-table-rows-full', [
            'details' => $sortedChildren,
            'level' => $level + 1,
            'project' => $project,
            'section' => $section,
            'parentNumber' => $currentNumber,
            'isChild' => true
        ])
    @endif
@endforeach