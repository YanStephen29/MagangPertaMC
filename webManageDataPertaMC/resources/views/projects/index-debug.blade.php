<!DOCTYPE html>
<html>
<head>
    <title>Projects Index - No Layout</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="container mx-auto py-8 px-4">
        <h1 class="text-2xl font-bold mb-6">Request Data Proyek dan Equipment</h1>
        
        <!-- Debug Info -->
        <div class="bg-yellow-50 border border-yellow-200 text-yellow-700 px-4 py-3 rounded-md mb-4">
            <strong>Debug Info:</strong> Total projects found: {{ $projects->count() }}
            @if($projects->count() > 0)
                <br>First project: {{ $projects->first()->no_IO ?? 'N/A' }} - {{ $projects->first()->title_project ?? 'N/A' }}
            @endif
        </div>

        @if($projects->count() > 0)
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 bg-white">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">No I/O</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Judul Project</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tanggal Dibuat</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @foreach($projects as $project)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $project->no_IO }}</td>
                                <td class="px-6 py-4 text-sm text-gray-900">{{ $project->title_project }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $project->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-12">
                <p class="text-xl text-gray-500 mb-6">Tidak ada project yang ditemukan</p>
                <a href="/projects/create" class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded">
                    Tambah Project Baru
                </a>
            </div>
        @endif
        
        <div class="mt-8">
            <h2 class="text-lg font-semibold mb-4">Raw Debug Data:</h2>
            <pre class="bg-gray-100 p-4 rounded text-xs overflow-auto">{{ json_encode($projects->toArray(), JSON_PRETTY_PRINT) }}</pre>
        </div>
    </div>
</body>
</html>
