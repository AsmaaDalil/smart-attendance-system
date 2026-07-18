<x-professor-layout>

    <main class="p-8">

        <div class="flex justify-between items-center mb-8">

            <div>
                <h1 class="text-3xl font-bold text-[#1a4a40]">
                    Professor Dashboard
                </h1>

                <p class="text-gray-500 mt-1">
                    Welcome back
                </p>
            </div>

            <div class="flex items-center gap-4">

                <div class="text-right">
                    <h4 class="font-semibold text-[#1a4a40]">
                        {{ auth()->user()->name }}
                    </h4>

                    <p class="text-sm text-gray-500">
                        Professor
                    </p>
                </div>

                <div class="w-12 h-12 rounded-full bg-[#1a4a40]
                            flex items-center justify-center
                            text-white font-bold">

                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <button type="submit"
                            class="text-sm text-red-500 hover:text-red-700">
                        Logout
                    </button>
                </form>

            </div>

        </div>

        <div class="bg-gradient-to-r from-[#1a4a40] to-[#24584d]
                    rounded-3xl p-8 text-white shadow-lg mb-8">

            <h2 class="text-3xl font-bold">
                Welcome Back
            </h2>

            <p class="mt-3 text-white/80">
                Start attendance sessions, display QR codes,
                and monitor your students.
            </p>

        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">

            <div class="bg-white rounded-3xl p-6 shadow-md">
                <p class="text-gray-500">My Subjects</p>

                <h3 class="text-4xl font-bold text-[#1a4a40] mt-3">
                    0
                </h3>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-md">
                <p class="text-gray-500">Active Sessions</p>

                <h3 class="text-4xl font-bold text-[#d4a373] mt-3">
                    0
                </h3>
            </div>

            <div class="bg-white rounded-3xl p-6 shadow-md">
                <p class="text-gray-500">Attendance Records</p>

                <h3 class="text-4xl font-bold text-[#1a4a40] mt-3">
                    0
                </h3>
            </div>

        </div>

    </main>

</x-professor-layout>