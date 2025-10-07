@foreach($details as $detail)
    <div class="detail-item" style="margin-left: {{ $level * 20 }}px;">
        <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm" style="border-left: {{ 4 + $level * 2 }}px solid {{ $level == 0 ? '#3b82f6' : ($level == 1 ? '#8b5cf6' : '#10b981') }};">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-3">
                        @if($detail->children && $detail->children->count() > 0)
                            <button onclick="toggleSubDetails({{ $detail->no }})" 
                                    id="toggle-icon-{{ $detail->no }}"
                                    class="text-lg hover:bg-gray-200 p-1 rounded transition-colors"
                                    title="Click to expand/collapse">
                                📁
                            </button>
                        @else
                            <span class="text-lg p-1">📄</span>
                        @endif
                        
                        <div class="flex-1">
                            <h6 class="font-semibold text-gray-800 text-lg">{{ $detail->nama_detail }}</h6>
                            <div class="flex items-center gap-4 text-xs text-gray-500">
                                <span>ID: {{ $detail->no }}</span>
                                <span>Level: {{ $level }}</span>
                                @if($detail->children->count() > 0)
                                    <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full">
                                        {{ $detail->children->count() }} sub-item(s)
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    <!-- Detail Information Grid -->
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 text-sm mb-3">
                        <div class="bg-gray-50 p-2 rounded">
                            <span class="text-gray-600 block text-xs">Quantity:</span>
                            <span class="font-medium">{{ number_format($detail->quantity) }} {{ $detail->unit }}</span>
                        </div>
                        <div class="bg-gray-50 p-2 rounded">
                            <span class="text-gray-600 block text-xs">Harga Satuan:</span>
                            <span class="font-medium">{{ $detail->formatted_harga_satuan }}</span>
                        </div>
                        <div class="bg-green-50 p-2 rounded">
                            <span class="text-gray-600 block text-xs">Total Harga:</span>
                            <span class="font-bold text-green-600">{{ $detail->formatted_harga_total }}</span>
                            @if($detail->children && $detail->children->count() > 0)
                                <span class="block text-xs text-blue-600">(From {{ $detail->children->count() }} children)</span>
                            @else
                                <span class="block text-xs text-gray-500">(Own calculation)</span>
                            @endif
                        </div>
                        <div class="bg-blue-50 p-2 rounded">
                            <span class="text-gray-600 block text-xs">Waktu:</span>
                            <span class="font-medium">{{ $detail->quantity_waktu }} {{ $detail->unit_waktu }}</span>
                        </div>
                    </div>
                    
                    <!-- Specification -->
                    @if($detail->spesification && $detail->spesification !== 'TBD')
                        <div class="bg-yellow-50 border border-yellow-200 rounded p-3 mb-3">
                            <p class="text-xs text-gray-600 mb-1">📋 Spesifikasi:</p>
                            <p class="text-sm text-gray-800">{{ $detail->spesification }}</p>
                        </div>
                    @endif
                </div>
                
                <!-- Action Buttons -->
                <div class="flex flex-col gap-1 ml-4">
                    <a href="{{ route('sections.details.create', [$project, $section]) }}?parent_no={{ $detail->no }}" 
                       class="bg-purple-600 hover:bg-purple-700 text-white px-2 py-1 rounded text-xs text-center"
                       title="Add Sub Detail">
                        ➕ Sub
                    </a>
                    <a href="{{ route('sections.details.edit', [$project, $section, $detail]) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs text-center">
                        ✏️ Edit
                    </a>
                    <form action="{{ route('sections.details.destroy', [$project, $section, $detail]) }}" 
                          method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs w-full"
                                onclick="return confirm('Yakin ingin menghapus detail ini? Semua sub-detail juga akan terhapus.')"
                                title="Delete Detail">
                            🗑️ Del
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        <!-- Recursive Children Display -->
        @if($detail->children && $detail->children->count() > 0)
            <div id="sub-details-{{ $detail->no }}" class="hidden mt-3 ml-6 border-l-2 border-gray-300 pl-4">
                @include('partials.detail-tree-full', [
                    'details' => $detail->children, 
                    'level' => $level + 1, 
                    'project' => $project, 
                    'section' => $section
                ])
            </div>
        @endif
    </div>
@endforeach