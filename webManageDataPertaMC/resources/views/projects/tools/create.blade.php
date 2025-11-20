<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                    Add New Request
                </h2>
                <p class="text-sm text-gray-600 mt-1">Project: {{ $project->title_project }} ({{ $project->no_IO }})</p>
            </div>
            <div class="mt-4 sm:mt-0">
                <a href="{{ route('projects.tools.index', $project) }}" 
                   class="inline-flex items-center px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white text-sm font-medium rounded-md transition-colors duration-200">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Back to List Requests
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Error Messages -->
            @if($errors->any())
                <div class="bg-red-50 border-l-4 border-red-400 rounded-lg shadow-sm mb-6">
                    <div class="p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-red-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <h3 class="text-sm font-medium text-red-800">Please fix the following errors:</h3>
                                <div class="mt-2">
                                    <ul class="list-disc list-inside text-sm text-red-700 space-y-1">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Main Form Card -->
            <div class="bg-white overflow-hidden shadow-xl rounded-xl border border-gray-200">
                <div class="p-8">

                    @if(session('boq_validation_error'))
                        <div class="bg-red-50 border border-red-200 rounded-lg p-6 mb-6">
                            <div class="flex items-center mb-4">
                                <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                </svg>
                                <h3 class="text-lg font-medium text-red-800">Validate BOQ Failed</h3>
                            </div>
                            
                            @php $messages = session('validation_messages', []); @endphp
                            
                            @if(isset($messages['errors']))
                                <div class="mb-4">
                                    <h4 class="font-medium text-red-800 mb-2">Error:</h4>
                                    <ul class="list-disc list-inside text-red-700 space-y-1">
                                        @foreach($messages['errors'] as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(isset($messages['warnings']))
                                <div class="mb-4">
                                    <h4 class="font-medium text-yellow-800 mb-2">Warning:</h4>
                                    <ul class="list-disc list-inside text-yellow-700 space-y-1">
                                        @foreach($messages['warnings'] as $warning)
                                            <li>{{ $warning }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(isset($messages['suggestions']))
                                <div class="mb-4">
                                    <h4 class="font-medium text-blue-800 mb-2">Item BOQ suggestions:</h4>
                                    <div class="bg-blue-50 border border-blue-200 rounded p-3">
                                        @php 
                                            $validationResult = session('validation_result', []);
                                            $suggestions = $validationResult['suggestions'] ?? [];
                                        @endphp
                                        @if(!empty($suggestions))
                                            <div class="space-y-2">
                                                @foreach($suggestions as $suggestion)
                                                    <div class="flex justify-between items-center p-2 bg-white rounded border">
                                                        <div>
                                                            <span class="font-medium text-blue-900">{{ $suggestion['detail']->nama_detail }}</span>
                                                            <span class="text-sm text-blue-700">
                                                                ({{ $suggestion['detail']->quantity }} {{ $suggestion['detail']->unit }})
                                                            </span>
                                                        </div>
                                                        <div class="text-right">
                                                            <div class="text-sm font-medium text-green-600">
                                                                Rp {{ number_format($suggestion['detail']->harga_satuan, 0, ',', '.') }}
                                                            </div>
                                                            <div class="text-xs text-blue-600">
                                                                {{ number_format($suggestion['similarity'] * 100, 1) }}% similarity
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if(isset($messages['info']))
                                <div class="mb-4">
                                    <h4 class="font-medium text-blue-800 mb-2">Information BOQ:</h4>
                                    <ul class="list-disc list-inside text-blue-700 space-y-1">
                                        @foreach($messages['info'] as $info)
                                            <li>{{ $info }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif



                            <div class="flex flex-col sm:flex-row gap-3 mt-6 pt-4 border-t border-red-200">
                                <button type="button" onclick="fixToolData()" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                    Correction Data Tool
                                </button>
                                
                                <form method="POST" action="{{ route('projects.tools.store', $project) }}" class="inline">
                                    @csrf
                                    @foreach(session('tool_data', []) as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach
                                    <input type="hidden" name="force_create" value="1">
                                    <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">
                                        Force Create Tool (Ignore BOQ)
                                    </button>
                                </form>
                            </div>
                        </div>
                    @endif

                    @if(isset($selectedBoqItems) && $selectedBoqItems && $selectedBoqItems->count() > 0)
                    <!-- Selected BOQ Items Summary -->
                    <div class="bg-green-50 border border-green-200 rounded-lg p-6 mb-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-semibold text-green-900">Selected BOQ Items</h3>
                            <div class="flex items-center space-x-2">
                                <span class="bg-green-100 text-green-800 text-xs font-medium px-2.5 py-0.5 rounded">
                                    {{ $selectedBoqItems->count() }} items selected
                                </span>
                                <a href="{{ route('projects.tools.select-boq-items', $project) }}" class="text-sm text-green-600 hover:text-green-700 font-medium">
                                    Re-select Items
                                </a>
                            </div>
                        </div>
                        
                        <!-- Horizontal scrollable container for BOQ items -->
                        <div class="relative">
                            @if($selectedBoqItems->count() > 2)
                                <!-- Scroll indicators -->
                                <div class="absolute top-4 right-4 z-10 flex items-center space-x-2">
                                    <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full font-medium">
                                        {{ $selectedBoqItems->count() }} items • Scroll →
                                    </span>
                                </div>
                            @endif
                            
                            <div class="flex @if($selectedBoqItems->count() > 2) overflow-x-auto @else justify-center @endif gap-6 pb-4 @if($selectedBoqItems->count() > 2) scrollbar-thin scrollbar-thumb-green-300 scrollbar-track-green-100 @endif">
                                @foreach($selectedBoqItems as $item)
                                    @php
                                        $usageData = $boqUsageData[$item->no] ?? null;
                                    @endphp
                                    <div class="border border-green-200 rounded-lg p-6 bg-white shadow-sm hover:shadow-md transition-shadow duration-200 @if($selectedBoqItems->count() > 2) flex-shrink-0 w-96 @else flex-1 max-w-lg @endif">
                                    <div class="mb-4">
                                        <h4 class="text-base font-semibold text-gray-900 mb-3 leading-tight">{{ $item->nama_detail }}</h4>
                                        <div class="flex items-center justify-between flex-wrap gap-2">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                📂 {{ $item->section->nama ?? 'Section' }}
                                            </span>
                                            <span class="text-xs text-gray-400 font-mono">#{{ $item->no }}</span>
                                        </div>
                                    </div>
                                    
                                    @if($usageData)
                                    <div class="space-y-4">
                                        <!-- Status Overview -->
                                        @php
                                            $usagePercentage = $usageData['usage_percentage'];
                                            $availablePercentage = 100 - $usagePercentage;
                                            $progressBarPercentage = min($usagePercentage, 100);
                                            $statusColor = $usagePercentage > 80 ? 'red' : ($usagePercentage > 50 ? 'yellow' : 'green');
                                        @endphp
                                        
                                        <!-- Main Stats Card -->
                                        <div class="bg-white border-2 @if($statusColor == 'red') border-red-200 @elseif($statusColor == 'yellow') border-yellow-200 @else border-green-200 @endif rounded-xl p-5 shadow-sm">
                                            <div class="grid grid-cols-3 gap-3 mb-6">
                                                <!-- Total -->
                                                <div class="flex items-center justify-center">
                                                    <div class="bg-gradient-to-br from-gray-50 to-gray-100 border border-gray-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow duration-200 w-full">
                                                        <div class="flex flex-col items-center justify-center text-center">
                                                            <div class="text-3xl font-bold text-gray-800 mb-1">{{ number_format($usageData['total_quantity']) }}</div>
                                                            <div class="text-xs text-gray-600 font-medium mb-2">Total</div>
                                                            <div class="text-xs text-gray-500 bg-gray-200 px-2 py-0.5 rounded-full">{{ $usageData['unit'] }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Available -->
                                                <div class="flex items-center justify-center">
                                                    <div class="bg-gradient-to-br @if($statusColor == 'red') from-red-50 to-red-100 border border-red-200 @elseif($statusColor == 'yellow') from-yellow-50 to-yellow-100 border border-yellow-200 @else from-green-50 to-green-100 border border-green-200 @endif rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow duration-200 w-full">
                                                        <div class="flex flex-col items-center justify-center text-center">
                                                            <div class="text-3xl font-bold @if($statusColor == 'red') text-red-700 @elseif($statusColor == 'yellow') text-yellow-700 @else text-green-700 @endif mb-1">{{ number_format($usageData['available_quantity']) }}</div>
                                                            <div class="text-xs @if($statusColor == 'red') text-red-600 @elseif($statusColor == 'yellow') text-yellow-600 @else text-green-600 @endif font-medium mb-2">Available</div>
                                                            <div class="text-xs @if($statusColor == 'red') text-red-500 bg-red-100 @elseif($statusColor == 'yellow') text-yellow-500 bg-yellow-100 @else text-green-500 bg-green-100 @endif px-2 py-0.5 rounded-full">{{ $usageData['unit'] }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                                
                                                <!-- Used -->
                                                <div class="flex items-center justify-center">
                                                    <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-5 shadow-sm hover:shadow-md transition-shadow duration-200 w-full">
                                                        <div class="flex flex-col items-center justify-center text-center">
                                                            <div class="text-3xl font-bold text-blue-700 mb-1">{{ number_format($usageData['used_quantity']) }}</div>
                                                            <div class="text-xs text-blue-600 font-medium mb-2">Used</div>
                                                            <div class="text-xs text-blue-500 bg-blue-100 px-2 py-0.5 rounded-full">{{ $usageData['unit'] }}</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Usage Progress Bar -->
                                            <div class="space-y-3">
                                                <div class="flex justify-between items-center flex-wrap gap-2">
                                                    <span class="text-sm font-medium text-gray-700">Usage Status</span>
                                                    <div class="flex items-center space-x-2">
                                                        <span class="text-sm @if($statusColor == 'red') text-red-600 @elseif($statusColor == 'yellow') text-yellow-600 @else text-green-600 @endif font-medium">
                                                            {{ number_format($availablePercentage, 1) }}% Available
                                                        </span>
                                                        @if($statusColor == 'red')
                                                            @if ($availablePercentage <= 0)
                                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-gray-800 text-white">
                                                                    ❌ Out of Stock
                                                                </span>
                                                            @else
                                                                <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                                    ⚠️ Low Stock
                                                                </span>
                                                            @endif
                                                        @elseif($statusColor == 'yellow')
                                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                                ⚡ Medium Stock
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                                ✅ Good Stock
                                                            </span>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <!-- Visual Progress Bar -->
                                                <div class="w-full bg-gray-200 rounded-full h-4 shadow-inner">
                                                    <div class="h-4 rounded-full transition-all duration-500 @if($statusColor == 'red') bg-gradient-to-r from-red-400 to-red-600 @elseif($statusColor == 'yellow') bg-gradient-to-r from-yellow-400 to-yellow-600 @else bg-gradient-to-r from-green-400 to-green-600 @endif" 
                                                         style="width: {{ $progressBarPercentage }}%"></div>
                                                </div>
                                                
                                                <!-- Usage Scale -->
                                                <div class="flex justify-between text-xs text-gray-500">
                                                    <span>0%</span>
                                                    <span>25%</span>
                                                    <span>50%</span>
                                                    <span>75%</span>
                                                    <span>100%</span>
                                                </div>
                                            </div>

                                            <!-- Additional Info -->
                                            <div class="mt-5 pt-4 border-t border-gray-200">
                                                <div class="space-y-2">
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-sm text-gray-600">Unit Price:</span>
                                                        <span class="text-sm font-medium text-gray-900">Rp {{ number_format($usageData['unit_price'], 0, ',', '.') }}</span>
                                                    </div>
                                                    <div class="flex justify-between items-center">
                                                        <span class="text-sm text-gray-600">Total Value:</span>
                                                        <span class="text-sm font-medium text-gray-900">Rp {{ number_format($usageData['total_quantity'] * $usageData['unit_price'], 0, ',', '.') }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @else
                                    <div class="bg-gradient-to-br from-green-50 to-green-100 border-2 border-green-200 rounded-xl p-6 text-center shadow-sm">
                                        <div class="text-green-700">
                                            <div class="flex justify-center mb-3">
                                                <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="text-base font-semibold mb-2 text-green-800">Full Quantity Available</div>
                                            <div class="text-sm text-green-600 mb-3">This BOQ item hasn't been used in any requests yet</div>
                                            <div class="bg-white bg-opacity-50 rounded-lg p-3">
                                                <div class="text-lg font-bold text-green-800">{{ number_format($item->quantity) }} {{ $item->unit }}</div>
                                                <div class="text-xs text-green-600 mt-1">Available for request</div>
                                            </div>
                                        </div>
                                    </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        </div>
                    </div>
                    @endif

                    <form action="{{ route('projects.tools.store', $project) }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <!-- Hidden field for selected BOQ items -->
                        @if(isset($selectedBoqItems) && $selectedBoqItems && $selectedBoqItems->count() > 0)
                            <input type="hidden" name="selected_boq_items" value="{{ $selectedBoqItems->pluck('no')->toJson() }}">
                        @endif

                        @if(isset($selectedBoqItems) && $selectedBoqItems && $selectedBoqItems->count() > 0)
                            @php
                                $totalQuantity = 0;
                                $commonUnit = null;
                                $unitMismatch = false;
                                $combinedDescription = [];
                                
                                foreach($selectedBoqItems as $item) {
                                    $totalQuantity += $item->quantity;
                                    $combinedDescription[] = $item->nama_detail;
                                    
                                    if ($commonUnit === null) {
                                        $commonUnit = $item->unit;
                                    } elseif ($commonUnit !== $item->unit) {
                                        $unitMismatch = true;
                                    }
                                }
                                
                                $generatedDescription = implode(', ', $combinedDescription);
                                if (strlen($generatedDescription) > 45) {
                                    $generatedDescription = substr($generatedDescription, 0, 42) . '...';
                                }
                            @endphp

                            <!-- Auto-filled form based on selected BOQ items -->
                            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                                <h3 class="text-sm font-medium text-blue-800 mb-3">Data Tool Based on Selected BOQ</h3>
                                
                                @if($unitMismatch)
                                    <div class="bg-red-100 border border-red-300 rounded-lg p-3 mb-4">
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                                            </svg>
                                            <div>
                                                <h4 class="text-red-800 font-medium">Inconsistent Unit!</h4>
                                                <p class="text-red-700 text-sm mt-1">The unit from the box list you selected is different</p>
                                                <div class="mt-2">
                                                    <a href="{{ route('projects.tools.select-boq-items', $project) }}" class="inline-flex items-center text-sm text-red-600 hover:text-red-700 font-medium">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                                                        </svg>
                                                        Choose Again BOQ Items
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <!-- BOQ Items Summary -->
                                    <div class="bg-white border border-blue-200 rounded-lg p-4 mb-4">
                                        <h4 class="text-sm font-medium text-blue-800 mb-3">Items BOQ has been selected:</h4>
                                        <div class="space-y-2">
                                            @foreach($selectedBoqItems as $item)
                                                @php
                                                    $availableQty = $item->getAvailableQuantity();
                                                    $originalQty = $item->quantity;
                                                @endphp
                                                <div class="flex justify-between items-center p-2 bg-blue-50 rounded">
                                                    <span class="text-sm text-blue-900">{{ $item->nama_detail }}</span>
                                                    <div class="text-right">
                                                        <span class="text-xs font-medium text-blue-600">{{ number_format($availableQty) }} {{ $item->unit }} available</span>
                                                        @if($availableQty < $originalQty)
                                                            <div class="text-xs text-gray-500">({{ number_format($originalQty - $availableQty) }} used)</div>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                        <div class="mt-3 pt-3 border-t border-blue-200">
                                            @php
                                                $totalAvailableQuantity = $selectedBoqItems->sum(function($item) {
                                                    return $item->getAvailableQuantity();
                                                });
                                            @endphp
                                            <div class="flex justify-between text-sm">
                                                <span class="font-medium text-blue-800">Total Available:</span>
                                                <span class="font-bold @if($totalAvailableQuantity <= 0) text-red-600 @else text-blue-900 @endif">
                                                    {{ number_format($totalAvailableQuantity) }} {{ $commonUnit }}
                                                    @if($totalAvailableQuantity <= 0)
                                                        <span class="ml-2 text-xs bg-red-100 text-red-800 px-2 py-1 rounded-full">OUT OF STOCK</span>
                                                    @endif
                                                </span>
                                            </div>
                                            @if($totalAvailableQuantity <= 0)
                                                <div class="mt-2 p-2 bg-red-50 border border-red-200 rounded-lg">
                                                    <p class="text-xs text-red-800">
                                                        ⚠️ <strong>Warning:</strong> No stock available. Your request will be automatically set to HOLD status for admin review.
                                                    </p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="mb-4">
                                        <label for="Description" class="block text-sm font-medium text-gray-700 mb-2">
                                            Tool Description <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               name="Description" 
                                               id="Description" 
                                               value="{{ old('Description', $generatedDescription) }}"
                                               placeholder="Anda dapat mengubah deskripsi sesuai kebutuhan"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('Description') border-red-500 @enderror"
                                               maxlength="45"
                                               required>
                                        <p class="text-xs text-gray-500 mt-1">Based on the selected BOQ (can be changed)</p>
                                        @error('Description')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <!-- Quantity -->
                                        <div>
                                            <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                                Quantity <span class="text-red-500">*</span>
                                            </label>
                                            <input type="number" 
                                                   name="quantity" 
                                                   id="quantity" 
                                                   value="{{ old('quantity', 1) }}"
                                                   min="1"
                                                   step="1"
                                                   class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('quantity') border-red-500 @enderror"
                                                   onchange="validateQuantity()"
                                                   oninput="validateQuantity()"
                                                   required>
                                            <div class="mt-1 text-xs">
                                                <span class="text-gray-500">Available: {{ number_format($totalAvailableQuantity) }} {{ $commonUnit }}</span>
                                                <div id="quantity-warning" class="text-yellow-600 hidden mt-1">
                                                    Quantity exceeds BOQ stock. Request will be held for admin approval.
                                                </div>
                                            </div>
                                            @error('quantity')
                                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>

                                        <!-- Unit -->
                                        <div>
                                            <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                                                Unit (Field from BOQ) <span class="text-red-500">*</span>
                                            </label>
                                            <input type="text" 
                                                   name="unit" 
                                                   id="unit" 
                                                   value="{{ old('unit', $commonUnit) }}"
                                                   class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-700"
                                                   readonly
                                                   required>
                                            <p class="text-xs text-gray-500 mt-1">Consistent unit from the selected BOQ</p>
                                        </div>
                                    </div>

                                    <!-- Hidden fields for BOQ tracking -->
                                    <input type="hidden" name="boq_max_quantity" value="{{ $totalQuantity }}">
                                    <input type="hidden" name="boq_unit" value="{{ $commonUnit }}">
                                @endif
                            </div>
                        @else
                            <!-- Manual input when no BOQ items selected -->
                            <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                                <div class="flex items-center mb-3">
                                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <h3 class="text-sm font-medium text-yellow-800">ℹ️ Manual Input</h3>
                                </div>
                                <p class="text-yellow-700 text-sm mb-4">There are no BOQ items selected. You can input manually or <a href="{{ route('projects.tools.select-boq-items', $project) }}" class="font-medium underline">select from BOQ</a>.</p>
                                
                                <!-- Description -->
                                <div class="mb-4">
                                    <label for="Description" class="block text-sm font-medium text-gray-700 mb-2">
                                        Tool Description <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" 
                                           name="Description" 
                                           id="Description" 
                                           value="{{ old('Description') }}"
                                           placeholder="Contoh: Hydraulic Pump, Steel Pipe, Safety Valve, dll"
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('Description') border-red-500 @enderror"
                                           maxlength="45"
                                           required>
                                    @error('Description')
                                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <!-- Quantity -->
                                    <div>
                                        <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                                            Quantity <span class="text-red-500">*</span>
                                        </label>
                                        <input type="number" 
                                               name="quantity" 
                                               id="quantity" 
                                               value="{{ old('quantity', 1) }}"
                                               min="1"
                                               step="1"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('quantity') border-red-500 @enderror"
                                               required>
                                        @error('quantity')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Unit -->
                                    <div>
                                        <label for="unit" class="block text-sm font-medium text-gray-700 mb-2">
                                            Unit <span class="text-red-500">*</span>
                                        </label>
                                        <input type="text" 
                                               name="unit" 
                                               id="unit" 
                                               value="{{ old('unit') }}"
                                               placeholder="pcs, meter, liter, kg, dll"
                                               class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('unit') border-red-500 @enderror"
                                               maxlength="20"
                                               required>
                                        @error('unit')
                                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- Delivery Date -->
                        <div>
                            <label for="delivery_date" class="block text-sm font-medium text-gray-700 mb-2">
                                Delivery Date
                            </label>
                            <input type="date" 
                                   name="delivery_date" 
                                   id="delivery_date" 
                                   value="{{ old('delivery_date') }}"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('delivery_date') border-red-500 @enderror">
                            @error('delivery_date')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-600 mt-1">Optional - just leave it blank if not specified</p>
                        </div>

                        <!-- Bidang -->
                        <div>
                            <label for="kode_GL" class="block text-sm font-medium text-gray-700 mb-2">
                                GL <span class="text-red-500">*</span>
                            </label>
                            <select name="kode_GL" 
                                    id="kode_GL" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('kode_GL') border-red-500 @enderror"
                                    required>
                                <option value="">Choose GL Code...</option>
                                @foreach($bidangs as $bidang)
                                    <option value="{{ $bidang->kode_GL }}" {{ old('kode_GL') == $bidang->kode_GL ? 'selected' : '' }}>
                                        {{ $bidang->kode_GL }} - {{ $bidang->nama_Bidang }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kode_GL')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Document Assignment -->
                        <div>
                            <label for="no_document" class="block text-sm font-medium text-gray-700 mb-2">
                                Assign to Document
                            </label>
                            <select name="no_document" 
                                    id="no_document" 
                                    class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('no_document') border-red-500 @enderror">
                                <option value="">Choose Document (Optional)...</option>
                                @foreach($documents as $doc)
                                    @php
                                        $assignedDocument = session('assigned_document');
                                        $isSelected = (old('no_document') == $doc->no_request) || 
                                                     ($assignedDocument && $assignedDocument == $doc->no_request);
                                    @endphp
                                    <option value="{{ $doc->no_request }}" {{ $isSelected ? 'selected' : '' }}>
                                        {{ $doc->no_request }} - {{ $doc->jenis_request }} ({{ $doc->date_issue->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                            @error('no_document')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-600 mt-1">Optional - tool can be assigned to a document later</p>
                        </div>

                        <!-- Remarks -->
                        <div>
                            <label for="remarks" class="block text-sm font-medium text-gray-700 mb-2">
                            Remarks/Notes
                            </label>
                            <textarea name="remarks" 
                                      id="remarks" 
                                      rows="3"
                                      placeholder="Extra Note, special specifications, etc..."
                                      class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500 @error('remarks') border-red-500 @enderror"
                                      maxlength="45">{{ old('remarks') }}</textarea>
                            @error('remarks')
                                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-sm text-gray-600 mt-1">Maximal 45 characters</p>
                        </div>

                        <!-- Information Box -->
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-green-400 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                </svg>
                                <div class="ml-3">
                                    <h3 class="text-sm font-medium text-green-800">Information</h3>
                                    <div class="mt-2 text-sm text-green-700">
                                        <ul class="list-disc list-inside space-y-1">
                                            <li>Tool will be automatically linked to project: <strong>{{ $project->title_project }}</strong></li>
                                            <li>Field is required for categorization and tracking</li>
                                            <li>Document assignment is optional - can be done after the tool is created</li>
                                            <li>All fields marked with * are mandatory</li>
                                            @php
                                                $boqCount = \App\Models\Detail::whereHas('section.boq', function($query) use ($project) {
                                                    $query->where('project_no_io', $project->no_IO);
                                                })->count();
                                            @endphp
                                            <li class="font-medium {{ $boqCount > 0 ? 'text-green-800' : 'text-yellow-800' }}">
                                                BOQ Status: {{ $boqCount > 0 ? "$boqCount item available" : "No BOQ data available" }}
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row justify-end gap-4">
                            <a href="{{ route('projects.tools.index', $project) }}" 
                               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md transition-colors duration-200 text-center">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-2 rounded-md shadow-md transform hover:scale-105 transition-all duration-200">
                                Add Tools
                            </button>
                        </div>
                    </form>
                </div>
            </div>


        </div>
    </div>

    <script>
        function fixToolData() {
            // Scroll to the form
            document.querySelector('form').scrollIntoView({ behavior: 'smooth' });
            
            // Focus on the description field
            document.getElementById('Description').focus();
            
            // Add visual indicator
            document.getElementById('Description').classList.add('ring-2', 'ring-blue-500');
            setTimeout(() => {
                document.getElementById('Description').classList.remove('ring-2', 'ring-blue-500');
            }, 3000);
        }



        // Form validation for BOQ-based tools
        document.addEventListener('DOMContentLoaded', function() {
            // Clear BOQ selection when navigating to different project
            const currentProjectId = '{{ $project->no_IO }}';
            const lastProjectId = sessionStorage.getItem('lastProjectId');
            
            if (lastProjectId && lastProjectId != currentProjectId) {
                // Clear any BOQ-related session data by making an AJAX call
                fetch('/clear-boq-session', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-Token': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    },
                    body: JSON.stringify({})
                }).catch(function(error) {
                    console.log('Note: BOQ session cleared on project change');
                });
            }
            
            // Store current project ID
            sessionStorage.setItem('lastProjectId', currentProjectId);
            
            @if(isset($selectedBoqItems) && $selectedBoqItems && $selectedBoqItems->count() > 0)
                @php
                    $unitMismatch = false;
                    $commonUnit = null;
                    foreach($selectedBoqItems as $item) {
                        if ($commonUnit === null) {
                            $commonUnit = $item->unit;
                        } elseif ($commonUnit !== $item->unit) {
                            $unitMismatch = true;
                            break;
                        }
                    }
                @endphp
                
                @if($unitMismatch)
                    // Disable form submission if units are mismatched
                    const form = document.querySelector('form');
                    const submitButton = form.querySelector('button[type="submit"]');
                    
                    if (submitButton) {
                        submitButton.disabled = true;
                        submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                        submitButton.innerHTML = '⚠️ Unit Tidak Konsisten - Tidak Dapat Submit';
                    }
                    
                    // Prevent form submission
                    form.addEventListener('submit', function(e) {
                        e.preventDefault();
                        showToast('Tidak dapat membuat tool karena unit BOQ item tidak konsisten. Silakan pilih ulang item dengan unit yang sama.', 'error');
                    });
                @else
                    console.log('BOQ items validated - {{ $selectedBoqItems->count() }} items with consistent unit: {{ $commonUnit }}');
                    
                    // Add quantity validation
                    function validateQuantity() {
                        const quantityInput = document.getElementById('quantity');
                        const warningDiv = document.getElementById('quantity-warning');
                        const boqMaxQuantity = parseFloat(document.querySelector('input[name="boq_max_quantity"]').value);
                        
                        if (quantityInput && warningDiv) {
                            const requestQuantity = parseFloat(quantityInput.value) || 0;
                            
                            if (requestQuantity > boqMaxQuantity) {
                                warningDiv.classList.remove('hidden');
                                quantityInput.classList.add('border-yellow-500');
                                quantityInput.classList.remove('border-gray-300');
                            } else {
                                warningDiv.classList.add('hidden');
                                quantityInput.classList.remove('border-yellow-500');
                                quantityInput.classList.add('border-gray-300');
                            }
                        }
                    }

                    // Run validation on page load and input change
                    const quantityInput = document.getElementById('quantity');
                    if (quantityInput) {
                        validateQuantity();
                        quantityInput.addEventListener('input', validateQuantity);
                        quantityInput.addEventListener('change', validateQuantity);
                    }
                @endif
            @endif
        });

    </script>

    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
</x-app-layout>