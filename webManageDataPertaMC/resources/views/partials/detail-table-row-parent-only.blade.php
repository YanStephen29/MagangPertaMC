{{-- Detail Table Row for BOQ Overview - Parent Only (No Children Displayed) --}}
<tr id="detail-row-{{ $sectionId }}-{{ $detail->no }}" class="hidden detail-row-{{ $sectionId }} hover:bg-red-50 transition-colors duration-200 border-l-4 border-red-300">
    <td class="px-6 py-3 whitespace-nowrap border-r border-gray-200">
        <div class="flex items-center" style="padding-left: 24px;">
            {{-- Numbering untuk detail --}}
            <div class="flex-shrink-0 mr-3">
                <span class="text-sm font-medium text-red-600 bg-red-50 px-2 py-1 rounded">
                    {{ $sectionNumber }}.{{ $detailNumber }}
                </span>
            </div>
            
            <div class="flex-shrink-0 h-6 w-6">
                @if($detail->children && $detail->children->count() > 0)
                    <div class="h-6 w-6 rounded bg-red-100 flex items-center justify-center">
                        <svg class="w-3 h-3 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                        </svg>
                    </div>
                @else
                    <div class="h-6 w-6 rounded bg-gray-100 flex items-center justify-center">
                        <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                    </div>
                @endif
            </div>
            <div class="ml-3">
                <div class="text-sm font-medium text-gray-900">
                    {{ $detail->nama_detail }}
                </div>
                @if($detail->note)
                    <div class="text-xs text-gray-500 mt-1">
                        {{ Str::limit($detail->note, 50) }}
                    </div>
                @endif
            </div>
        </div>
    </td>
    <td class="px-6 py-3 whitespace-nowrap border-r border-gray-200">
        @if($detail->children && $detail->children->count() > 0)
            <div class="flex items-center">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ $detail->children->count() }} items
                </span>
            </div>
        @else
            <div class="text-sm text-gray-900">
                {{ $detail->quantity }} {{ $detail->unit }}
            </div>
            <div class="text-xs text-gray-500">
                @ {{ number_format($detail->harga_satuan, 0, ',', '.') }}
            </div>
        @endif
    </td>
    <td class="px-6 py-3 whitespace-nowrap border-r border-gray-200">
        <div class="text-sm font-medium text-green-600">
            {{ $detail->formatted_harga_total }}
        </div>
        @if($detail->children && $detail->children->count() > 0)
            <div class="text-xs text-gray-500">
                (includes {{ $detail->children->count() }} sub-items)
            </div>
        @endif
    </td>
    <td class="px-6 py-3 whitespace-nowrap text-center text-sm font-medium">
        <div class="flex items-center justify-center space-x-2">
            @if($detail->canBeEdited(auth()->guard('admin')->user()))
                <a href="{{ route('sections.details.edit', [$project, $detail->section, $detail]) }}" 
                   class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs inline-flex items-center transition-colors">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit
                </a>
            @else
                <button class="bg-gray-400 text-white px-2 py-1 rounded text-xs inline-flex items-center cursor-not-allowed" 
                        title="{{ $detail->section->boq->status === 'Locked' ? 'BOQ sudah di-lock, tidak bisa edit detail' : 'Tidak ada akses untuk edit detail' }}"
                        disabled>
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                    Edit
                </button>
            @endif
            <a href="{{ route('sections.details.index', [$project, $detail->section]) }}" 
               class="bg-purple-500 hover:bg-purple-600 text-white px-2 py-1 rounded text-xs inline-flex items-center transition-colors">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7h16M4 11h16M4 15h16"></path>
                </svg>
                Manage
            </a>
        </div>
    </td>
</tr>