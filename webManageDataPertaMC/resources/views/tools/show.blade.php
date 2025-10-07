<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                Detail Tool
            </h2>
            <p class="text-sm text-gray-600 mt-1">Project: {{ $project->title_project }} ({{ $project->no_IO }})</p>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="w-full px-3 sm:px-6 lg:px-8 max-w-2xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    <div class="space-y-6">
                        <!-- Tool Information -->
                        <div class="bg-gradient-to-r from-red-50 to-blue-50 p-4 rounded-lg border">
                            <h3 class="text-lg font-semibold text-red-700 mb-4">Informasi Tool</h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                                    <div class="text-sm text-gray-900 font-medium">{{ $tool->Description }}</div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Quantity</label>
                                    <div class="text-sm text-red-600 font-bold">{{ number_format($tool->quantity) }} {{ $tool->unit }}</div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Delivery Date</label>
                                    <div class="text-sm text-gray-900">
                                        {{ $tool->delivery_date ? $tool->delivery_date->format('d F Y') : 'Belum ditentukan' }}
                                    </div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Bidang</label>
                                    <div class="text-sm text-gray-900">
                                        <div class="font-medium">{{ $tool->bidang->kode_GL }}</div>
                                        <div class="text-gray-600">{{ $tool->bidang->nama_Bidang }}</div>
                                    </div>
                                </div>
                            </div>
                            
                            @if($tool->remarks)
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Remarks</label>
                                <div class="text-sm text-gray-900">{{ $tool->remarks }}</div>
                            </div>
                            @endif
                        </div>

                        <!-- Project Information -->
                        <div class="bg-gray-50 p-4 rounded-lg border">
                            <h3 class="text-lg font-semibold text-gray-700 mb-4">Informasi Project</h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">No I/O</label>
                                    <div class="text-sm text-red-600 font-bold">{{ $project->no_IO }}</div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Title Project</label>
                                    <div class="text-sm text-gray-900">{{ $project->title_project }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Timestamps -->
                        <div class="bg-blue-50 p-4 rounded-lg border">
                            <h3 class="text-lg font-semibold text-blue-700 mb-4">Informasi Tambahan</h3>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Dibuat</label>
                                    <div class="text-sm text-gray-900">{{ $tool->created_at->format('d F Y H:i') }}</div>
                                </div>
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Terakhir Diupdate</label>
                                    <div class="text-sm text-gray-900">{{ $tool->updated_at->format('d F Y H:i') }}</div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-2">
                            <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
                                <a href="{{ route('projects.tools.edit', [$project, $tool]) }}" class="bg-gradient-to-r from-blue-600 to-blue-700 hover:from-blue-700 hover:to-blue-800 text-white font-bold py-3 px-6 rounded-lg shadow-lg transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-blue-300 text-center">
                                    ✏️ Edit Tool
                                </a>
                                
                                <form action="{{ route('projects.tools.destroy', [$project, $tool]) }}" method="POST" class="inline w-full sm:w-auto">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-3 px-6 rounded-lg shadow-lg transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-red-300 w-full" onclick="return confirm('Yakin ingin menghapus tool ini?')">
                                        🗑️ Hapus Tool
                                    </button>
                                </form>
                            </div>
                            
                            <a href="{{ route('projects.tools.index', $project) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-3 px-6 rounded-lg shadow-md transition-colors duration-200 w-full sm:w-auto text-center">
                                ↩️ Kembali ke Tools
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
