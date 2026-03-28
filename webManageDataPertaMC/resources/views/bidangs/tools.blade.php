<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                    GL Code Usage Details
                </h2>
                <p class="text-gray-600 font-medium">
                    @if ($bidang)
                        {{ $bidang->nama_Bidang }} :
                    @endif
                    <span class="text-md text-green-700 font-semibold">{{ $kodeGL }}</span>
                </p>
            </div>
            <a href="{{ route('bidangs.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to GL Code List
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="w-full px-3 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                <div class="p-3 sm:p-6 text-gray-900">

                
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
                                           value="{{ $search ?? '' }}" {{-- Tampilkan nilai pencarian sebelumnya --}}
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

                                    {{-- Tombol reset mengarah kembali ke halaman yang sama TANPA parameter search --}}
                                    <a href="{{ route('bidangs.tools', ['kodeGL' => $kodeGL]) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md shadow-sm transition-colors duration-200 text-sm font-medium inline-flex items-center justify-center">
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


                    @if($tools->count() > 0)
                        <div class="overflow-x-auto -mx-3 sm:mx-0">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-red-50 to-blue-50">
                                    <tr>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-red-700 uppercase tracking-wider border-r border-gray-200">
                                            Project Name
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-red-700 uppercase tracking-wider border-r border-gray-200">
                                            No. I/O
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-red-700 uppercase tracking-wider border-r border-gray-200">
                                            Total Project Expenditure
                                        </th>
                                        <th class="px-3 sm:px-6 py-3 sm:py-4 text-left text-xs font-bold text-red-700 uppercase tracking-wider">
                                            Actions
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    @foreach($tools as $tool)
                                        {{-- Tambahkan pengecekan ini untuk memastikan $tool valid dan memiliki ID --}}
                                        @if($tool && $tool->idTools)
                                            @php
                                                $totalSpending = 0;
                                                // Pastikan relasi 'request' ada sebelum diakses
                                                if ($tool->request) {
                                                    // Akses melalui rantai relasi yang benar
                                                    foreach($tool->request->requestDetails as $rd) {
                                                        if ($rd->detail) {
                                                            $totalSpending += $rd->requested_quantity * $rd->detail->harga_satuan;
                                                        }
                                                    }
                                                }
                                            @endphp
                                            <tr class="hover:bg-red-50 transition-colors duration-150">
                                                <td class="px-3 sm:px-6 py-3 sm:py-4 text-sm text-gray-900 border-r border-gray-100 break-words">
                                                    {{ $tool->project->title_project ?? '-' }}
                                                </td>
                                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-500 border-r border-gray-100">
                                                    {{ $tool->project->no_IO ?? '-' }}
                                                </td>
                                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm text-gray-800 font-semibold border-r border-gray-100">
                                                    Rp {{ number_format($totalSpending, 0, ',', '.') }}
                                                </td>
                                                <td class="px-3 sm:px-6 py-3 sm:py-4 whitespace-nowrap text-sm font-medium">
                                                    {{-- Memberikan parameter 'tool' secara eksplisit --}}
                                                    <a href="{{ route('bidangs.tools.details',['tool' => $tool->idTools]) }}" class="bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded-md inline-flex items-center text-xs transition-colors duration-200 justify-center">
                                                        <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                                        See Details
                                                    </a>
                                                </td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
            
                        <div class="text-center py-12">
                            <div class="mb-4">
                                <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <p class="text-xl text-gray-500">
                                {{-- Pesan berbeda jika ada filter aktif --}}
                                @if(request('search'))
                                    No usage data found matching your search criteria for this GL Code.
                                @else
                                    There is no usage data for this GL Code.
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

