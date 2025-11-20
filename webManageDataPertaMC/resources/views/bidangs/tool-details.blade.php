<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                    Project Expenditure Details
                </h2>
                <p class="text-md text-gray-600 font-medium mt-1">
                    {{ $tool->project->title_project ?? 'Project Name Not Found' }}
                </p>
            </div>
            {{-- Tombol kembali disamakan dengan gaya tombol sekunder di halaman lain --}}
            <a href="{{ route('bidangs.tools', ['kodeGL' => $tool->kode_GL]) }}" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors shadow-sm inline-flex items-center">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to GL Code Usage
            </a>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="w-full px-3 sm:px-6 lg:px-8">
            {{-- Kartu Informasi Umum --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200 mb-6">
                <div class="divide-y divide-gray-200">
                    <div class="px-6 py-5">
                        <h3 class="text-lg font-semibold text-gray-900">General Information Request</h3>
                    </div>
                    <div class="px-6 py-6 space-y-6">
                        {{-- Bagian Request Description --}}
                        <dl>
                            <div>
                                <dt class="text-sm font-medium text-gray-500 flex items-center">
                                    Request Description
                                </dt>
                                <dd class="mt-1 text-lg font-bold text-gray-800 ml-6.5">{{ $tool->Description }}</dd>
                            </div>
                        </dl>

                        {{-- Tabel untuk Quantity, Unit, dan No. I/O --}}
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200 border border-gray-200 rounded-lg shadow-sm">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                            Quantity
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                            Unit
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">
                                            No. I/O
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-100">
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-gray-800">
                                            {{ $tool->quantity }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-gray-800">
                                            {{ $tool->unit }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-lg font-bold text-gray-800 font-mono">
                                            {{ $tool->no_IO }}
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-md font-semibold text-gray-700 mb-4">Summary</h4>
                             @php
                                // Hitung Grand Total di sini agar bisa digunakan di stats
                                $grandTotalBoq = 0;
                                $itemCount = 0;
                                if ($tool->request && $tool->request->requestDetails) {
                                    foreach ($tool->request->requestDetails as $rd) {
                                        if ($rd->detail) {
                                            $grandTotalBoq += $rd->requested_quantity * $rd->detail->harga_satuan;
                                            $itemCount++;
                                        }
                                    }
                                }
                                $requestStatus = $tool->request ? $tool->request->getStatusDisplay() : 'N/A';
                                $requestStatusColor = $tool->request ? $tool->request->getStatusColor() : 'gray';
                                $toolStatus = $tool->getStatusToolsDisplay();
                                $toolStatusColor = $tool->getStatusToolsColor();
                            @endphp
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                                <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
                                    <div class="text-blue-600 text-sm font-medium">Total Expenditure</div>
                                    <div class="text-2xl font-bold text-blue-900">Rp {{ number_format($grandTotalBoq, 0, ',', '.') }}</div>
                                </div>

                                <div class="bg-green-50 p-4 rounded-lg border border-green-200">
                                    <div class="text-green-600 text-sm font-medium">BoQ Items Used</div>
                                    <div class="text-2xl font-bold text-green-900">{{ $itemCount }}</div>
                                </div>

                                <div class="bg-yellow-50 p-4 rounded-lg border border-yellow-200">
                                    <div class="text-yellow-600 text-sm font-medium">Request Status</div>
                                    <div class="text-xl font-bold text-yellow-900">{{ $requestStatus }}</div>
                                </div>

                                <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
                                    <div class="text-purple-600 text-sm font-medium">Tool Status</div>
                                    <div class="text-xl sm:text-xl font-bold text-{{$toolStatusColor}}-900 truncate">{{ $toolStatus }}</div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            {{-- Kartu Tabel Rincian Item BoQ (Tidak Berubah, hanya pemindahan kalkulasi $grandTotal) --}}
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg border border-gray-200">
                <div class="divide-y divide-gray-200">
                    <div class="px-6 py-5">
                        <h3 class="text-lg font-semibold text-gray-900">Used BoQ Item Details</h3>
                    </div>

                    @if ($tool->request && $tool->request->requestDetails->isNotEmpty())
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gradient-to-r from-red-50 to-blue-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-red-700 uppercase tracking-wider">No.</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-red-700 uppercase tracking-wider">BoQ Item</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-red-700 uppercase tracking-wider">Quantity</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-red-700 uppercase tracking-wider">Unit Price</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-bold text-red-700 uppercase tracking-wider">Total Expenditure</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    {{-- Kalkulasi dipindah ke atas --}}
                                    @foreach ($tool->request->requestDetails as $rd)
                                        @if ($rd->detail)
                                            @php
                                                $subtotal = $rd->requested_quantity * $rd->detail->harga_satuan;
                                            @endphp
                                            <tr class="hover:bg-red-50 transition-colors duration-150">
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-center">{{ $loop->iteration }}</td>
                                                <td class="px-6 py-4 text-sm">
                                                    <div class="font-semibold text-gray-900">{{ $rd->detail->nama_detail }}</div>
                                                    <div class="text-xs text-gray-500 mt-1">
                                                        Section: {{ $rd->detail->section->nama?? 'N/A' }}
                                                    </div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">{{ $rd->requested_quantity }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600 text-right">Rp {{ number_format($rd->detail->harga_satuan, 0, ',', '.') }}</td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium text-right">Rp {{ number_format($subtotal, 0, ',', '.') }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="bg-gray-50">
                                        <td colspan="4" class="px-6 py-4 text-right text-sm font-bold text-gray-900">
                                            GRAND TOTAL
                                        </td>
                                        <td class="px-6 py-4 text-right text-sm font-bold text-red-700">
                                            Rp {{ number_format($grandTotalBoq, 0, ',', '.') }}
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    @else
                        {{-- Tampilan Kosong (Empty State) --}}
                        <div class="text-center py-12 px-6">
                            <svg class="mx-auto h-16 w-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <p class="text-xl text-gray-500 mt-4">No BoQ item details found for this request.</p>
                            <p class="text-sm text-gray-400 mt-1">There are no line items associated with this expenditure.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>

