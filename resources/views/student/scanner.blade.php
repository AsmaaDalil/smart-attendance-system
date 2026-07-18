<x-student-layout>
        <div class="min-h-screen bg-[#f8f9fa]">

        <!-- Navbar -->
        <div class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">

                <div>
                    <h1 class="text-2xl font-bold text-[#1a4a40]">
                        Smart Attendance
                    </h1>
                    <p class="text-sm text-gray-500">
                        QR Attendance System
                    </p>
                </div>

            <div class="flex items-center gap-4">

    <div class="text-right">
        <h4 class="font-semibold text-[#1a4a40]">
            {{ auth()->user()->name }}
        </h4>

        <p class="text-sm text-gray-500">
            Student
        </p>
    </div>

    <div class="w-12 h-12 rounded-full bg-[#1a4a40]
                flex items-center justify-center text-white font-bold">

        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
    </div>

    <form method="POST" action="{{ route('logout') }}">
        @csrf

        <button type="submit"
                class="px-4 py-2 rounded-xl
                       border border-red-200
                       bg-red-50 text-red-600
                       hover:bg-red-500 hover:text-white
                       transition duration-300">

            Logout
        </button>
    </form>

</div>
            </div>  
        </div>
            

        <div class="max-w-7xl mx-auto p-6">

            <!-- Welcome -->
            <div class="bg-gradient-to-r from-[#1a4a40] to-[#24584d] rounded-3xl p-8 text-white shadow-lg mb-6">
                <h2 class="text-3xl font-bold">Welcome Back 👋</h2>
                <p class="mt-2 text-white/80">
                    Scan the QR code displayed by your instructor to record attendance.
                </p>
            </div>

            <!-- Current Lecture -->
            <div class="bg-white rounded-3xl shadow-md p-6 mb-6">
                <h3 class="text-xl font-bold text-[#1a4a40] mb-5">Current Lecture</h3>

                <div class="grid md:grid-cols-3 gap-6">
                    <div>
                        <p class="text-gray-500 text-sm">Subject</p>
                        <p class="font-semibold text-lg">Data Security</p>
                    </div>

                    <div>
                        <p class="text-gray-500 text-sm">Lecture Number</p>
                        <p class="font-semibold text-lg">Lecture 3</p>
                    </div>

                    <div>
                        <p class="text-gray-500 text-sm">Topic</p>
                        <p class="font-semibold text-lg">DES Encryption</p>
                    </div>
                </div>
            </div>

            <!-- QR Scanner -->
            <div class="bg-white rounded-3xl shadow-md p-8 mb-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-2xl font-bold text-[#1a4a40]">
                        Attendance Scanner
                    </h3>

                    <span class="bg-green-100 text-green-700 px-4 py-2 rounded-full">
                        Session Active
                    </span>
                </div>

                <button class="w-full h-56 rounded-3xl bg-[#1a4a40] hover:bg-[#153d35] text-white text-3xl font-bold transition duration-300">
                    📷 Scan QR Code
                </button>
            </div>

            <!-- Stats -->
            <div class="grid md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white rounded-3xl shadow-md p-6">
                    <p class="text-gray-500">Attendance Rate</p>
                    <h3 class="text-5xl font-bold text-[#1a4a40] mt-3">90%</h3>
                </div>

                <div class="bg-white rounded-3xl shadow-md p-6">
                    <p class="text-gray-500">Absences</p>
                    <h3 class="text-5xl font-bold text-red-500 mt-3">3</h3>
                </div>

                <div class="bg-white rounded-3xl shadow-md p-6">
                    <p class="text-gray-500">Excused</p>
                    <h3 class="text-5xl font-bold text-[#d4a373] mt-3">1</h3>
                </div>
            </div>

            <!-- Warning -->
            <div class="bg-red-50 border border-red-200 rounded-3xl p-6 mb-6">
                <h3 class="font-bold text-red-700 mb-2">Attendance Warning</h3>
                <p class="text-red-600">
                    Your absence rate will be monitored automatically. Students exceeding the allowed percentage may receive an academic warning.
                </p>
            </div>

            <!-- Recent -->
            <div class="bg-white rounded-3xl shadow-md overflow-hidden mb-6">
                <div class="p-6 border-b">
                    <h3 class="text-xl font-bold text-[#1a4a40]">
                        Recent Attendance
                    </h3>
                </div>

                <div class="p-6 space-y-4">
                    <div class="flex justify-between items-center">
                        <span>Data Security</span>
                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700">Present</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span>Networks</span>
                        <span class="px-3 py-1 rounded-full bg-red-100 text-red-700">Absent</span>
                    </div>

                    <div class="flex justify-between items-center">
                        <span>Web Development</span>
                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700">Present</span>
                    </div>
                </div>
            </div>

            <!-- Excuse -->
            <div class="bg-white rounded-3xl shadow-md p-6">
                <h3 class="text-xl font-bold text-[#1a4a40] mb-5">
                    Submit Excuse
                </h3>

                <form>
                    <textarea
                        rows="4"
                        class="w-full border border-gray-200 rounded-2xl p-4 focus:outline-none focus:ring-2 focus:ring-[#1a4a40]/30"
                        placeholder="Write your excuse here..."></textarea>

                    <input type="file" class="mt-4 block w-full">

                    <button type="submit"
                        class="mt-5 bg-[#1a4a40] hover:bg-[#153d35] text-white px-6 py-3 rounded-2xl transition">
                        Submit Excuse
                    </button>
                </form>
            </div>

        </div>
    </div>
</x-student-layout>