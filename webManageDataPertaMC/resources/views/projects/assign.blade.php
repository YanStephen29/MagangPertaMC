<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                    🎯 Assign Project Manager
                </h2>
                <p class="text-sm text-gray-600 mt-1">Assign project "{{ $project->title_project }}" to a Project Manager</p>
            </div>
            <div class="flex gap-2 mt-3 sm:mt-0">
                <a href="{{ route('projects.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium inline-flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m0 7h18"></path>
                    </svg>
                    Back to Projects
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="max-w-2xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                <div class="p-6">
                    <div class="mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Project Details</h3>
                        <div class="bg-gray-50 p-4 rounded-md">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Project No:</span>
                                    <p class="text-sm font-bold text-red-600">{{ $project->no_IO }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Project Title:</span>
                                    <p class="text-sm text-gray-900">{{ $project->title_project }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Created By:</span>
                                    <p class="text-sm text-gray-900">{{ $project->admin->username ?? 'Unknown' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Currently Assigned:</span>
                                    @if($project->assignedTo)
                                        <div class="flex items-center mt-1">
                                            <div class="flex-shrink-0 h-6 w-6 mr-2">
                                                <div class="h-6 w-6 rounded-full flex items-center justify-center text-white font-bold text-xs" style="background-color: {{ $project->assignedTo->getRoleColor() }}">
                                                    {{ substr($project->assignedTo->username, 0, 2) }}
                                                </div>
                                            </div>
                                            <span class="text-sm text-gray-900">{{ $project->assignedTo->username }}</span>
                                        </div>
                                    @else
                                        <p class="text-sm text-gray-400">Not assigned</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('projects.assign.store', $project) }}" method="POST">
                        @csrf
                        
                        <div class="mb-6">
                            <label for="assigned_to" class="block text-sm font-medium text-gray-700 mb-2">
                                Assign to Project Manager
                            </label>
                            <select name="assigned_to" id="assigned_to" class="block w-full border border-gray-300 rounded-md px-3 py-2 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500">
                                <option value="">-- Remove Assignment --</option>
                                @foreach($projectManagers as $pm)
                                    <option value="{{ $pm->admin_id }}" {{ $project->assigned_to == $pm->admin_id ? 'selected' : '' }}>
                                        {{ $pm->username }} ({{ $pm->role }})
                                    </option>
                                @endforeach
                            </select>
                            @error('assigned_to')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-medium py-2 px-6 rounded-md shadow-sm transition-colors duration-200 inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                Save Assignment
                            </button>
                            <a href="{{ route('projects.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2 px-6 rounded-md shadow-sm transition-colors duration-200 inline-flex items-center">
                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>