<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                    Upload Excel BOQ
                </h2>
                <p class="text-sm text-gray-600 mt-0.5">
                    Project: <span class="font-medium">{{ $project->title_project }}</span> ({{ $project->no_IO }}) | 
                    BOQ: <span class="font-medium">{{ $boq->nomorBoq }}</span>
                </p>
            </div>
            <div class="mb-4">
                <a href="{{ route('projects.boq.index', $project) }}" 
                   class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm inline-flex items-center">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to BOQ
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="w-full px-3 sm:px-6 lg:px-8">

    <!-- Upload Form -->
    <div class="bg-white rounded-lg shadow-md p-6">
        @if(session('success'))
            <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center">
                    <i class="fas fa-exclamation-circle mr-2"></i>
                    {{ session('error') }}
                </div>
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg mb-6">
                <div class="flex items-center mb-2">
                    <i class="fas fa-exclamation-triangle mr-2"></i>
                    <strong>Upload Error:</strong>
                </div>
                <ul class="list-disc list-inside ml-4">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('projects.boq.upload.process', [$project, $boq]) }}" 
              method="POST" 
              enctype="multipart/form-data" 
              class="space-y-6">
            @csrf
            
            <!-- File Upload -->
            <div>
                <label for="excel_file" class="block text-sm font-medium text-gray-700 mb-2">
                    Select Excel File
                </label>
                <div class="flex items-center justify-center w-full">
                    <label for="excel_file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 transition duration-200">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <i class="fas fa-file-excel text-3xl text-green-500 mb-2"></i>
                            <p class="mb-2 text-sm text-gray-500">
                                <span class="font-semibold">Click to upload</span> Excel file
                            </p>
                            <p class="text-xs text-gray-500">XLSX or XLS (Max 10MB)</p>
                        </div>
                        <input id="excel_file" name="excel_file" type="file" class="hidden" 
                               accept=".xlsx,.xls" required>
                    </label>
                </div>
                <div id="file-name" class="mt-2 text-sm text-gray-600 hidden"></div>
            </div>

            <!-- Upload Button -->
            <div class="flex items-center justify-between">
                <button type="submit" 
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-medium transition duration-200 flex items-center">
                    <i class="fas fa-upload mr-2"></i>
                    Upload Excel File
                </button>
                
                <div class="text-sm text-gray-500">
                    <i class="fas fa-info-circle mr-1"></i>
                    Processing may take a few moments
                </div>
            </div>
        </form>
    </div>

    <!-- Instructions -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mt-6">
        <h3 class="text-lg font-semibold text-blue-900 mb-4 flex items-center">
            <i class="fas fa-info-circle mr-2"></i>
            Excel Format Instructions
        </h3>
        
        <div class="space-y-4 text-blue-800">
            <div>
                <h4 class="font-medium mb-2">Required Columns (Header Row):</h4>
                <ul class="list-disc list-inside ml-4 space-y-1">
                    <li><strong>A:</strong> No/Number/Nomor - hierarchy number (e.g., "1", "1.1", "1.1.1")</li>
                    <li><strong>B:</strong> Description/Deskripsi/Nama - item description</li>
                    <li><strong>C:</strong> Unit/Satuan - unit of measurement</li>
                    <li><strong>D:</strong> Quantity/Qty/Jumlah - numeric quantity</li>
                    <li><strong>E:</strong> Unit Price/Harga Satuan - price per unit</li>
                    <li><strong>F:</strong> Note/Catatan/Keterangan (optional) - additional notes</li>
                </ul>
                <p class="text-sm text-blue-600 mt-2"><em>Note: Column names are flexible - you can use English or Indonesian headers</em></p>
            </div>
            
            <div>
                <h4 class="font-medium mb-2">Hierarchy Rules:</h4>
                <ul class="list-disc list-inside ml-4 space-y-1">
                    <li><strong>Sections:</strong> Numbers without dots (e.g., "1", "2", "3")</li>
                    <li><strong>Details:</strong> Numbers with dots (e.g., "1.1", "1.2", "1.1.1")</li>
                    <li>More dots = deeper nesting level</li>
                    <li>Parent relationships are automatically determined</li>
                </ul>
            </div>
            
            <div>
                <h4 class="font-medium mb-2">Example Excel Structure:</h4>
                <div class="bg-white border rounded p-3 font-mono text-sm">
                    <div class="font-bold border-b pb-1 mb-2">No | Description | Unit | Quantity | Unit Price | Note</div>
                    <div>1 | Foundation Work | - | - | - | Main section</div>
                    <div class="ml-4">1.1 | Excavation | m³ | 100 | 50000 | Detail work</div>
                    <div class="ml-4">1.2 | Concrete Work | m³ | 50 | 800000 | Another detail</div>
                    <div class="ml-8">1.2.1 | Reinforcement | kg | 500 | 15000 | Sub-detail</div>
                    <div>2 | Structure Work | - | - | - | Another section</div>
                </div>
            </div>
            
            <div class="bg-blue-100 border border-blue-300 rounded p-3">
                <p class="text-sm">
                    <strong>Note:</strong> The first row should contain headers and will be skipped during import.
                    Make sure your Excel file follows this exact column structure for successful import.
                </p>
            </div>
        </div>
        </div>
    </div>

    <script>
    document.getElementById('excel_file').addEventListener('change', function(e) {
        const fileNameDiv = document.getElementById('file-name');
        const fileName = e.target.files[0]?.name;
        
        if (fileName) {
            fileNameDiv.textContent = `Selected: ${fileName}`;
            fileNameDiv.classList.remove('hidden');
        } else {
            fileNameDiv.classList.add('hidden');
        }
    });
    </script>
</x-app-layout>