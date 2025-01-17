<div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition-shadow">
    <div class="flex items-center gap-4">
        <div class="p-3 bg-green-100 rounded-full">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                </path>
            </svg>
        </div>
        <div class="flex items-center gap-2">
            <div>
                <p class="text-sm text-gray-500">
                    Fakultet reyting balli
                    <span class="text-[10px] text-red-500 align-top ml-1">{{ $faculty->custom_points === null ? 'AUTO' : 'REAL' }}</span>
                </p>
                <span class="bg-green-100 text-green-800 text-lg font-medium px-2.5 py-0.5 rounded">
                    {{ $faculty->custom_points ?? $totalPoints }} ball
                </span>
            </div>
            @if(Auth::user()->is_admin)
                <button
                    onclick="openCustomPointsModal({{ $faculty->id }})"
                    class="p-1.5 bg-blue-100 text-blue-600 rounded-full hover:bg-blue-200 transition-colors ml-4"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </button>
            @endif
        </div>
    </div>
 </div>

 @if(Auth::user()->is_admin)
    <div id="customPointsModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 w-96">
            <h3 class="text-lg font-medium mb-4">Fakultet reyting balini belgilash</h3>

            <input type="hidden" id="facultyId">
            <input
                type="number"
                id="customPoints"
                class="w-full border rounded p-2 mb-4"
                placeholder="Ball miqdorini kiriting"
            >

            <div class="flex justify-end gap-2">
                <button
                    onclick="closeCustomPointsModal()"
                    class="px-4 py-2 text-gray-600 hover:bg-gray-100 rounded"
                >
                    Bekor qilish
                </button>
                <button
                    onclick="saveCustomPoints()"
                    class="px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700"
                >
                    Saqlash
                </button>
            </div>
        </div>
    </div>

    <script>
    function openCustomPointsModal(facultyId) {
        document.getElementById('customPointsModal').classList.remove('hidden');
        document.getElementById('customPointsModal').classList.add('flex');
        document.getElementById('facultyId').value = facultyId;
    }

    function closeCustomPointsModal() {
        document.getElementById('customPointsModal').classList.add('hidden');
        document.getElementById('customPointsModal').classList.remove('flex');
    }

    function saveCustomPoints() {
        const facultyId = document.getElementById('facultyId').value;
        const points = document.getElementById('customPoints').value;

        fetch('/admin/faculties/custom-points', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                faculty_id: facultyId,
                points: points
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Xatolik yuz berdi!');
        });
    }
    </script>
 @endif
