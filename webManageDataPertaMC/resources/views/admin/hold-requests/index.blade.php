<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center">
            <div>
                <h2 class="font-semibold text-lg sm:text-xl text-gray-800 leading-tight">
                    🔒 Hold Requests
                </h2>
                <p class="text-sm text-gray-600 mt-1">Request Tools that require admin approval</p>
            </div>
            <div class="flex items-center space-x-2 mt-2 sm:mt-0">
                <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">
                    {{ $holdTools->count() }} Tools Hold
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-4 sm:py-6">
        <div class="w-full px-3 sm:px-6 lg:px-8">


    @if($holdTools->count() > 0)
        <div class="space-y-4">
            @foreach($holdTools as $tool)
                <div class="bg-white rounded-lg shadow-md border border-yellow-200 p-6">
                    <!-- Header Info -->
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-1">
                            {{ $tool->Description }}
                            </h3>
                            <div class="text-sm text-gray-600">
                                📋 Project: <span class="font-medium">{{ $tool->project->title_project ?? 'N/A' }}</span>
                            </div>
                            <div class="text-sm text-gray-600">
                                📄 Request: <span class="font-medium">{{ $tool->request->no_surat ?? 'No Request' }}</span>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-semibold">
                                🔒 {{ $tool->getStatusToolsDisplay() }}
                            </span>
                            <div class="text-xs text-gray-500 mt-1">
                                {{ $tool->created_at->format('d/m/Y H:i') }}
                            </div>
                        </div>
                    </div>

                    <!-- Quantity Details -->
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <h4 class="font-medium text-yellow-800 mb-2">⚠️ Quantity Exceed Stok BOQ</h4>
                        <div class="grid grid-cols-3 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600">Quantity Request:</span>
                                <div class="font-semibold text-red-600">
                                    {{ number_format($tool->quantity) }} {{ $tool->unit }}
                                </div>
                            </div>
                            <div>
                                <span class="text-gray-600">Stok Available:</span>
                                <div class="font-semibold text-green-600">
                                    @php
                                        $availableQuantity = 0;
                                        if($tool->request && $tool->request->requestDetails) {
                                            $availableQuantity = $tool->request->requestDetails->sum(function($detail) {
                                                return $detail->detail->getAvailableQuantity();
                                            });
                                        }
                                    @endphp
                                    {{ number_format($availableQuantity) }} {{ $tool->unit }}
                                </div>
                            </div>
                            <div>
                                <span class="text-gray-600">Difference:</span>
                                <div class="font-semibold text-red-600">
                                    +{{ number_format($tool->quantity - $availableQuantity) }} {{ $tool->unit }}
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- BOQ Items Details -->
                    @if($tool->request && $tool->request->requestDetails->count() > 0)
                        <div class="mb-4">
                            <h4 class="font-medium text-gray-800 mb-2">Related BOQ Items:</h4>
                            <div class="space-y-2">
                                @foreach($tool->request->requestDetails as $detail)
                                    <div class="flex justify-between items-center p-2 bg-gray-50 rounded">
                                        <span class="text-sm text-gray-900">{{ $detail->detail->nama_detail }}</span>
                                        <span class="text-xs text-gray-600">
                                            {{ number_format($detail->requested_quantity) }} {{ $detail->detail->unit }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <!-- Action Buttons -->
                    <div class="flex justify-end space-x-3 pt-4 border-t border-gray-200">
                        <form method="POST" action="{{ route('hold-requests.update', $tool->idTools) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="action" value="reject">
                            <button type="button" 
                                    onclick="showRejectModal({{ $tool->idTools }})"
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                Reject Request
                            </button>
                        </form>
                        
                        <form method="POST" action="{{ route('hold-requests.update', $tool->idTools) }}" class="inline">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="action" value="approve">
                            <button type="button"
                                    onclick="showApproveModal({{ $tool->idTools }})"
                                    class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors">
                                Approve Request
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <!-- Empty State -->
        <div class="bg-white rounded-lg shadow-md p-12 text-center">
            <div class="text-6xl mb-4">🎉</div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">No Tools on Hold</h3>
            <p class="text-gray-600">All tool requests have been processed successfully!</p>
        </div>
    @endif
        </div>
    </div>

<!-- Approve Modal -->
<div id="approveModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center mb-4">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                    <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-medium text-gray-900 text-center mb-4">Approve Request</h3>
            <form id="approveForm" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="action" value="approve">
                <div class="mb-4">
                    <label for="approve_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Approval Note (opsional)
                    </label>
                    <textarea name="notes" id="approve_notes" rows="3" 
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-green-500 focus:border-green-500"
                              placeholder="Provide notes for this approval ..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeApproveModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                        Approve
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Modal -->
<div id="rejectModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
    <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex items-center mb-4">
                <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </div>
            </div>
            <h3 class="text-lg font-medium text-gray-900 text-center mb-4">Reject Request</h3>
            <form id="rejectForm" method="POST">
                @csrf
                @method('PATCH')
                <input type="hidden" name="action" value="reject">
                <div class="mb-4">
                    <label for="reject_notes" class="block text-sm font-medium text-gray-700 mb-2">
                        Reasons of Rejection <span class="text-red-500">*</span>
                    </label>
                    <textarea name="notes" id="reject_notes" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-red-500 focus:border-red-500"
                              placeholder="Explain the reasons for rejecting this request..."></textarea>
                </div>
                <div class="flex justify-end space-x-3">
                    <button type="button" onclick="closeRejectModal()" 
                            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-md hover:bg-gray-400">
                        Cancel
                    </button>
                    <button type="submit" 
                            class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                        Reject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function showApproveModal(toolId) {
    const modal = document.getElementById('approveModal');
    const form = document.getElementById('approveForm');
    form.action = `/hold-requests/${toolId}`;
    modal.classList.remove('hidden');
}

function closeApproveModal() {
    const modal = document.getElementById('approveModal');
    modal.classList.add('hidden');
    document.getElementById('approve_notes').value = '';
}

function showRejectModal(toolId) {
    const modal = document.getElementById('rejectModal');
    const form = document.getElementById('rejectForm');
    form.action = `/hold-requests/${toolId}`;
    modal.classList.remove('hidden');
}

function closeRejectModal() {
    const modal = document.getElementById('rejectModal');
    modal.classList.add('hidden');
    document.getElementById('reject_notes').value = '';
}

// Close modals when clicking outside
window.onclick = function(event) {
    const approveModal = document.getElementById('approveModal');
    const rejectModal = document.getElementById('rejectModal');
    
    if (event.target === approveModal) {
        closeApproveModal();
    }
    if (event.target === rejectModal) {
        closeRejectModal();
    }
}
</script>
</x-app-layout>