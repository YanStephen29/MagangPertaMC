<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                @if(isset($from_add_request) && $from_add_request)
                    Create Document from Request
                @else
                    Add New Document
                @endif
            </h2>
            <p class="text-sm text-gray-600 mt-1">
                @if(isset($from_add_request) && $from_add_request)
                    Creating document from Add Request workflow
                @else
                    Manage request documents for efficient tool management
                @endif
            </p>
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="w-full px-3 sm:px-6 lg:px-8 max-w-4xl mx-auto">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-4 sm:p-6 text-gray-900">

                    <!-- Notifikasi sudah ditangani di layout utama (app.blade.php) -->

                    <form action="{{ route('documents.store') }}" method="POST">
                        @csrf
                        
                        @if(isset($from_add_request) && $from_add_request && isset($project_id))
                            <input type="hidden" name="from_add_request" value="1">
                            <input type="hidden" name="project_id" value="{{ $project_id }}">
                        @endif

                        <div class="mb-4">
                            <label for="no_request" class="block text-sm font-medium text-gray-700 mb-2">
                                No Request *
                            </label>
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <input type="text" 
                                           id="no_request_prefix" 
                                           readonly
                                           placeholder="Choose type"
                                           class="w-20 px-3 py-2 border border-gray-300 rounded-l-md shadow-sm bg-gray-100 text-gray-700 focus:outline-none"
                                           style="border-right: none;">
                                </div>
                                <input type="text" 
                                       name="no_request_number" 
                                       id="no_request_number" 
                                       value="{{ old('no_request_number') }}"
                                       placeholder="Input only the number, e.g.: 001"
                                       class="flex-1 px-3 py-2 border border-gray-300 rounded-r-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                       required>
                                <input type="hidden" id="no_request_full" name="no_request" value="{{ old('no_request') }}">
                            </div>
                            @error('no_request')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            <p class="text-xs text-gray-500 mt-1">Input the serial number only. The code will be automatically added: MR (Material Request), SR (Service Request), FR (Facility Request), AS (Aset)</p>
                        </div>

                        <!-- Document Details Section -->
                        <div class="bg-gradient-to-br from-blue-50 via-cyan-50 to-indigo-50 rounded-2xl p-6 border border-blue-200 shadow-lg mb-4">
                            <div class="flex items-center mb-6">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-gradient-to-br from-blue-600 to-cyan-600 rounded-xl flex items-center justify-center shadow-lg">
                                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4 flex flex-col">
                                    <h4 class="text-lg font-bold text-gray-900">Document Details</h4>
                                    <p class="text-sm text-gray-600">Classification information and date</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label for="jenis_request" class="block text-sm font-medium text-gray-700 mb-2">
                                        Request Type *
                                    </label>
                                    <select name="jenis_request" 
                                            id="jenis_request" 
                                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                            required>
                                        <option value="">Choose request type...</option>
                                        <option value="Material Request" {{ old('jenis_request') == 'Material Request' ? 'selected' : '' }}>
                                            Material Request
                                        </option>
                                        <option value="Service Request" {{ old('jenis_request') == 'Service Request' ? 'selected' : '' }}>
                                            Service Request
                                        </option>
                                        <option value="Facility Request" {{ old('jenis_request') == 'Facility Request' ? 'selected' : '' }}>
                                            Facility Request
                                        </option>
                                        <option value="Aset" {{ old('jenis_request') == 'Aset' ? 'selected' : '' }}>
                                            Aset
                                        </option>
                                    </select>
                                    @error('jenis_request')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="date_issue" class="block text-sm font-medium text-gray-700 mb-2">
                                        Date Issue *
                                    </label>
                                    <input type="date" 
                                           name="date_issue" 
                                           id="date_issue" 
                                           value="{{ old('date_issue', date('Y-m-d')) }}"
                                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500"
                                           required>
                                    @error('date_issue')
                                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        <!-- Information Section -->
                        <div class="bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 rounded-2xl p-6 border border-green-200 shadow-md mb-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-10 h-10 bg-gradient-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center shadow-lg">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <h4 class="text-lg font-bold text-gray-900 mb-3">Important Information</h4>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                        <div class="flex items-start space-x-3 p-3 bg-white rounded-lg border border-green-100 shadow-sm">
                                            <div class="flex-shrink-0">
                                                <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Unique Number</p>
                                                <p class="text-xs text-gray-600 mt-1">Enter request number as per your requirements</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start space-x-3 p-3 bg-white rounded-lg border border-green-100 shadow-sm">
                                            <div class="flex-shrink-0">
                                                <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Verification</p>
                                                <p class="text-xs text-gray-600 mt-1">Ensure request number is unique and not used</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start space-x-3 p-3 bg-white rounded-lg border border-green-100 shadow-sm">
                                            <div class="flex-shrink-0">
                                                <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Assignment</p>
                                                <p class="text-xs text-gray-600 mt-1">Document can be assigned to tools in projects</p>
                                            </div>
                                        </div>
                                        
                                        <div class="flex items-start space-x-3 p-3 bg-white rounded-lg border border-green-100 shadow-sm">
                                            <div class="flex-shrink-0">
                                                <div class="w-5 h-5 bg-green-100 rounded-full flex items-center justify-center">
                                                    <svg class="w-3 h-3 text-green-600" fill="currentColor" viewBox="0 0 20 20">
                                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                                    </svg>
                                                </div>
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900">Consistency</p>
                                                <p class="text-xs text-gray-600 mt-1">Use consistent format for easier tracking</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 sm:gap-0">
                            <button type="submit" id="saveDocumentBtn" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-bold py-2 px-6 rounded-lg shadow-lg transform hover:scale-105 transition-all duration-200 focus:outline-none focus:ring-4 focus:ring-red-300 w-full sm:w-auto">
                                <span id="saveButtonText">Save Document</span>
                                <span id="saveButtonSpinner" class="hidden ml-2">
                                    <svg class="animate-spin h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                </span>
                            </button>
                            @if(isset($from_add_request) && $from_add_request && isset($project_id))
                                <a href="{{ route('projects.tools.index', $project_id) }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-colors duration-200 w-full sm:w-auto text-center">
                                    Back to Request List
                                </a>
                            @else
                                <a href="{{ route('documents.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-bold py-2 px-6 rounded-lg shadow-md transition-colors duration-200 w-full sm:w-auto text-center">
                                    Cancel
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const saveBtn = document.getElementById('saveDocumentBtn');
            const saveButtonText = document.getElementById('saveButtonText');
            const saveButtonSpinner = document.getElementById('saveButtonSpinner');
            
            // Auto-generate request number system
            const jenisRequestSelect = document.getElementById('jenis_request');
            const noRequestPrefix = document.getElementById('no_request_prefix');
            const noRequestNumber = document.getElementById('no_request_number');
            const noRequestFull = document.getElementById('no_request_full');
            const noRequestInput = document.querySelector('input[name="no_request"]');
            
            // Mapping jenis request to codes
            const requestCodes = {
                'Material Request': 'MR',
                'Service Request': 'SR',
                'Facility Request': 'FR',
                'Aset': 'AS'
            };
            
            // Update prefix when jenis request changes
            jenisRequestSelect.addEventListener('change', function() {
                const selectedJenis = this.value;
                const code = requestCodes[selectedJenis] || '';
                
                if (code) {
                    const currentYear = new Date().getFullYear();
                    noRequestPrefix.value = `${code}-${currentYear}-`;
                    noRequestPrefix.style.color = '#374151';
                } else {
                    noRequestPrefix.value = '';
                }
                
                updateFullRequestNumber();
            });
            
            // Update full request number when number changes
            noRequestNumber.addEventListener('input', function() {
                updateFullRequestNumber();
            });
            
            function updateFullRequestNumber() {
                const prefix = noRequestPrefix.value;
                const number = noRequestNumber.value;
                
                if (prefix && number) {
                    const fullNumber = prefix + number;
                    noRequestFull.value = fullNumber;
                    noRequestInput.value = fullNumber;
                } else {
                    noRequestFull.value = '';
                    noRequestInput.value = '';
                }
            }
            
            // Initialize if there's an old value
            const oldFullValue = noRequestFull.value || '';
            const oldNumberValue = noRequestNumber.value || '';
            
            if (oldFullValue && oldFullValue.includes('-')) {
                // Parse existing full number from hidden field
                const parts = oldFullValue.split('-');
                if (parts.length >= 3) {
                    noRequestPrefix.value = `${parts[0]}-${parts[1]}-`;
                    noRequestNumber.value = parts.slice(2).join('-');
                    updateFullRequestNumber();
                }
            } else if (oldNumberValue && jenisRequestSelect.value) {
                // Initialize with jenis request selection
                const selectedJenis = jenisRequestSelect.value;
                const code = requestCodes[selectedJenis];
                if (code) {
                    const currentYear = new Date().getFullYear();
                    noRequestPrefix.value = `${code}-${currentYear}-`;
                    noRequestNumber.value = oldNumberValue;
                    updateFullRequestNumber();
                }
            }
            
            form.addEventListener('submit', function(e) {
                console.log('Form submit event triggered');
                
                // Prevent double submission
                if (saveBtn.disabled) {
                    console.log('Button already disabled, preventing double submission');
                    e.preventDefault();
                    return false;
                }
                
                // Basic validation
                const noRequestFull = document.getElementById('no_request_full').value.trim();
                const noRequestNumber = document.getElementById('no_request_number').value.trim();
                const jenisRequest = document.getElementById('jenis_request').value;
                const dateIssue = document.getElementById('date_issue').value;
                
                console.log('Form validation check:', {
                    noRequestFull: noRequestFull,
                    noRequestNumber: noRequestNumber,
                    jenisRequest: jenisRequest,
                    dateIssue: dateIssue
                });
                
                if (!noRequestFull || !noRequestNumber || !jenisRequest || !dateIssue) {
                    console.log('Validation failed, preventing submission');
                    showToast('Please fill in all required fields', 'warning');
                    e.preventDefault();
                    return false;
                }
                
                // Ensure the full request number is set in the form
                updateFullRequestNumber();
                
                // Disable button and show loading state
                console.log('Disabling button and showing loading state');
                saveBtn.disabled = true;
                saveBtn.classList.add('opacity-75', 'cursor-not-allowed');
                saveButtonText.textContent = 'Saving...';
                saveButtonSpinner.classList.remove('hidden');
                
                console.log('Form validation passed, allowing submission');
                
                // Set a timeout to re-enable button if page doesn't redirect
                setTimeout(function() {
                    console.log('Timeout reached, re-enabling button');
                    if (saveBtn.disabled) {
                        saveBtn.disabled = false;
                        saveBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                        saveButtonText.textContent = 'Save Document';
                        saveButtonSpinner.classList.add('hidden');
                    }
                }, 10000); // 10 seconds timeout
                
                // Allow form submission to continue
                return true;
            });
            
            // Re-enable button if user navigates back
            window.addEventListener('pageshow', function(event) {
                if (event.persisted) {
                    saveBtn.disabled = false;
                    saveBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                    saveButtonText.textContent = 'Save Document';
                    saveButtonSpinner.classList.add('hidden');
                }
            });
        });

        // Show success modal if document was created successfully
        @if(session('document_created_success'))
            document.addEventListener('DOMContentLoaded', function() {
                // Reset form
                const form = document.querySelector('form');
                form.reset();
                
                // Re-enable button
                const saveBtn = document.getElementById('saveDocumentBtn');
                const saveButtonText = document.getElementById('saveButtonText');
                const saveButtonSpinner = document.getElementById('saveButtonSpinner');
                
                saveBtn.disabled = false;
                saveBtn.classList.remove('opacity-75', 'cursor-not-allowed');
                saveButtonText.textContent = 'Save Document';
                saveButtonSpinner.classList.add('hidden');
                
                // Show success modal
                setTimeout(() => {
                    showSuccessModal({
                        documentNo: '{{ session('document_no') }}',
                        projectId: '{{ session('project_id') }}',
                        fromAddRequest: {{ session('from_add_request') ? 'true' : 'false' }}
                    });
                }, 500);
            });
        @endif

        function showSuccessModal(data) {
            const modal = document.getElementById('successModal');
            const modalBackdrop = document.getElementById('successModalBackdrop');
            const modalWrapper = document.getElementById('successModalWrapper');
            const modalContent = document.getElementById('successModalContent');
            const documentNoSpan = document.getElementById('successDocumentNo');
            
            // Modal option elements
            const backToRequestOption = document.getElementById('backToRequestOption');
            const moveToBoqOption = document.getElementById('moveToBoqOption');
            const backToRequestTitle = document.getElementById('backToRequestTitle');
            const backToRequestDesc = document.getElementById('backToRequestDesc');
            const moveToBoqTitle = document.getElementById('moveToBoqTitle');
            const moveToBoqDesc = document.getElementById('moveToBoqDesc');
            
            console.log('Showing success modal with data:', data);
            
            // Set document number
            documentNoSpan.textContent = data.documentNo;
            
            // Set button URLs and text based on context
            if (data.fromAddRequest && data.projectId) {
                console.log('Setting URLs for Add Request workflow');
                
                // Back to Request List option
                backToRequestTitle.textContent = 'Back to Request List';
                backToRequestDesc.textContent = 'Return to the tools request list for this project';
                backToRequestOption.onclick = function() {
                    window.location.href = `{{ url('/projects') }}/${data.projectId}/tools`;
                };
                
                // Move to Choose BOQ option
                moveToBoqTitle.textContent = 'Move to Choose BOQ';
                moveToBoqDesc.textContent = 'Continue with BOQ selection and tool assignment';
                moveToBoqOption.onclick = function() {
                    // Add loading state
                    moveToBoqOption.style.opacity = '0.5';
                    moveToBoqOption.style.pointerEvents = 'none';
                    moveToBoqTitle.textContent = 'Loading...';
                    
                    // Small delay to ensure document is fully saved
                    setTimeout(() => {
                        // URL encode the document number to handle special characters like /
                        const encodedDocumentNo = encodeURIComponent(data.documentNo);
                        window.location.href = `{{ url('/requests/document') }}/${encodedDocumentNo}/select-boq-items?project_id=${data.projectId}`;
                    }, 1500); // 1.5 second delay
                };
                
            } else {
                console.log('Setting URLs for regular workflow');
                
                // View Documents option
                backToRequestTitle.textContent = 'View All Documents';
                backToRequestDesc.textContent = 'Go to documents list to manage all documents';
                backToRequestOption.onclick = function() {
                    window.location.href = '{{ route('documents.index') }}';
                };
                
                // Move to Choose BOQ option (for regular workflow, go to document's BOQ selection)
                moveToBoqTitle.textContent = 'Move to Choose BOQ';
                moveToBoqDesc.textContent = 'Continue with BOQ selection for this document';
                moveToBoqOption.onclick = function() {
                    // Add loading state
                    moveToBoqOption.style.opacity = '0.5';
                    moveToBoqOption.style.pointerEvents = 'none';
                    moveToBoqTitle.textContent = 'Loading...';
                    
                    // Small delay to ensure document is fully saved
                    setTimeout(() => {
                        // URL encode the document number to handle special characters like /
                        const encodedDocumentNo = encodeURIComponent(data.documentNo);
                        window.location.href = `{{ url('/requests/document') }}/${encodedDocumentNo}/select-boq-items?project_id=${data.projectId}`;
                    }, 1500); // 1.5 second delay
                };
                
                // Update icons for regular workflow
                const backIcon = backToRequestOption.querySelector('.w-6.h-6');
                backIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>`;
                
                const moveIcon = moveToBoqOption.querySelector('.w-6.h-6');
                moveIcon.innerHTML = `<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>`;
            }
            
            // Show modal with animation (following the same pattern as other modals)
            modal.classList.remove('hidden');
            
            // Trigger reflow to ensure the element is rendered
            modal.offsetHeight;
            
            setTimeout(() => {
                modalBackdrop.classList.remove('opacity-0');
                modalWrapper.classList.remove('opacity-0');
                modalContent.classList.remove('scale-95');
                modalContent.classList.add('scale-100');
            }, 10);
        }

        function closeSuccessModal() {
            const modal = document.getElementById('successModal');
            const modalBackdrop = document.getElementById('successModalBackdrop');
            const modalWrapper = document.getElementById('successModalWrapper');
            const modalContent = document.getElementById('successModalContent');
            
            modalBackdrop.classList.add('opacity-0');
            modalWrapper.classList.add('opacity-0');
            modalContent.classList.remove('scale-100');
            modalContent.classList.add('scale-95');
            
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 300);
        }
    </script>

    <!-- Success Modal -->
    <div id="successModal" class="fixed inset-0 hidden z-50">
        <!-- Backdrop with blur -->
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-all duration-300 opacity-0" id="successModalBackdrop"></div>
        
        <!-- Modal -->
        <div class="fixed inset-0 flex items-center justify-center p-4 pt-16 pb-8 overflow-y-auto transition-all duration-300 opacity-0" id="successModalWrapper">
            <div class="bg-white rounded-2xl shadow-2xl max-w-lg w-full relative transform transition-all duration-300 scale-95 overflow-hidden border border-gray-100" id="successModalContent">
                <!-- Modal Header -->
                <div class="relative bg-gradient-to-r from-green-600 via-green-700 to-emerald-700 px-6 py-3">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-white">
                                Document Berhasil Disimpan!
                            </h3>
                            <p class="text-green-100 text-xs mt-0.5">Document <span id="successDocumentNo" class="font-medium"></span> telah berhasil dibuat</p>
                        </div>
                        <button type="button" onclick="closeSuccessModal()" class="p-2 text-white hover:text-gray-200 hover:bg-white hover:bg-opacity-20 rounded-lg transition-all duration-200">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>
                    
                    <!-- Decorative elements -->
                    <div class="absolute top-0 right-0 -mt-1 -mr-1 w-10 h-10 bg-white bg-opacity-10 rounded-full"></div>
                    <div class="absolute bottom-0 left-0 -mb-1 -ml-1 w-8 h-8 bg-white bg-opacity-10 rounded-full"></div>
                </div>
                
                <!-- Modal Body -->
                <div class="p-6 bg-gray-50">
                    <!-- Status/Info Card -->
                    <div class="mb-6">
                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 border-l-4 border-green-500 rounded-lg p-4 shadow-sm">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-green-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-3 flex-1">
                                    <h4 class="text-sm font-semibold text-green-900 mb-1">Success!</h4>
                                    <p class="text-sm text-green-700">Pilih langkah selanjutnya yang ingin Anda lakukan</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <!-- Option 1: Back to Request List -->
                        <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-gray-300 hover:bg-gray-50 cursor-pointer transition-all duration-200" id="backToRequestOption">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-gray-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-medium text-gray-900" id="backToRequestTitle">Back to Request List</h4>
                                    <p class="text-gray-600 mt-1" id="backToRequestDesc">Return to the tools request list</p>
                                </div>
                                <div class="flex-shrink-0">
                                    <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </div>
                            </div>
                        </div>

                        <!-- Option 2: Move to Choose BOQ -->
                        <div class="border-2 border-gray-200 rounded-lg p-4 hover:border-blue-300 hover:bg-blue-50 cursor-pointer transition-all duration-200" id="moveToBoqOption">
                            <div class="flex items-center space-x-4">
                                <div class="flex-shrink-0">
                                    <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center">
                                        <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-lg font-medium text-gray-900" id="moveToBoqTitle">Move to Choose BOQ</h4>
                                    <p class="text-gray-600 mt-1" id="moveToBoqDesc">Continue with BOQ selection process</p>
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
</x-app-layout>