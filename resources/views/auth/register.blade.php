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
            <div class="w-16 h-16 mx-auto mb-5
            bg-[#1a4a40]
            rounded-2xl flex items-center justify-center
            shadow-lg shadow-[#1a4a40]/20">
                <span class="text-white text-xl font-bold">SA</span>
            </div>

            <h1 class="text-3xl font-bold text-[#1a4a40]">
                Create Account
            </h1>
            <p class="text-gray-600 text-sm mt-2">
                Join Smart Attendance today
            </p>
        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="mb-5">
                <label class="text-[#1a4a40] text-sm block mb-2">Name</label>
                <input type="text" name="name" required
                    class="w-full px-4 py-3 rounded-xl bg-white border border-[#1a4a40]/20 text-[#1a4a40] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1a4a40]/30 transition"
                    placeholder="Full Name">
            </div>

            <div class="mb-5">
                <label class="text-[#1a4a40] text-sm block mb-2">Email</label>
                <input type="email" name="email" required
                    class="w-full px-4 py-3 rounded-xl bg-white border border-[#1a4a40]/20 text-[#1a4a40] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1a4a40]/30 transition"
                    placeholder="student@university.edu">
            </div>

            <div class="mb-5">
                <label class="text-[#1a4a40] text-sm block mb-2">Password</label>
                <input type="password" name="password" required
                    class="w-full px-4 py-3 rounded-xl bg-white border border-[#1a4a40]/20 text-[#1a4a40] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1a4a40]/30 transition"
                    placeholder="••••••••">
            </div>

            <div class="mb-6">
                <label class="text-[#1a4a40] text-sm block mb-2">Confirm Password</label>
                <input type="password" name="password_confirmation" required
                    class="w-full px-4 py-3 rounded-xl bg-white border border-[#1a4a40]/20 text-[#1a4a40] placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1a4a40]/30 transition"
                    placeholder="••••••••">
            </div>

            <button type="submit"
                class="w-full py-3 rounded-xl font-semibold text-white
                bg-[#1a4a40] hover:bg-[#153d35] transition
                shadow-lg shadow-[#1a4a40]/20">
                Register
            </button>

            <div class="text-center mt-6">
                <a href="{{ route('login') }}"
                   class="text-[#1a4a40] hover:text-[#153d35] font-medium text-sm transition">
                   Already have an account? Login
                </a>
            </div>

        </form>
    </div>
</div>

</x-guest-layout>