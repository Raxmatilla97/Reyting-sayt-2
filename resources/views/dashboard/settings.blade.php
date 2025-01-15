<x-app-layout>
    <x-slot name="header">
        <div class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-xl shadow-lg">
            <h2 class="py-6 text-2xl font-bold text-center text-white">
                {{ __("Sozlamlar sahifasi") }}
            </h2>
        </div>
    </x-slot>
 
    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h4 class="text-xl font-semibold mb-4">Tizim Sozlamalari</h4>
                    
                    <form id="settingsForm">
                        @csrf
                        <div class="space-y-4">
                            <label class="block">
                                <span class="text-gray-700">Ma'lumot kiritish holati:</span>
                                <select id="allow_data_entry" name="allow_data_entry" 
                                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <option value="1" {{ config('settings.allow_data_entry') ? 'selected' : '' }}>
                                        Ma'lumot kiritish mumkin
                                    </option>
                                    <option value="0" {{ !config('settings.allow_data_entry') ? 'selected' : '' }}>
                                        Ma'lumot kiritish mumkin emas
                                    </option>
                                </select>
                            </label>
 
                            <div class="mt-4">
                                <button type="button" id="saveSettings" 
                                    class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                    Saqlash
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
 
    <!-- Modal -->
    <div id="resultModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Natija</h3>
                <div class="mt-2 px-7 py-3">
                    <p id="resultMessage" class="text-sm text-gray-500"></p>
                </div>
                <div class="items-center px-4 py-3">
                    <button id="closeModal" 
                        class="px-4 py-2 bg-gray-500 text-white text-base font-medium rounded-md shadow-sm hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-gray-300">
                        Yopish
                    </button>
                </div>
            </div>
        </div>
    </div>


    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('settingsForm');
            const modal = document.getElementById('resultModal');
            const saveButton = document.getElementById('saveSettings');
            const allowDataEntry = document.getElementById('allow_data_entry');
    
            saveButton.addEventListener('click', async function(e) {
                e.preventDefault();
                console.log('Saqlash bosildi');
    
                try {
                    // Loading holatini yoqish
                    saveButton.disabled = true;
                    saveButton.innerHTML = 'Saqlanmoqda...';
    
                    const formData = new FormData(form);
                    console.log('FormData:', Object.fromEntries(formData));
    
                    const response = await fetch('{{ route("admin.settings.update") }}', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json',
                        },
                        body: formData
                    });
    
                    console.log('Response:', response);
    
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
    
                    const data = await response.json();
                    console.log('Data:', data);
    
                    // Muvaffaqiyatli natija
                    document.getElementById('resultMessage').textContent = data.message || 'Muvaffaqiyatli saqlandi';
                    modal.classList.remove('hidden');
    
                    // 2 sekunddan keyin modalni yopish
                    setTimeout(() => {
                        modal.classList.add('hidden');
                    }, 2000);
    
                } catch (error) {
                    console.error('Xatolik:', error);
                    document.getElementById('resultMessage').textContent = 'Xatolik yuz berdi: ' + error.message;
                    modal.classList.remove('hidden');
                } finally {
                    // Tugmani qayta yoqish
                    saveButton.disabled = false;
                    saveButton.innerHTML = 'Saqlash';
                }
            });
    
            // Modal yopish
            document.getElementById('closeModal')?.addEventListener('click', () => {
                modal.classList.add('hidden');
            });
        });
    </script>


 </x-app-layout>
