<div id="modal"
    class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden flex items-center justify-center"
    style="z-index: 9999;">
    <div class="relative p-8 border w-11/12 max-w-2xl shadow-lg rounded-md bg-white">
        <div class="mt-3 text-center">
            <i class="fas fa-exclamation-circle text-5xl text-yellow-500 mb-4"></i>
            <h3 class="text-2xl leading-6 font-bold text-gray-900 mb-4">Muhim xabar</h3>
            <div class="mt-2 px-7 py-3">

                <div class="p-4 mb-4 text-left text-sm text-blue-800 rounded-lg bg-blue-50" role="alert">
                    <p class="font-medium mb-2">2.10.2024</p>
                    <p class="mb-2">Hurmatli foydalanuvchilar, saytimizda yangilanishlar amalga oshirildi:</p>
                    <ul class="list-disc pl-5 space-y-2">
                        <li>Kafedra ballarini hisoblashda o'zgarish kiritildi. Endi kafedraga o'tkaziladigan ballar 0,10 balldan yuqori qo'yilmaydi va ilgari kiritilgan barcha ballar 0,10 ballga o'zgartirildi.</li>
                        <li>Saytda o'z suratingizni o'zgartirmoqchi bo'lsangiz <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:underline">profil</a> sahifasiga o'tib o'zgartirish imkoniyati qo'shildi.</li>
                        <li>Kafedrangizni va oldindan yuklagan ma'lumotlaringiz hamda unga qo'yilgan ballarni boshqa kafedraga o'tkazish imkoniyati yaratildi. Buning uchun <a href="{{ route('profile.edit') }}" class="text-blue-600 hover:underline">profil</a> sahifasiga kirib, kerakli kafedrani tanlang.</li>
                        <li>Kafedradagi o'qituvchilar soni yangilandi. So'nggi yangilanishlar 27.09.2024 sanasiga ko'ra ro'yxat bo'yicha sonlar yangilangan.</li>
                        <li>"Men yuborgan ma'lumotlar" sahifasida ballarni to'g'ri ko'rsatish funksiyasi tuzatildi. Bundan tashqari, saytning boshqa kichik stillari va diagramma (chart) qismi ham takomillashtirildi.</li>
                    </ul>
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
