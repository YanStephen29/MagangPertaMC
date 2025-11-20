<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
            <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                Data GL Code
            </h2>
            <div class="flex flex-col sm:flex-row gap-2">
                <button type="button" onclick="openProjectModal()" class="bg-emerald-600 hover :bg-emerald-700 text-white font-medium py-2 px-4 rounded-md shadow-sm transition-colors duration-200 inline-flex items-center text-sm w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                    </svg>
                    Export GL by Project to Excel
                </button>
                @canAccess('bidang_create')
                <a href="{{ route('bidangs.create') }}" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-medium py-2 px-4 rounded-md shadow-md transform hover:scale-105 transition-all duration-200 inline-flex items-center text-sm w-full sm:w-auto justify-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                    </svg>
                    Add New GL Code
                </a>
                @endcanAccess
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="w-full px-3 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                <div class="p-3 sm:p-6 text-gray-900">

                    {{-- === BAGIAN PENCARIAN === --}}
                    <div class="mb-6 bg-gray-50 p-4 rounded-lg border">
                        <form method="GET" action="{{ route('bidangs.index') }}" class="space-y-4">
                            <div class="flex flex-col lg:flex-row gap-4 items-end">
                                <!-- Search Input -->
                                <div class="flex-1">
                                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">
                                        🔍 Search GL Code
                                    </label>
                                    <input type="text"
                                           name="search"
                                           id="search"
                                           value="{{ $search ?? '' }}" {{-- Display previous search value --}}
                                           placeholder="Search by GL Code or GL Name..."
                                           class="w-full px-4 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-red-500 focus:border-red-500 text-sm">
                                </div>

                                <!-- Action Buttons -->
                                <div class="flex flex-col sm:flex-row gap-2 w-full lg:w-auto">
                                    <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md shadow-sm transition-colors duration-200 text-sm font-medium inline-flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                        </svg>
                                        Search
                                    </button>

                                    {{-- Reset button goes back to the index without search parameter --}}
                                    <a href="{{ route('bidangs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md shadow-sm transition-colors duration-200 text-sm font-medium inline-flex items-center justify-center">
                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                                        </svg>
                                        Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- === AKHIR BAGIAN PENCARIAN === --}}

                    {{-- Tabel Data Bidang (Tanpa Checkbox Ekspor) --}}
                    @if($bidangs->count() > 0)
                        <div class="overflow-x-auto -mx-3 sm:mx-0">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-red-50 to-blue-50">
                                    <tr>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-red-700 uppercase tracking-wider border-r border-gray-200">GL Code</th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-red-700 uppercase tracking-wider border-r border-gray-200">GL Name</th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-right text-xs font-bold text-red-700 uppercase tracking-wider border-r border-gray-200">Total GL Expenditure</th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-red-700 uppercase tracking-wider border-r border-gray-200 hidden md:table-cell">Created At</th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-red-700 uppercase tracking-wider">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($bidangs as $bidang)
                                        <tr class="hover:bg-red-50 transition-colors duration-150">
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm font-bold text-red-600 border-r border-gray-100">{{ $bidang->kode_GL }}</td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 text-sm text-gray-900 border-r border-gray-100 break-words">{{ $bidang->nama_Bidang }}</td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-800 font-semibold border-r border-gray-100 text-right">Rp {{ number_format($bidang->total_expenditure ?? 0, 0, ',', '.') }}</td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500 border-r border-gray-100 hidden md:table-cell">{{ $bidang->created_at ? $bidang->created_at->format('d/m/Y') : '-' }}</td>
                                            <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm font-medium">
                                                <div class="flex flex-col sm:flex-row gap-2 sm:gap-1">
                                                    @canAccess('bidang_update')
                                                    <a href="{{ route('bidangs.edit', $bidang) }}" class="bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded-md inline-flex items-center text-xs transition-colors duration-200 justify-center" title="Edit GL Code">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg> Edit
                                                    </a>
                                                    @endcanAccess
                                                    @canAccess('bidang_read')
                                                    <a href="{{ route('bidangs.tools', $bidang->kode_GL) }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-md inline-flex items-center text-xs transition-colors duration-200 w-full sm:w-auto justify-center" title="View Usage Details">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg> Details
                                                    </a>
                                                    @endcanAccess
                                                     @canAccess('bidang_delete')
                                                    <button type="button" onclick="openDeleteBidangModal('{{ $bidang->kode_GL }}', '{{ $bidang->nama_Bidang }}', '{{ route('bidangs.destroy', $bidang) }}')" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded-md inline-flex items-center text-xs transition-colors duration-200 w-full sm:w-auto justify-center" title="Delete GL Code">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg> Delete
                                                    </button>
                                                     @endcanAccess
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        {{-- Empty State --}}
                        <div class="text-center py-12">
                             <div class="mb-4"><svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg></div>
                            <p class="text-xl text-gray-500 mb-6">
                                @if(request('search')) No GL codes found matching search.
                                @else There are no GL codes added yet.
                                @endif
                            </p>
                             @if(!request('search'))
                                @canAccess('bidang_create')
                                <a href="{{ route('bidangs.create') }}" class="bg-gradient-to-r from-red-600 to-red-700 hover:from-red-700 hover:to-red-800 text-white font-medium py-2 px-6 rounded-md shadow-md transform hover:scale-105 transition-all duration-200 inline-flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg> Add First GL Code
                                </a>
                                @endcanAccess
                             @endif
                        </div>
                    @endif

                </div>
            </div>
        </div>
    </div>

    {{-- === MODAL PEMILIHAN PROJECT === --}}
    <div id="projectExportModal" class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm hidden" style="z-[99999]" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        {{-- Backdrop --}}
        <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm transition-opacity opacity-0" id="projectExportModalBackdrop" aria-hidden="true" style="transition: opacity 150ms ease-out;"></div>
        {{-- Modal Container --}}
        <div class="fixed inset-0 z-10 flex items-center justify-center p-4">
            {{-- Modal Panel --}}
            <div id="projectExportModalContent" class="bg-white rounded-lg shadow-xl max-w-lg w-full relative transition-all"
                 style="opacity: 0; transform: scale(0.95); transition: opacity 150ms ease-out, transform 150ms ease-out;">
                <div class="p-6">
                    {{-- Modal Header --}}
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                             <svg class="w-6 h-6 text-blue-600 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 13h6m-3-3v6m5 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                             </svg>
                            Select Projects for Export
                        </h3>
                        <button type="button" onclick="closeProjectModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    {{-- Modal Body --}}
                    <div class="mb-6">
                        <p class="text-sm text-gray-500 mb-3">Choose projects to include. All GL Codes will be listed with expenditures from selected projects only.</p>
                        {{-- Project List Container --}}
                        <div id="projectListContainer" class="max-h-60 overflow-y-auto border border-gray-300 rounded-md bg-white divide-y divide-gray-200">
                            {{-- Sticky Select All --}}
                            <div class="sticky top-0 bg-gray-50 px-4 py-2 border-b border-gray-300">
                                <div class="flex items-center">
                                    <input id="selectAllProjectsCheckbox" type="checkbox" class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                    <label for="selectAllProjectsCheckbox" class="ml-3 block text-sm font-bold text-gray-700 select-none cursor-pointer">Select All Projects</label>
                                </div>
                            </div>
                            {{-- Scrollable List --}}
                             <div class="divide-y divide-gray-200">
                                @if(isset($projects) && $projects->isNotEmpty())
                                    @foreach ($projects as $project)
                                        <div class="flex items-center px-4 py-2 hover:bg-gray-50">
                                            <input id="project_{{ $project->no_IO }}" name="project_ids[]" value="{{ $project->no_IO }}" type="checkbox" class="project-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500">
                                            <label for="project_{{ $project->no_IO }}" class="ml-3 block text-sm text-gray-700 flex-1 cursor-pointer select-none">
                                                <span class="font-medium">{{ $project->title_project }}</span> <span class="text-gray-500">({{ $project->no_IO }})</span>
                                            </label>
                                        </div>
                                    @endforeach
                                @else
                                    <p class="px-4 py-3 text-sm text-gray-500 italic">No projects available.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Modal Footer --}}
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeProjectModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 border border-gray-300 rounded-lg transition-colors duration-200 text-sm font-medium">
                            Cancel
                        </button>
                        <button type="button" id="modalExportButton" class="px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg transition-colors duration-200 text-sm font-medium inline-flex items-center disabled:opacity-50 disabled:cursor-not-allowed" disabled>
                           <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                            </svg>
                            Export Selected
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- === AKHIR MODAL === --}}


    <!-- Delete Bidang Confirmation Modal (Kode tidak berubah) -->
    <div id="deleteBidangModal" class="fixed inset-0 hidden z-[99999]">
         <div class="fixed inset-0 bg-black bg-opacity-50 backdrop-blur-sm" id="deleteBidangModalBackdrop" style="z-index: 99999;"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4 z-[100000]">
            <div class="bg-white rounded-lg shadow-xl max-w-md w-full relative" id="deleteBidangModalContent" style="z-index: 100001;">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-medium text-gray-900 flex items-center">
                            <svg class="w-6 h-6 text-red-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
                            Confirm Delete GL Code
                        </h3>
                        <button type="button" onclick="closeDeleteBidangModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                    <div class="mb-6">
                        <p class="text-gray-600 mb-3">Are you sure you want to delete this GL code?</p>
                        <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                            <p class="text-red-800 font-semibold" id="bidangInfo">GL Code Info</p>
                            <p class="text-red-600 text-sm mt-1">All tools related to this GL code will lose their references and cannot be recovered</p>
                        </div>
                    </div>
                    <div class="flex justify-end space-x-3">
                        <button type="button" onclick="closeDeleteBidangModal()" class="px-4 py-2 text-gray-600 hover:text-gray-800 hover:bg-gray-100 border border-gray-300 rounded-lg transition-colors duration-200">Cancel</button>
                        <form id="deleteBidangForm" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors duration-200">Yes, Delete GL Code</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- JavaScript --}}
    <script>
        const projectModal = document.getElementById('projectExportModal');
        const projectModalContent = document.getElementById('projectExportModalContent');
        const projectModalBackdrop = document.getElementById('projectExportModalBackdrop');
        const selectAllProjectsCheckbox = document.getElementById('selectAllProjectsCheckbox');
        const projectCheckboxes = document.querySelectorAll('#projectListContainer .project-checkbox');
        const modalExportButton = document.getElementById('modalExportButton');

        function openProjectModal() {
            projectModal.classList.remove('hidden');
            if (selectAllProjectsCheckbox) selectAllProjectsCheckbox.checked = false;
            projectCheckboxes.forEach(cb => cb.checked = false);
            toggleModalExportButton();
            setTimeout(() => {
                projectModalBackdrop.style.opacity = '1';
                projectModalContent.style.opacity = '1';
                projectModalContent.style.transform = 'scale(1)';
            }, 10);
        }

        function closeProjectModal() {
            // Animasi fade-out
            projectModalBackdrop.style.opacity = '0';
            projectModalContent.style.opacity = '0';
            projectModalContent.style.transform = 'scale(0.95)';
             // Match timeout to CSS transition duration if specified, otherwise ~150ms is common
            setTimeout(() => {
                projectModal.classList.add('hidden');
            }, 150);
        }

        function toggleModalExportButton() {
            // Ensure modalExportButton exists before accessing properties
             if (!modalExportButton) return;
            const anyProjectChecked = Array.from(projectCheckboxes).some(checkbox => checkbox.checked);
            modalExportButton.disabled = !anyProjectChecked;
        }

        // Event listener untuk backdrop modal project
        if (projectModalBackdrop) {
            projectModalBackdrop.addEventListener('click', closeProjectModal);
        }
         // Event listener untuk tombol escape modal project
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && projectModal && !projectModal.classList.contains('hidden')) {
                closeProjectModal();
            }
        });


        // Event listener untuk checkbox "Select All" Project
        if (selectAllProjectsCheckbox) {
            selectAllProjectsCheckbox.addEventListener('change', function() {
                projectCheckboxes.forEach(checkbox => {
                    checkbox.checked = selectAllProjectsCheckbox.checked;
                });
                toggleModalExportButton();
            });
        }

        // Event listener untuk checkbox project individual
        projectCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (!checkbox.checked && selectAllProjectsCheckbox) {
                   selectAllProjectsCheckbox.checked = false;
                } else if (selectAllProjectsCheckbox) {
                     const allChecked = Array.from(projectCheckboxes).every(cb => cb.checked);
                     // Only update selectAll if it exists
                     if (selectAllProjectsCheckbox) {
                        selectAllProjectsCheckbox.checked = allChecked;
                     }
                }
                toggleModalExportButton();
            });
        });

        // Event listener untuk tombol Export di dalam modal
        if (modalExportButton) {
            modalExportButton.addEventListener('click', function() {
                const selectedProjects = Array.from(projectCheckboxes)
                                            .filter(checkbox => checkbox.checked)
                                            .map(checkbox => checkbox.value);

                if (selectedProjects.length === 0) {
                    alert('Please select at least one project.');
                    return;
                }

                // Buat form tersembunyi
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '{{ route("bidangs.export") }}'; // Target route export

                // Tambahkan CSRF token
                const csrfInput = document.createElement('input');
                csrfInput.type = 'hidden';
                csrfInput.name = '_token';
                csrfInput.value = '{{ csrf_token() }}';
                form.appendChild(csrfInput);

                // Tambahkan input hidden untuk setiap project ID yang dipilih
                selectedProjects.forEach(projectId => {
                    const input = document.createElement('input');
                    input.type = 'hidden';
                    input.name = 'selected_projects[]';
                    input.value = projectId;
                    form.appendChild(input);
                });

                // Tambahkan form ke body dan submit
                document.body.appendChild(form);
                form.submit();

                // Hapus form setelah submit (opsional tapi baik untuk kebersihan DOM)
                document.body.removeChild(form);

                // Tutup modal setelah submit
                closeProjectModal();
            });
        }

        // --- Kode Modal Delete Bidang Anda yang Sudah Ada ---
         function openDeleteBidangModal(kodeGL, namaBidang, deleteUrl) {
            document.getElementById('bidangInfo').textContent = `${kodeGL} - ${namaBidang}`;
            document.getElementById('deleteBidangForm').action = deleteUrl;
            const modal = document.getElementById('deleteBidangModal');
             if(modal) modal.classList.remove('hidden'); // Check if modal exists
            const modalContent = document.getElementById('deleteBidangModalContent');
             if(modalContent){ // Check if content exists
                 modalContent.style.transform = 'scale(0.95)';
                 modalContent.style.opacity = '0';
                 modalContent.style.transition = 'all 0.15s ease-out';
                 setTimeout(() => {
                    modalContent.style.transform = 'scale(1)';
                    modalContent.style.opacity = '1';
                }, 10);
            }
        }

        function closeDeleteBidangModal() {
            const modalContent = document.getElementById('deleteBidangModalContent');
             if(modalContent){ // Check if content exists
                modalContent.style.transform = 'scale(0.95)';
                modalContent.style.opacity = '0';
                modalContent.style.transition = 'all 0.15s ease-in';
                setTimeout(() => {
                     const modal = document.getElementById('deleteBidangModal');
                     if(modal) modal.classList.add('hidden'); // Check if modal exists
                    modalContent.style.transform = '';
                    modalContent.style.opacity = '';
                    modalContent.style.transition = '';
                }, 150);
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
             // Event listeners for Delete Bidang Modal
            const deleteBidangModal = document.getElementById('deleteBidangModal');
            if (deleteBidangModal) {
                const bidangModalBackdrop = document.getElementById('deleteBidangModalBackdrop');
                if (bidangModalBackdrop) {
                    bidangModalBackdrop.addEventListener('click', closeDeleteBidangModal);
                }
                 // Moved Escape listener inside the check for deleteBidangModal
                document.addEventListener('keydown', function(e) {
                    if (e.key === 'Escape' && !deleteBidangModal.classList.contains('hidden')) {
                        closeDeleteBidangModal();
                    }
                });
            }

            if (modalExportButton) {
                toggleModalExportButton();
            }
        });
        // --- Akhir Kode Modal Delete Bidang ---

    </script>
</x-app-layout>

