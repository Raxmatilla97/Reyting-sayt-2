<div id="modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden flex items-center justify-center" style="z-index: 9999;">
    <div class="relative p-8 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <i class="fas fa-exclamation-circle text-5xl text-yellow-500 mb-4"></i>
            <h3 class="text-2xl leading-6 font-bold text-gray-900 mb-4">Yangi o'zgarishlar haqida ma'lumot</h3>
            <div class="mt-2 px-7 py-3">
                <div class="p-4 mb-4 text-left text-sm text-blue-800 rounded-lg bg-blue-50" role="alert">
                    <p class="font-medium mb-2">26-dekabr, 2024-yil</p>
                    <p class="mb-2">Hurmatli OTM.CSPU.UZ foydalanuvchilari!</p>
                    <p class="leading-relaxed">
                        Saytimizda amalga oshirilayotgan barcha yangilanishlar, qo'shilayotgan yangi imkoniyatlar va tizimning ishida yuzaga kelishi mumkin bo'lgan texnik nosozliklar haqidagi dolzarb ma'lumotlarni tezkor ravishda olish uchun rasmiy Telegram kanalimizga a'zo bo'lishingizni tavsiya etamiz. Kanalimiz orqali siz tizimning ishlash jarayoni, yangi funksiyalar va muhim e'lonlar haqida birinchilardan xabardor bo'lasiz.
                    </p>
                    <p class="mt-4 italic text-blue-700">
                        Tizimdan yanada samarali foydalanish va so'nggi yangilanishlardan xabardor bo'lish uchun quyidagi Telegram kanalimizga ulaning!
                    </p>
                </div>

                <div class="text-right space-y-2">
                    <!-- Previous button -->
                    <a href="https://t.me/Raxmatilla_Fayziyev" target="_blank" rel="noopener noreferrer" class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                        Xatolik topsangiz bog'laning
                    </a>

                    <!-- New Telegram channel button -->
                    <a href="https://t.me/cspu_rttm_info_offical" target="_blank" rel="noopener noreferrer" class="inline-flex items-center text-white bg-[#0088cc] hover:bg-[#0077b5] focus:ring-4 focus:ring-[#0088cc]/50 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2">
                        <svg class="w-4 h-4 me-2" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M19.777 4.43a1.5 1.5 0 0 1 2.085 1.752l-2.652 12.584a1.5 1.5 0 0 1-2.303.965l-5.186-3.38-2.9 2.9a1 1 0 0 1-1.7-.708L7.27 14l-4.622-3.01a1.5 1.5 0 0 1 .407-2.676l16.722-3.885ZM7.672 13.264l3.827 2.492 5.64-5.64-7.777 4.547a1.5 1.5 0 0 0-.106.064l-1.584 4.644 1.584-4.644a1.5 1.5 0 0 0-1.584-1.463Z"/>
                        </svg>
                        Yangilanishlarni kuzatib boring
                    </a>
                </div>
            </div>

            <div class="items-center px-4 py-3">
                <button id="closeModal" class="px-6 py-3 bg-blue-500 text-white text-sm font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 transition duration-300">
                    Tushunarli
                </button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('modal');
        const closeButton = document.getElementById('closeModal');
        const MODAL_SHOWN_KEY = 'modalLastShown';
        const TWELVE_HOURS = 12 * 60 * 60 * 1000; // 12 soat millisekundlarda

        function showModal() {
            modal.classList.remove('hidden');
        }

        function closeModal() {
            modal.classList.add('hidden');
            localStorage.setItem(MODAL_SHOWN_KEY, Date.now().toString());
        }

        function shouldShowModal() {
            const lastShown = localStorage.getItem(MODAL_SHOWN_KEY);
            if (!lastShown) return true;

            const timeSinceLastShown = Date.now() - parseInt(lastShown);
            return timeSinceLastShown > TWELVE_HOURS;
        }

        if (shouldShowModal()) {
            showModal();
        }

        closeButton.addEventListener('click', closeModal);

        modal.addEventListener('click', function(event) {
            if (event.target === modal) {
                closeModal();
            }
        });
    });
</script>
