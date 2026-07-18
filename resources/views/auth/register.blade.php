<x-guest-layout>

<div class="min-h-screen flex items-center justify-center
            px-6 py-10 bg-[#f8f9fa]
            relative overflow-hidden">

    {{-- Background decoration --}}
    <div class="absolute inset-0 opacity-30 pointer-events-none">

        <div class="absolute -top-40 -left-40
                    w-[500px] h-[500px]
                    bg-[#1a4a40] blur-3xl rounded-full">
        </div>

        <div class="absolute top-0 right-0
                    w-[450px] h-[450px]
                    bg-[#d4a373] blur-3xl rounded-full">
        </div>

    </div>

    {{-- Registration card --}}
    <div class="relative w-full max-w-3xl
                bg-white/85 backdrop-blur-xl
                border border-[#1a4a40]/10
                rounded-3xl shadow-2xl
                shadow-gray-300/70 p-8 md:p-10">

        {{-- Header --}}
        <div class="text-center mb-8">

            <div class="w-16 h-16 mx-auto mb-5
                        bg-[#1a4a40] rounded-2xl
                        flex items-center justify-center
                        shadow-lg shadow-[#1a4a40]/20">

                <span class="text-white text-xl font-bold">
                    SA
                </span>
            </div>

            <h1 class="text-3xl font-bold text-[#1a4a40]">
                Create Student Account
            </h1>

            <p class="text-gray-500 text-sm mt-2">
                Enter your university information to join Smart Attendance
            </p>

        </div>

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">

                {{-- Name --}}
                <div>
                    <label class="text-[#1a4a40] text-sm block mb-2">
                        Full Name
                    </label>

                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        class="w-full px-4 py-3 rounded-xl
                               bg-white border border-[#1a4a40]/20
                               focus:outline-none focus:ring-2
                               focus:ring-[#1a4a40]/30"
                        placeholder="Full Name">

                    @error('name')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- University number --}}
                <div>
                    <label class="text-[#1a4a40] text-sm block mb-2">
                        University Number
                    </label>

                    <input
                        type="text"
                        name="university_number"
                        value="{{ old('university_number') }}"
                        required
                        class="w-full px-4 py-3 rounded-xl
                               bg-white border border-[#1a4a40]/20
                               focus:outline-none focus:ring-2
                               focus:ring-[#1a4a40]/30"
                        placeholder="20260001">

                    @error('university_number')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="text-[#1a4a40] text-sm block mb-2">
                        Email Address
                    </label>

                    <input
                        type="email"
                        name="email"
                        value="{{ old('email') }}"
                        required
                        class="w-full px-4 py-3 rounded-xl
                               bg-white border border-[#1a4a40]/20
                               focus:outline-none focus:ring-2
                               focus:ring-[#1a4a40]/30"
                        placeholder="student@university.edu">

                    @error('email')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Phone --}}
                <div>
                    <label class="text-[#1a4a40] text-sm block mb-2">
                        Phone Number
                    </label>

                    <input
                        type="text"
                        name="phone"
                        value="{{ old('phone') }}"
                        class="w-full px-4 py-3 rounded-xl
                               bg-white border border-[#1a4a40]/20
                               focus:outline-none focus:ring-2
                               focus:ring-[#1a4a40]/30"
                        placeholder="09XXXXXXXX">

                    @error('phone')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Academic year --}}
                <div>
                    <label class="text-[#1a4a40] text-sm block mb-2">
                        Academic Year
                    </label>

                    <select
                        name="academic_year"
                        required
                        class="w-full px-4 py-3 rounded-xl
                               bg-white border border-[#1a4a40]/20
                               focus:outline-none focus:ring-2
                               focus:ring-[#1a4a40]/30">

                        <option value="">
                            Select Academic Year
                        </option>

                        <option value="1"
                            @selected(old('academic_year') == 1)>
                            First Year
                        </option>

                        <option value="2"
                            @selected(old('academic_year') == 2)>
                            Second Year
                        </option>

                        <option value="3"
                            @selected(old('academic_year') == 3)>
                            Third Year
                        </option>

                        <option value="4"
                            @selected(old('academic_year') == 4)>
                            Fourth Year
                        </option>

                        <option value="5"
                            @selected(old('academic_year') == 5)>
                            Fifth Year
                        </option>

                    </select>

                    @error('academic_year')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Address --}}
                <div>
                    <label class="text-[#1a4a40] text-sm block mb-2">
                        Address
                    </label>

                    <input
                        type="text"
                        name="address"
                        value="{{ old('address') }}"
                        class="w-full px-4 py-3 rounded-xl
                               bg-white border border-[#1a4a40]/20
                               focus:outline-none focus:ring-2
                               focus:ring-[#1a4a40]/30"
                        placeholder="Enter your address">

                    @error('address')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label class="text-[#1a4a40] text-sm block mb-2">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        required
                        autocomplete="new-password"
                        class="w-full px-4 py-3 rounded-xl
                               bg-white border border-[#1a4a40]/20
                               focus:outline-none focus:ring-2
                               focus:ring-[#1a4a40]/30"
                        placeholder="Minimum 8 characters">

                    @error('password')
                        <p class="text-red-500 text-xs mt-1">
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                {{-- Password confirmation --}}
                <div>
                    <label class="text-[#1a4a40] text-sm block mb-2">
                        Confirm Password
                    </label>

                    <input
                        type="password"
                        name="password_confirmation"
                        required
                        autocomplete="new-password"
                        class="w-full px-4 py-3 rounded-xl
                               bg-white border border-[#1a4a40]/20
                               focus:outline-none focus:ring-2
                               focus:ring-[#1a4a40]/30"
                        placeholder="Repeat your password">
                </div>

            </div>

            {{-- Dormitory --}}
            <label class="flex items-center gap-3 mt-6
                          p-4 rounded-2xl
                          bg-[#1a4a40]/5
                          border border-[#1a4a40]/10
                          cursor-pointer">

                <input
                    type="checkbox"
                    name="is_dormitory"
                    value="1"
                    @checked(old('is_dormitory'))
                    class="w-5 h-5 rounded
                           text-[#1a4a40]
                           focus:ring-[#1a4a40]">

                <span>
                    <span class="block text-sm font-medium text-[#1a4a40]">
                        University Dormitory Student
                    </span>

                    <span class="block text-xs text-gray-500 mt-1">
                        Enable this option if you live in university housing.
                    </span>
                </span>

            </label>

            {{-- Submit --}}
            <button
                type="submit"
                class="w-full mt-7 py-3.5 rounded-xl
                       font-semibold text-white
                       bg-[#1a4a40]
                       hover:bg-[#153d35]
                       transition shadow-lg
                       shadow-[#1a4a40]/20">

                Create Student Account
            </button>

            <div class="text-center mt-6">

                <a
                    href="{{ route('login') }}"
                    class="text-[#1a4a40]
                           hover:text-[#153d35]
                           font-medium text-sm">

                    Already have an account? Login
                </a>

            </div>

        </form>

    </div>

</div>

</x-guest-layout>