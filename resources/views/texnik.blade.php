<div id="modal"
    class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden flex items-center justify-center"
    style="z-index: 9999;">
    <div class="relative p-8 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <i class="fas fa-exclamation-circle text-5xl text-yellow-500 mb-4"></i>
            <h3 class="text-2xl leading-6 font-bold text-gray-900 mb-4">Muhim xabar</h3>
            <div class="mt-2 px-7 py-3">
                <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50" role="alert">
                    <span class="font-medium">16.09.2024</span> Hurmatli foydalanuvchilar, saytimizda O'qituvchi,
                    Kafedra va Fakultet ballarini shakllantirish tizimi yangilandi. Hozirda barcha ma'lumotlar uchun
                    ballar shakllantirilmoqda. Agar tizimda biror xatolik yoki noaniqlik sezsangiz, iltimos, bizga xabar
                    bering. Sizning fikr-mulohazalaringiz bizga tizimni yanada takomillashtirish imkonini beradi.
                </div>
                <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50" role="alert">
                    <span class="font-medium">17.09.2024</span> Hurmatli foydalanuvchilar, saytimizda yangi yo'nalishlar
                    qo'shildi! table_1_3_1_a_, table_1_3_1_b_ va table_1_3_2_a_, table_1_3_2_b_ lar uchun ma'lumotlarni
                    qayta jo'natishingiz mumkin.
                </div>
                <div class="p-4 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50" role="alert">
                    <span class="font-medium">28.09.2024</span> Hurmatli foydalanuvchilar, saytimizda kafedralar ro'yxatida yangilanishlar bajarildi, o'qituvchilar ro'yxatdan o'tishlari mumkin.
                </div>
                <div class="text-right">
                    <a href="https://t.me/Raxmatilla_Fayziyev" target="_blank" rel="noopener noreferrer"
                    class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-5 py-2.5 me-2 mb-2 dark:bg-blue-600 dark:hover:bg-blue-700 focus:outline-none dark:focus:ring-blue-800">
                    Xatolik topilsa!
                </a>
                </div>


            </div>
            <div class="items-center px-4 py-3">
                <button id="closeModal"
                    class="px-6 py-3 bg-blue-500 text-white text-sm font-medium rounded-md w-full shadow-sm hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-300 transition duration-300">
                    Tushundim
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
