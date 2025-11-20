<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                Document Created Successfully
            </h2>
            <p class="text-sm text-gray-600 mt-1">Choose your next action</p>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="w-full px-3 sm:px-6 lg:px-8 max-w-4xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">
                    


                    <div class="text-center mb-8">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Document Created!</h3>
                        <p class="text-gray-600">Document <strong>{{ $documentNo }}</strong> has been successfully created.</p>
                    </div>

                    <div class="space-y-4">
                        <!-- Option 1: Move to Choose BOQ -->
                        <div class="border-2 border-blue-200 rounded-lg p-6 hover:border-blue-300 hover:bg-blue-50 cursor-pointer transition-all duration-200" onclick="selectNextAction('choose-boq')">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-medium text-gray-900">1. Continue to Choose BOQ</h4>
                                    <p class="text-gray-600 mt-1">Continue with BOQ selection process. You can assign tools to this document and manage BOQ items.</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Option 2: Back to List Request -->
                        <div class="border-2 border-gray-200 rounded-lg p-6 hover:border-gray-300 hover:bg-gray-50 cursor-pointer transition-all duration-200" onclick="selectNextAction('back-to-list')">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-medium text-gray-900">2. Back to List Request</h4>
                                    <p class="text-gray-600 mt-1">Return to the tools request list for project {{ $projectModel->title_project }}</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function selectNextAction(action) {
            if (action === 'choose-boq') {
                // Redirect to tools index with BOQ selection focus
                window.location.href = '{{ route("projects.tools.index", $projectModel->no_IO) }}?highlight_document={{ $documentNo }}';
            } else if (action === 'back-to-list') {
                // Redirect back to tools list
                window.location.href = '{{ route("projects.tools.index", $projectModel->no_IO) }}';
            }
        }
    </script>
</x-app-layout>