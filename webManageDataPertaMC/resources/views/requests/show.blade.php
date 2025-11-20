@extends('layouts.app')

@section('title', 'Detail Request - ' . $document->no_request)

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Detail Request</h1>
                    <p class="text-gray-600 mt-1">Document: <span class="font-semibold">{{ $document->no_request }}</span></p>
                </div>
                <div class="flex space-x-3">
                    <a href="{{ route('requests.show', $document->no_request) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                        </svg>
                        Back to Form
                    </a>
                </div>
            </div>
        </div>

        <!-- Request Information -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">information Request</h2>
            </div>
            
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Type Surat</label>
                        <div class="mt-1 text-sm text-gray-900">{{ $request->type_surat }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Type Request</label>
                        <div class="mt-1 text-sm text-gray-900">{{ $request->jenis_req }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">No Surat</label>
                        <div class="mt-1 text-sm text-gray-900">{{ $request->no_surat }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Date Request</label>
                        <div class="mt-1 text-sm text-gray-900">{{ $request->date_req->format('d/m/Y') }}</div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Status</label>
                        <div class="mt-1">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                bg-{{ $request->getStatusColor() }}-100 text-{{ $request->getStatusColor() }}-800">
                                {{ $request->getStatusDisplay() }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Request Details - BOQ Items -->
        @if($request->requestDetails && $request->requestDetails->count() > 0)
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="p-6 border-b border-gray-200">
                <h2 class="text-lg font-semibold text-gray-900">Requested BOQ Item Details</h2>
                <p class="text-gray-600 text-sm mt-1">{{ $request->requestDetails->count() }} items in this request</p>
            </div>
            
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Item BOQ
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Description Request
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Quantity
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Price/Unit
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Total Cost
                            </th>
                            <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @php $grandTotal = 0; @endphp
                        @foreach($request->requestDetails as $detail)
                            @php $grandTotal += $detail->total_price; @endphp
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $detail->detail->nama_detail }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Section: {{ $detail->detail->section->nama_section }}
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="text-sm text-gray-900 max-w-xs">
                                        {{ $detail->notes }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                                    {{ number_format($detail->requested_quantity, 2) }}
                                    <div class="text-xs text-gray-500">{{ $detail->detail->unit }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm text-gray-900">
                                    Rp {{ number_format($detail->unit_price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-gray-900">
                                    Rp {{ number_format($detail->total_price, 0, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                        {{ $detail->status === 'approved' ? 'bg-green-100 text-green-800' : 
                                           ($detail->status === 'rejected' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') }}">
                                        {{ ucfirst($detail->status) }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot class="bg-gray-50">
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-right text-sm font-medium text-gray-900">
                                Grand Total:
                            </td>
                            <td class="px-6 py-4 text-right text-lg font-bold text-gray-900">
                                Rp {{ number_format($grandTotal, 0, ',', '.') }}
                            </td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        @else
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 mb-6">
            <div class="p-6 text-center">
                <div class="text-gray-400 text-6xl mb-4">
                    <i class="fas fa-inbox"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-2">No Item Details</h3>
                <p class="text-gray-600">This request has no BOQ item details.</p>
            </div>
        </div>
        @endif

        <!-- Action Buttons -->
        <div class="flex justify-between items-center">
            <a href="{{ route('requests.show', $document->no_request) }}" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 17l-5-5m0 0l5-5m-5 5h12"></path>
                </svg>
                Edit Request
            </a>
            
            <div class="flex space-x-3">
                <button onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                    </svg>
                    Print
                </button>
                <button onclick="window.history.back()" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                    Back
                </button>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    .no-print { display: none !important; }
    body { background: white !important; }
    .bg-gray-50, .bg-gray-100 { background: white !important; }
    .shadow-sm, .shadow { box-shadow: none !important; }
    .border { border: 1px solid #ccc !important; }
}
</style>
@endsection