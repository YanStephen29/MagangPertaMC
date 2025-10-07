{{-- Detail Table Row with Indentation --}}
<tr id="detail-row-{{ $sectionId }}-{{ $detail->no }}" class="hidden detail-row-{{ $sectionId }} hover:bg-blue-50 transition-colors duration-200 border-l-4 border-blue-200">
    <td class="px-6 py-3 whitespace-nowrap">
        <div class="flex items-center" style="padding-left: {{ ($level - 1) * 24 }}px;">
            {{-- Indentation with tree lines --}}
            @if($level > 1)
                <div class="flex items-center mr-2">
                    {{-- Vertical line connectors --}}
                    @for($i = 2; $i < $level; $i++)
                        <div class="w-px h-8 bg-gray-300 mr-6"></div>
                    @endfor
                    {{-- Horizontal connector --}}
                    <div class="flex items-center">
                        <div class="w-4 h-px bg-gray-300"></div>
                        <div class="w-px h-4 bg-gray-300"></div>
                    </div>
                </div>
            @endif
            
            <div class="flex-shrink-0 h-6 w-6">
                @if($detail->children && $detail->children->count() > 0)
                    <button onclick="toggleSubDetails({{ $detail->no }})" class="h-6 w-6 rounded bg-blue-100 flex items-center justify-center hover:bg-blue-200 transition-colors">
                        <svg id="toggle-icon-{{ $detail->no }}" class="w-3 h-3 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                        </svg>
                    </button>
                @else
                    <div class="h-6 w-6 rounded bg-gray-100 flex items-center justify-center">
                        <div class="w-2 h-2 bg-gray-400 rounded-full"></div>
                    </div>
                @endif
            </div>
            <div class="ml-3">
                <div class="text-sm font-medium text-gray-900">
                    @if($level > 1)
                        <span class="text-xs text-gray-400 mr-2">L{{ $level - 1 }}:</span>
                    @endif
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
    <td class="px-6 py-3 whitespace-nowrap">
        <div class="text-sm text-gray-900">
            {{ $detail->quantity }} {{ $detail->unit }}
        </div>
        <div class="text-xs text-gray-500">
            @ {{ number_format($detail->harga_satuan, 0, ',', '.') }}
        </div>
    </td>
    <td class="px-6 py-3 whitespace-nowrap">
        <div class="text-sm font-medium text-green-600">
            {{ $detail->formatted_harga_total }}
        </div>
    </td>
    <td class="px-6 py-3 whitespace-nowrap text-center text-sm font-medium">
        <div class="flex items-center justify-center space-x-2">
            <a href="{{ route('sections.details.edit', [$project, $detail->section, $detail]) }}" 
               class="bg-blue-500 hover:bg-blue-600 text-white px-2 py-1 rounded text-xs inline-flex items-center transition-colors">
                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Edit
            </a>
            <form action="{{ route('sections.details.destroy', [$project, $detail->section, $detail]) }}" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        class="bg-red-500 hover:bg-red-600 text-white px-2 py-1 rounded text-xs inline-flex items-center transition-colors" 
                        onclick="return confirm('Yakin ingin menghapus detail ini?')">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                    Del
                </button>
            </form>
        </div>
    </td>
</tr>

{{-- Recursive Sub-details --}}
@if($detail->children && $detail->children->count() > 0)
    @foreach($detail->children as $childDetail)
        @include('partials.detail-table-row', [
            'detail' => $childDetail, 
            'sectionId' => $sectionId,
            'level' => $level + 1,
            'project' => $project
        ])
    @endforeach
@endif