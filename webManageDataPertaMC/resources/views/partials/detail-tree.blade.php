@foreach($details as $detail)
    <div class="detail-item" style="margin-left: {{ $level * 20 }}px;">
        <div class="bg-gray-50 p-3 rounded-lg border-l-4 border-blue-500 mb-2">
            <div class="flex justify-between items-start">
                <div class="flex-1">
                    <div class="flex items-center gap-2 mb-2">
                        @if($detail->children->count() > 0)
                            <button onclick="toggleSubDetails({{ $detail->no }})" 
                                    id="toggle-icon-{{ $detail->no }}"
                                    class="hover:bg-gray-200 p-1 rounded transition-colors">
                                <svg class="w-4 h-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-6l-2-2H5a2 2 0 00-2 2z"></path>
                                </svg>
                            </button>
                        @else
                            <span class="p-1">
                                <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </span>
                        @endif
                        
                        <div>
                            <h6 class="font-semibold text-gray-800">{{ $detail->nama_detail }}</h6>
                            <p class="text-xs text-gray-500">Detail ID: {{ $detail->no }}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                        <div>
                            <span class="text-gray-600">Qty:</span>
                            <span class="font-medium">{{ number_format($detail->quantity) }} {{ $detail->unit }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Harga Satuan:</span>
                            <span class="font-medium">{{ $detail->formatted_harga_satuan }}</span>
                        </div>
                        <div>
                            <span class="text-gray-600">Total:</span>
                            <span class="font-bold text-green-600">{{ $detail->formatted_harga_total }}</span>
                            @if($detail->children->count() > 0)
                                <span class="block text-xs text-blue-600">(From children)</span>
                            @endif
                        </div>
                        <div class="text-right">
                            @if($detail->children->count() > 0)
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs">
                                    {{ $detail->children->count() }} sub-item(s)
                                </span>
                            @endif
                        </div>
                    </div>
                    
                    @if($detail->spesification)
                        <div class="mt-2 p-2 bg-yellow-50 rounded">
                            <p class="text-xs text-gray-600 mb-1">Spesifikasi:</p>
                            <p class="text-sm">{{ $detail->spesification }}</p>
                        </div>
                    @endif
                    
                    @if($detail->unit_waktu && $detail->quantity_waktu)
                        <div class="mt-2 text-xs text-gray-600">
                            <span class="bg-gray-100 px-2 py-1 rounded">
                                Waktu: {{ $detail->quantity_waktu }} {{ $detail->unit_waktu }}
                            </span>
                        </div>
                    @endif
                </div>
                
                <div class="flex gap-1 ml-3">
                    <a href="{{ route('sections.details.edit', [request('project'), $detail->section, $detail]) }}" 
                       class="bg-blue-600 hover:bg-blue-700 text-white px-2 py-1 rounded text-xs">
                        Edit
                    </a>
                    <form action="{{ route('sections.details.destroy', [request('project'), $detail->section, $detail]) }}" 
                          method="POST" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" 
                                class="bg-red-600 hover:bg-red-700 text-white px-2 py-1 rounded text-xs"
                                onclick="return confirm('Yakin ingin menghapus detail ini? Semua sub-detail juga akan terhapus.')">
                            Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
        
        @if($detail->children->count() > 0)
            <div id="sub-details-{{ $detail->no }}" class="hidden ml-4 border-l border-gray-300 pl-4">
                @include('partials.detail-tree', ['details' => $detail->children, 'level' => $level + 1])
            </div>
        @endif
    </div>
@endforeach