<x-guest-layout>

<div class="min-h-screen flex items-center justify-center p-6 
bg-[#f8f9fa] relative overflow-hidden">

    <div class="absolute inset-0 opacity-30">
        <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-[#1a4a40] blur-3xl rounded-full"></div>
        <div class="absolute top-0 right-0 w-[450px] h-[450px] bg-[#d4a373] blur-3xl rounded-full"></div>
    </div>

    <div class="relative w-full max-w-md
    bg-white/80 backdrop-blur-xl
    border border-[#1a4a40]/10
    rounded-3xl shadow-2xl shadow-gray-300
    p-10">

        <div class="text-center mb-10">

    <img
        src="{{ asset('images/logo.png') }}"
        alt="Smart Attendance System"
        class="w-72 max-w-full mx-auto mb-5"
    >

</div>

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-5">
                <label class="text-[#1a4a40] text-sm block mb-2">
                    Email
                </label>

                <input type="email" name="email" autocomplete="off"
                    class="w-full px-4 py-3 rounded-xl
                    bg-white border border-[#1a4a40]/20
                    text-[#1a4a40] placeholder-gray-400
                    focus:outline-none focus:ring-2 focus:ring-[#1a4a40]/30
                    transition"
                    placeholder="student@university.edu">
            </div>

            <div class="mb-5">
                <label class="text-[#1a4a40] text-sm block mb-2">
                    Password
                </label>

                <input type="password" name="password" autocomplete="off"
                    class="w-full px-4 py-3 rounded-xl
                    bg-white border border-[#1a4a40]/20
                    text-[#1a4a40] placeholder-gray-400
                    focus:outline-none focus:ring-2 focus:ring-[#1a4a40]/30
                    transition"
                    placeholder="••••••••">
            </div>

<div class="mb-6 flex items-center gap-x-3"> <!-- إضافة gap-x-3 للتحكم بالمسافة -->
    <input type="checkbox" name="remember" autocomplete="off"
        class="accent-[#1a4a40] w-4 h-4">

    <span class="text-gray-600 text-sm">
        Remember me
    </span>
</div>

            <button type="submit"
                class="w-full py-3 rounded-xl font-semibold text-white
                bg-[#1a4a40] hover:bg-[#153d35] transition
                shadow-lg shadow-[#1a4a40]/20">

                Login
            </button>


@if ($errors->any())
    <p class="mt-2 text-xs text-center text-red-600">
Invalid email or password. Please try again.
    </p>
@endif
@if (session('success'))
    <div class="p-4 mb-4 text-green-700 bg-green-100 rounded-lg">
        {{ session('success') }}
    </div>
@endif

        </form>

    </div>

</div>

</x-guest-layout>