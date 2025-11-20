<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                Detail Document: {{ $document->no_request }}
            </h2>
            <div class="flex gap-2">
                <a href="{{ route('documents.edit', ['document' => base64_encode($document->no_request)]) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md shadow-md transition-all duration-200 inline-flex items-center text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                    </svg>
                    Edit Document
                </a>
                <a href="{{ route('documents.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-4 rounded-md shadow-md transition-all duration-200 inline-flex items-center text-sm">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back To List Documents
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="w-full px-3 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                <div class="p-3 sm:p-6 text-gray-900">
                    <!-- Document Info -->
                    <div class="mb-6">
                        <div class="bg-gradient-to-r from-purple-50 to-blue-50 p-6 rounded-lg border border-purple-200">
                            <h3 class="text-xl font-bold text-purple-800 mb-4">📄 Document Information</h3>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">No Request</label>
                                    <p class="text-lg font-semibold text-purple-600">{{ $document->no_request }}</p>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Type Request</label>
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium
                                        @if($document->jenis_request == 'Material Request')
                                            bg-green-100 text-green-800
                                        @elseif($document->jenis_request == 'Service Request')
                                            bg-blue-100 text-blue-800
                                        @elseif($document->jenis_request == 'Facility Request')
                                            bg-purple-100 text-purple-800
                                        @else
                                            bg-orange-100 text-orange-800 == 'Aset Request'
                                        @endif
                                    ">
                                        {{ $document->jenis_request }}
                                    </span>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Date Issue</label>
                                    <p class="text-sm text-gray-900">{{ $document->date_issue->format('d F Y') }}</p>
                                </div>
                            </div>
                            @if($document->description)
                                <div class="mt-4 pt-4 border-t border-purple-200">
                                    <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                                    <p class="text-sm text-gray-800 leading-relaxed">{{ $document->description }}</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tahapan Timeline -->
                    <div class="mb-6">
                        <div class="bg-gradient-to-r from-indigo-50 to-cyan-50 p-6 rounded-lg border border-indigo-200">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-xl font-bold text-indigo-800 flex items-center">
                                    🎯 Progress Stage Timeline
                                </h3>
                                <div class="text-right">
                                    <div class="text-xs text-gray-600 mb-1">Progress</div>
                                    <div class="text-2xl font-bold text-indigo-600">{{ $document->getProgressPercentage() }}%</div>
                                </div>
                            </div>

                            <!-- Progress Bar -->
                            <div class="w-full bg-gray-200 rounded-full h-3 mb-6">
                                <div class="bg-gradient-to-r from-indigo-500 to-cyan-500 h-3 rounded-full transition-all duration-500" 
                                     style="width: {{ $document->getProgressPercentage() }}%"></div>
                            </div>

                            <!-- Timeline Steps -->
                            @php
                                $allSteps = [
                                    'BELUM DI PROSES',
                                    'PROJECT TO EPC (EPC PROCESS)',
                                    'PMO TO EPC', 
                                    'EPC TO PROCUREMENT'
                                ];
                            @endphp

                            <div class="relative">
                                @foreach($allSteps as $index => $stepName)
                                    @php
                                        $tahapan = $document->tahapans->where('namaTahapan', $stepName)->first();
                                        // BELUM DI PROSES is always completed when it exists (since it's created with the document)
                                        $isCompleted = $tahapan && ($tahapan->Date_Tahapan || $stepName === 'BELUM DI PROSES');
                                        $isCurrent = $tahapan && !$tahapan->Date_Tahapan && $stepName !== 'BELUM DI PROSES';
                                        $isPending = !$tahapan;
                                    @endphp
                                    
                                    <div class="flex items-center mb-6 {{ $index < count($allSteps) - 1 ? 'relative' : '' }}">
                                        <!-- Connection Line -->
                                        @if($index < count($allSteps) - 1)
                                            <div class="absolute left-6 top-12 w-0.5 h-16 {{ $isCompleted ? 'bg-green-400' : 'bg-gray-300' }} z-0"></div>
                                        @endif
                                        
                                        <!-- Step Circle -->
                                        <div class="flex-shrink-0 w-12 h-12 rounded-full flex items-center justify-center relative z-10 {{ 
                                            $isCompleted ? 'bg-green-500 text-white shadow-lg' : 
                                            ($isCurrent ? 'bg-yellow-400 text-white shadow-lg animate-pulse' : 'bg-gray-300 text-gray-600') 
                                        }}">
                                            @if($isCompleted)
                                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                </svg>
                                            @elseif($isCurrent)
                                                <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                                </svg>
                                            @else
                                                {{ $index + 1 }}
                                            @endif
                                        </div>
                                        
                                        <!-- Step Content -->
                                        <div class="ml-6 flex-1">
                                            <div class="flex flex-col sm:flex-row sm:items-center justify-between">
                                                <div class="flex-1">
                                                    <h4 class="text-lg font-semibold {{ 
                                                        $isCompleted ? 'text-green-800' : 
                                                        ($isCurrent ? 'text-yellow-800' : 'text-gray-500') 
                                                    }}">
                                                        {{ $stepName }}
                                                    </h4>
                                                    <div class="flex items-center mt-1">
                                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium {{ 
                                                            $isCompleted ? 'bg-green-100 text-green-800' : 
                                                            ($isCurrent ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-100 text-gray-600') 
                                                        }}">
                                                            {{ $isCompleted ? '✅ Completed' : ($isCurrent ? '⏳ In Progress' : '⏸️ Pending') }}
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                @if($tahapan && $tahapan->Date_Tahapan)
                                                    <div class="mt-2 sm:mt-0 sm:ml-4 text-right">
                                                        <div class="text-sm font-medium text-gray-700">
                                                            {{ $tahapan->Date_Tahapan->format('d M Y') }}
                                                        </div>
                                                        <div class="text-xs text-gray-500">
                                                            {{ $tahapan->Date_Tahapan->diffForHumans() }}
                                                        </div>
                                                    </div>
                                                @elseif($isCurrent)
                                                    <div class="mt-2 sm:mt-0 sm:ml-4 text-right">
                                                        <div class="text-sm font-medium text-yellow-700">
                                                            Started: {{ $tahapan->created_at->format('d M Y') }}
                                                        </div>
                                                        <div class="text-xs text-yellow-600">
                                                            {{ $tahapan->created_at->diffForHumans() }}
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Summary Stats -->
                            <div class="mt-6 pt-6 border-t border-indigo-200">
                                <div class="grid grid-cols-1 sm:grid-cols-4 gap-4">
                                    @php
                                        $allSteps = ['BELUM DI PROSES', 'PROJECT TO EPC (EPC PROCESS)', 'PMO TO EPC', 'EPC TO PROCUREMENT'];
                                        $completedCount = 0;
                                        $inProgressCount = 0;
                                        $pendingCount = 0;
                                        
                                        foreach($allSteps as $stepName) {
                                            $tahapan = $document->tahapans->where('namaTahapan', $stepName)->first();
                                            $isCompleted = $tahapan && ($tahapan->Date_Tahapan || $stepName === 'BELUM DI PROSES');
                                            $isCurrent = $tahapan && !$tahapan->Date_Tahapan && $stepName !== 'BELUM DI PROSES';
                                            $isPending = !$tahapan;
                                            
                                            if ($isCompleted) $completedCount++;
                                            elseif ($isCurrent) $inProgressCount++;
                                            elseif ($isPending) $pendingCount++;
                                        }
                                    @endphp
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-green-600">{{ $completedCount }}</div>
                                        <div class="text-xs text-gray-600">Completed</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-yellow-600">{{ $inProgressCount }}</div>
                                        <div class="text-xs text-gray-600">In Progress</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-gray-600">{{ $pendingCount }}</div>
                                        <div class="text-xs text-gray-600">Pending</div>
                                    </div>
                                    <div class="text-center">
                                        <div class="text-2xl font-bold text-indigo-600">
                                            @if($document->tahapans->where('Date_Tahapan', '!=', null)->count() > 0)
                                                {{ $document->tahapans->where('Date_Tahapan', '!=', null)->last()->Date_Tahapan->diffInDays($document->date_issue) }} days
                                            @else
                                                0 days
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-600">Duration</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>                    <!-- Related Tools -->
                    @if($document->tools->count() > 0)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-800 mb-4">🔧 Request Tools Included in This Document ({{ $document->tools->count() }} items)</h3>
                            <div class="overflow-x-auto -mx-3 sm:mx-0">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gradient-to-r from-green-50 to-blue-50">
                                        <tr>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-center text-xs font-bold text-green-700 uppercase tracking-wider border-r border-gray-200">No</th>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-center text-xs font-bold text-green-700 uppercase tracking-wider border-r border-gray-200">Project</th>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-center text-xs font-bold text-green-700 uppercase tracking-wider border-r border-gray-200">Description</th>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-center text-xs font-bold text-green-700 uppercase tracking-wider border-r border-gray-200">Quantity</th>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-center text-xs font-bold text-green-700 uppercase tracking-wider border-r border-gray-200">Unit</th>
                                            <th class="px-3 sm:px-6 py-3 sm:py-4 text-center text-xs font-bold text-green-700 uppercase tracking-wider">GL Code</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($document->tools as $index => $tool)
                                            <tr class="hover:bg-green-50 transition-colors duration-150">
                                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-r border-gray-100 text-center">
                                                    {{ $index + 1 }}
                                                </td>
                                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-900 border-r border-gray-100">
                                                    @if($tool->project)
                                                        <div class="font-medium text-blue-600">{{ $tool->project->no_IO }}</div>
                                                        <div class="text-xs text-gray-500">{{ Str::limit($tool->project->title_project, 30) }}</div>
                                                    @else
                                                        <span class="text-gray-400">-</span>
                                                    @endif
                                                </td>
                                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-sm text-gray-900 border-r border-gray-100 break-words">
                                                    <div class="font-medium">{{ $tool->Description }}</div>
                                                    @if($tool->remarks)
                                                        <div class="text-xs text-gray-500 mt-1">{{ Str::limit($tool->remarks, 50) }}</div>
                                                    @endif
                                                </td>
                                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm font-medium text-gray-900 border-r border-gray-100 text-center">
                                                    {{ number_format($tool->quantity) }}
                                                </td>
                                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-700 border-r border-gray-100 text-center">
                                                    {{ $tool->unit }}
                                                </td>
                                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-700 text-center">
                                                    @if($tool->bidang)
                                                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                                            {{ $tool->bidang->kode_GL }}
                                                        </span>
                                                    @else
                                                        <span class="text-gray-400">-</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">There Are No Request Tools Yet</h3>
                            <p class="mt-1 text-sm text-gray-500">This document has no related tools requests.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>