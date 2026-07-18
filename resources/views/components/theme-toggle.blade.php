<button
    type="button"
    class="smart-theme-toggle"
    onclick="window.toggleSmartTheme()"
    title="Change appearance"
    aria-label="Change appearance"
>
    {{-- Moon: يظهر في الوضع الفاتح --}}
    <svg
        data-theme-moon
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.8"
        stroke="currentColor"
        class="h-5 w-5"
        style="display: block;"
    >
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M21.75 15.75a9 9 0 01-11.5-11.5
               9 9 0 1011.5 11.5z"
        />
    </svg>

    {{-- Sun: تظهر في الوضع الداكن --}}
    <svg
        data-theme-sun
        xmlns="http://www.w3.org/2000/svg"
        fill="none"
        viewBox="0 0 24 24"
        stroke-width="1.8"
        stroke="currentColor"
        class="h-5 w-5"
        style="display: none;"
    >
        <path
            stroke-linecap="round"
            stroke-linejoin="round"
            d="M12 3v1.5m0 15V21m9-9h-1.5
               M4.5 12H3m15.364-6.364-1.061 1.061
               M6.697 17.303l-1.061 1.061
               m12.728 0-1.061-1.061
               M6.697 6.697 5.636 5.636
               M16.5 12a4.5 4.5 0 11-9 0
               4.5 4.5 0 019 0z"
        />
    </svg>
</button>

@once
    <script>
        /*
         * تطبيق اللون المختار على الصفحة.
         */
        window.applySmartTheme = function (theme) {
            const isDark = theme === 'dark';

            // تغيير لون الصفحة.
            document.documentElement.classList.toggle(
                'dark',
                isDark
            );

            // حفظ الاختيار لكل صفحات الموقع.
            localStorage.setItem(
                'theme',
                isDark ? 'dark' : 'light'
            );

            // إظهار القمر في الوضع الفاتح فقط.
            document
                .querySelectorAll('[data-theme-moon]')
                .forEach(function (icon) {
                    icon.style.display = isDark
                        ? 'none'
                        : 'block';
                });

            // إظهار الشمس في الوضع الداكن فقط.
            document
                .querySelectorAll('[data-theme-sun]')
                .forEach(function (icon) {
                    icon.style.display = isDark
                        ? 'block'
                        : 'none';
                });
        };

        /*
         * تبديل اللون عند الضغط على الزر.
         */
        window.toggleSmartTheme = function () {
            const isDark =
                document.documentElement.classList.contains('dark');

            window.applySmartTheme(
                isDark ? 'light' : 'dark'
            );
        };

        /*
         * قراءة اللون المحفوظ عند فتح الصفحة.
         */
        function initializeSmartTheme() {
            const savedTheme =
                localStorage.getItem('theme') || 'light';

            window.applySmartTheme(savedTheme);
        }

        if (document.readyState === 'loading') {
            document.addEventListener(
                'DOMContentLoaded',
                initializeSmartTheme
            );
        } else {
            initializeSmartTheme();
        }

        /*
         * إعادة تطبيق اللون بعد التنقل داخل Filament.
         */
        document.addEventListener(
            'livewire:navigated',
            initializeSmartTheme
        );
    </script>
@endonce