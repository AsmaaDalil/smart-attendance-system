<x-app-layout>
    <!-- لا نحتاج تكرار الـ Sidebar هنا، هو موجود في الـ Layout -->

    <!-- Main Content -->
    <main class="flex-1 p-8">

        <!-- Top Bar -->
        <div class="flex justify-between items-center mb-8">
            <div>
                <h2 class="text-3xl font-bold text-[#1a4a40]">Dashboard</h2>
                <p class="text-gray-500">Welcome back</p>
            </div>

            <!-- User Profile Dropdown -->
            <div class="relative group">
                <div class="flex items-center gap-4 cursor-pointer">
                    <div class="text-right">
                        <!-- تم وضع اسم المستخدم الحقيقي هنا -->
                        <h4 class="font-semibold text-[#1a4a40]">{{ Auth::user()->name }}</h4>
                        <p class="text-sm text-gray-500">Administrator</p>
                    </div>
                    <div class="w-12 h-12 rounded-full bg-[#1a4a40] flex items-center justify-center text-white font-bold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                </div>

                <!-- Dropdown -->
                <div class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-lg border border-gray-100 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all z-50">
                    <a href="{{ route('profile.edit') }}" class="block px-4 py-3 text-gray-700 hover:bg-gray-100 rounded-t-xl">Profile</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="block w-full text-left px-4 py-3 text-red-600 hover:bg-gray-100 rounded-b-xl">Logout</button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Hero Section -->
        <div class="bg-gradient-to-r from-[#1a4a40] to-[#24584d] rounded-3xl p-8 text-white mb-8 shadow-lg">
            <h2 class="text-3xl font-bold">Welcome Back</h2>
            <p class="mt-3 text-white/80">Monitor attendance, manage students and generate QR codes easily.</p>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-3xl p-6 shadow-md">
                <p class="text-gray-500">Students</p>
                <h3 class="text-4xl font-bold text-[#1a4a40] mt-3">125</h3>
            </div>
            <div class="bg-white rounded-3xl p-6 shadow-md">
                <p class="text-gray-500">Attendance Rate</p>
                <h3 class="text-4xl font-bold text-[#d4a373] mt-3">94%</h3>
            </div>
            <div class="bg-white rounded-3xl p-6 shadow-md">
                <p class="text-gray-500">Courses</p>
                <h3 class="text-4xl font-bold text-[#1a4a40] mt-3">18</h3>
            </div>
            <div class="bg-white rounded-3xl p-6 shadow-md">
                <p class="text-gray-500">QR Scans</p>
                <h3 class="text-4xl font-bold text-[#d4a373] mt-3">240</h3>
            </div>
        </div>

        <!-- Recent Attendance Table -->
        <div class="bg-white rounded-3xl shadow-md overflow-hidden">
            <div class="p-6 border-b">
                <h3 class="text-xl font-semibold text-[#1a4a40]">Recent Attendance</h3>
            </div>
            <table class="w-full">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="p-4 text-left text-gray-600">Student</th>
                        <th class="p-4 text-left text-gray-600">Course</th>
                        <th class="p-4 text-left text-gray-600">Date</th>
                        <th class="p-4 text-left text-gray-600">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="border-t">
                        <td class="p-4">Ahmad Ali</td>
                        <td class="p-4">Web Development</td>
                        <td class="p-4">2025-06-20</td>
                        <td class="p-4"><span class="px-3 py-1 rounded-full bg-green-100 text-green-700">Present</span></td>
                    </tr>
                    <tr class="border-t">
                        <td class="p-4">Sara Mohammad</td>
                        <td class="p-4">Database Systems</td>
                        <td class="p-4">2025-06-20</td>
                        <td class="p-4"><span class="px-3 py-1 rounded-full bg-red-100 text-red-700">Absent</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </main>
</x-app-layout>