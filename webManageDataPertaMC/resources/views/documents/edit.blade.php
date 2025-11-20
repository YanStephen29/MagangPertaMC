<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                Edit Document: {{ $document->no_request }}
            </h2>
            <a href="{{ route('documents.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md inline-flex items-center text-sm transition-colors duration-200">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                 Back to List Documents
            </a>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="w-full px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                <div class="p-6 text-gray-900">
                    <!-- Notifikasi sudah ditangani di layout utama (app.blade.php) -->

                    <!-- Document Edit Form -->
                    <div class="bg-white border border-gray-200 rounded-lg shadow-sm">
                        <div class="px-6 py-4 bg-gradient-to-r from-purple-50 to-pink-50 border-b border-gray-200">
                            <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                <svg class="w-5 h-5 mr-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                                Edit Document Information
                            </h3>
                        </div>
                        
                        <div class="p-6">
                            <form action="{{ route('documents.update', ['document' => base64_encode($document->no_request)]) }}" method="POST" class="space-y-6">
                                @csrf
                                @method('PATCH')

                                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                    <!-- No Request -->
                                    <div>
                                        <label for="no_request" class="block text-sm font-medium text-gray-700 mb-2">
                                            📋 Request No.
                                        </label>
                                        <input type="text" 
                                               name="no_request" 
                                               id="no_request"
                                               value="{{ old('no_request', $document->no_request) }}"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 @error('no_request') border-red-500 @enderror"
                                               placeholder="Contoh: 001/JAE56015/VIII/2023">
                                        @error('no_request')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Jenis Request -->
                                    <div>
                                        <label for="jenis_request" class="block text-sm font-medium text-gray-700 mb-2">
                                            🏷️ Request Type
                                        </label>
                                        <select name="jenis_request" 
                                                id="jenis_request"
                                                class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 @error('jenis_request') border-red-500 @enderror">
                                            <option value="">-- Pilih Jenis Request --</option>
                                            <option value="Material Request" {{ old('jenis_request', $document->jenis_request) == 'Material Request' ? 'selected' : '' }}>Material Request</option>
                                            <option value="Service Request" {{ old('jenis_request', $document->jenis_request) == 'Service Request' ? 'selected' : '' }}>Service Request</option>
                                            <option value="Facility Request" {{ old('jenis_request', $document->jenis_request) == 'Facility Request' ? 'selected' : '' }}>Facility Request</option>
                                            <option value="Aset" {{ old('jenis_request', $document->jenis_request) == 'Aset' ? 'selected' : '' }}>Aset</option>
                                        </select>
                                        @error('jenis_request')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Date Issue -->
                                    <div>
                                        <label for="date_issue" class="block text-sm font-medium text-gray-700 mb-2">
                                            📅 Date Issue
                                        </label>
                                        <input type="date" 
                                               name="date_issue" 
                                               id="date_issue"
                                               value="{{ old('date_issue', $document->date_issue?->format('Y-m-d')) }}"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 @error('date_issue') border-red-500 @enderror">
                                        @error('date_issue')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Description -->
                                    <div class="lg:col-span-2">
                                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">
                                            📝 Description (Optional)
                                        </label>
                                        <textarea name="description" 
                                                  id="description"
                                                  rows="4"
                                                  placeholder="Enter a detailed description about this document..."
                                                  class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-purple-500 focus:border-purple-500 @error('description') border-red-500 @enderror">{{ old('description', $document->description) }}</textarea>
                                        @error('description')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Tahapan Update Form -->
                                <div class="bg-gradient-to-r from-indigo-50 to-cyan-50 p-6 rounded-lg border border-indigo-200">
                                    <div class="flex justify-between items-center mb-4">
                                        <h3 class="text-lg font-bold text-indigo-800 flex items-center">
                                            <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Update Progress Stages
                                        </h3>
                                        <div class="text-right">
                                            <div class="text-xs text-gray-600 mb-1">Current Progress</div>
                                            <div class="text-xl font-bold text-indigo-600">{{ $document->getProgressPercentage() }}%</div>
                                        </div>
                                    </div>

                                    <!-- Current Progress Bar -->
                                    <div class="w-full bg-gray-200 rounded-full h-2 mb-6">
                                        <div class="bg-gradient-to-r from-indigo-500 to-cyan-500 h-2 rounded-full" 
                                             style="width: {{ $document->getProgressPercentage() }}%"></div>
                                    </div>

                                    <!-- Current Tahapan Status -->
                                    @if($document->tahapans->isNotEmpty())
                                        @php $currentTahapan = $document->getCurrentTahapan(); @endphp
                                        <div class="mb-6">
                                            <div class="flex items-center p-4 bg-white rounded-lg border border-indigo-100">
                                                <div class="flex-shrink-0 w-10 h-10 bg-{{ $currentTahapan->getTahapanColor() }}-500 rounded-full flex items-center justify-center">
                                                    <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                                <div class="ml-4 flex-1">
                                                    <h4 class="text-base font-semibold text-gray-800">{{ $currentTahapan->namaTahapan }}</h4>
                                                    <p class="text-sm text-gray-600">
                                                        @if($currentTahapan->Date_Tahapan)
                                                            Completed: {{ $currentTahapan->Date_Tahapan->format('d M Y') }} ({{ $currentTahapan->Date_Tahapan->diffForHumans() }})
                                                        @else
                                                            Started: {{ $currentTahapan->created_at->format('d M Y') }} ({{ $currentTahapan->created_at->diffForHumans() }})
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    @endif

                                    <!-- Tahapan Selection Form -->
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                        <!-- Add/Update Tahapan -->
                                        <div>
                                            <label for="tahapan" class="block text-sm font-medium text-gray-700 mb-2">Update Tahapan</label>
                                            <select id="tahapan" name="tahapan" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                                <option value="">-- Select Stages to Update --</option>
                                                @php
                                                    $allTahapanOptions = [
                                                        'BELUM DI PROSES',
                                                        'PROJECT TO EPC (EPC PROCESS)',
                                                        'PMO TO EPC',
                                                        'EPC TO PROCUREMENT'
                                                    ];
                                                @endphp
                                                
                                                @foreach($allTahapanOptions as $option)
                                                    <option value="{{ $option }}">{{ $option }}</option>
                                                @endforeach
                                            </select>
                                            <p class="mt-1 text-xs text-gray-500">Select stages to add or update with completion date.</p>
                                        </div>

                                        <!-- Tahapan Date -->
                                        <div>
                                            <label for="tahapan_date" class="block text-sm font-medium text-gray-700 mb-2">
                                                Date Stages <span class="text-red-500">*</span>
                                            </label>
                                            <input type="date" 
                                                   id="tahapan_date" 
                                                   name="tahapan_date" 
                                                   value="{{ old('tahapan_date', now()->format('Y-m-d')) }}"
                                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 @error('tahapan_date') border-red-500 @enderror">
                                            @error('tahapan_date')
                                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                            @enderror
                                            <p class="mt-1 text-xs text-gray-500">Date completion for selected stages. Must be filled if selecting stages.</p>
                                        </div>
                                    </div>

                                    <!-- Quick Actions -->
                                    <div class="mt-6 pt-4 border-t border-indigo-200">
                                        <div class="flex flex-wrap gap-2">
                                            @php
                                                $existingTahapans = $document->tahapans->pluck('namaTahapan');
                                                $nextSteps = collect([
                                                    'BELUM DI PROSES',
                                                    'PROJECT TO EPC (EPC PROCESS)',
                                                    'PMO TO EPC',
                                                    'EPC TO PROCUREMENT'
                                                ])->reject(function($step) use ($existingTahapans) {
                                                    return $existingTahapans->contains($step);
                                                });
                                                $incompleteCount = $document->tahapans->where('Date_Tahapan', null)->count();
                                            @endphp
                                            
                                            @if($nextSteps->isNotEmpty())
                                                <button type="button" onclick="selectNextTahapan('{{ $nextSteps->first() }}')" 
                                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                                    </svg>
                                                    Add Next: {{ Str::limit($nextSteps->first(), 15) }}
                                                </button>
                                            @endif
                                            
                                            @if($incompleteCount > 0)
                                                <button type="button" onclick="completeFirstIncomplete()" 
                                                        class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    Complete ({{ $incompleteCount }} pending)
                                                </button>
                                            @endif
                                            
                                            <button type="button" onclick="setToday()" 
                                                    class="inline-flex items-center px-3 py-1 border border-transparent text-xs font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                Set Today
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <script>
                                    function selectNextTahapan(tahapanName) {
                                        document.getElementById('tahapan').value = tahapanName;
                                        document.getElementById('tahapan_date').focus();
                                    }
                                    
                                    function completeFirstIncomplete() {
                                        const incompleteTahapans = @json($document->tahapans->where('Date_Tahapan', null)->pluck('namaTahapan'));
                                        if (incompleteTahapans.length > 0) {
                                            document.getElementById('tahapan').value = incompleteTahapans[0];
                                            document.getElementById('tahapan_date').value = new Date().toISOString().split('T')[0];
                                        }
                                    }
                                    
                                    function setToday() {
                                        document.getElementById('tahapan_date').value = new Date().toISOString().split('T')[0];
                                    }

                                    // Form validation before submit
                                    document.addEventListener('DOMContentLoaded', function() {
                                        const form = document.querySelector('form');
                                        form.addEventListener('submit', function(e) {
                                            const tahapanSelect = document.getElementById('tahapan');
                                            const tahapanDate = document.getElementById('tahapan_date');
                                            
                                            if (tahapanSelect.value && !tahapanDate.value) {
                                                e.preventDefault();
                                                showToast('Date stage is required if selecting a tahapan to update!', 'warning');
                                                tahapanDate.focus();
                                                tahapanDate.style.borderColor = '#ef4444';
                                                setTimeout(() => {
                                                    tahapanDate.style.borderColor = '';
                                                }, 3000);
                                                return false;
                                            }
                                        });

                                        // Real-time validation
                                        const tahapanSelect = document.getElementById('tahapan');
                                        const tahapanDate = document.getElementById('tahapan_date');
                                        
                                        tahapanSelect.addEventListener('change', function() {
                                            if (this.value && !tahapanDate.value) {
                                                tahapanDate.style.borderColor = '#f59e0b';
                                                tahapanDate.focus();
                                            }
                                        });

                                        tahapanDate.addEventListener('change', function() {
                                            this.style.borderColor = '';
                                        });
                                    });
                                </script>

                                <!-- Warning if document has related tools -->
                                @if($document->tools()->count() > 0)
                                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                                        <div class="flex">
                                            <svg class="w-5 h-5 text-yellow-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                            </svg>
                                            <div class="ml-3">
                                                <h3 class="text-sm font-medium text-yellow-800">Warning</h3>
                                                <div class="mt-2 text-sm text-yellow-700">
                                                    <p>This document has <strong>{{ $document->tools()->count() }} related tools</strong>. Changing the request number will affect the references to these tools.</p>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endif

                                <!-- Action Buttons -->
                                <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                                    <a href="{{ route('documents.show', ['document' => base64_encode($document->no_request)]) }}" 
                                       class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md inline-flex items-center transition-colors duration-200">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                        Cancel
                                    </a>
                                    
                                    <button type="submit" 
                                            class="bg-gradient-to-r from-purple-600 to-purple-700 hover:from-purple-700 hover:to-purple-800 text-white px-6 py-2 rounded-md shadow-md transform hover:scale-105 transition-all duration-200 inline-flex items-center">
                                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        Update Document
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Related Tools Section -->
                    @if($document->tools->count() > 0)
                        <div class="mt-8 bg-white border border-gray-200 rounded-lg shadow-sm">
                            <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-200">
                                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                                    <svg class="w-5 h-5 mr-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 100 4m0-4v2m0-6V4"></path>
                                    </svg>
                                    Related Tools ({{ $document->tools->count() }} tools)
                                </h3>
                            </div>
                            
                            <div class="p-6">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tool</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Serial Number</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Project</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Bidang</th>
                                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($document->tools as $tool)
                                                <tr class="hover:bg-gray-50">
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                        {{ $tool->nama_tool }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $tool->serial_number ?: 'N/A' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $tool->project->nama_project ?? 'N/A' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        {{ $tool->bidang->nama_Bidang ?? 'N/A' }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                            @if($tool->status_tool == 'Available') bg-green-100 text-green-800
                                                            @elseif($tool->status_tool == 'In Use') bg-yellow-100 text-yellow-800
                                                            @elseif($tool->status_tool == 'Under Maintenance') bg-red-100 text-red-800
                                                            @else bg-gray-100 text-gray-800
                                                            @endif">
                                                            {{ $tool->status_tool ?: 'Unknown' }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>