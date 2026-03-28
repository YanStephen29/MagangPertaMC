<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                    📋 Add New Section
                </h2>
                <p class="text-sm text-gray-600 mt-1">BOQ: {{ $boq->nomorBoq }} - {{ $project->title_project }}</p>
            </div>
            <div class="mb-4">
                <a href="{{ route('projects.boq.index', $project) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm inline-flex items-center">
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
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                <div class="p-6">


                    <form action="{{ route('projects.boq.sections.store', [$project, $boq]) }}" method="POST" class="space-y-6">
                        @csrf

                        <!-- Section ID (Auto-increment) -->
                        <div>
                            <label for="section_id" class="block text-sm font-medium text-gray-700 mb-2">
                                🔢 Section ID
                            </label>
                            <input type="text" 
                                   name="section_id" 
                                   id="section_id" 
                                   value="{{ $nextSectionId }}"
                                   readonly
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-600 cursor-not-allowed">
                            <p class="text-sm text-gray-600 mt-1">ID section will be generated automatically (Section -{{ $nextSectionId }})</p>
                        </div>

                        <!-- Section Name -->
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700 mb-2">
                                📝 Section Name <span class="text-red-500">*</span>
                            </label>
                            <input type="text" 
                                   name="nama" 
                                   id="nama" 
                                   value="{{ old('nama') }}"
                                   placeholder="Contoh: Material & Equipment, Labor Cost, Overhead, dll"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-green-500 focus:border-green-500"
                                   maxlength="45"
                                   required>
                            @error('nama')
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
                                            <li>Section will be added to BOQ: <strong>{{ $boq->nomorBoq }}</strong></li>
                                            <li>Total price of the section will be calculated based on the added details</li>
                                            <li>After the section is created, you can add detail items</li>
                                            <li>Section will have ID: <strong>{{ $nextSectionId }}</strong></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex flex-col sm:flex-row justify-end gap-4">
                            <a href="{{ route('projects.boq.index', $project) }}" 
                               class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded-md transition-colors duration-200 text-center">
                                Cancel
                            </a>
                            <button type="submit" 
                                    class="bg-gradient-to-r from-green-600 to-green-700 hover:from-green-700 hover:to-green-800 text-white px-6 py-2 rounded-md shadow-md transform hover:scale-105 transition-all duration-200">
                                Add Section
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>