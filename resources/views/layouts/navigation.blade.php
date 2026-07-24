<!-- Mobile Overlay Backdrop -->
<div x-show="sidebarOpen"
     x-cloak
     x-transition.opacity
     @click="sidebarOpen = false"
     class="fixed inset-0 bg-black/50 z-30 sm:hidden">
</div>

<!-- Sidebar -->
<aside
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
    class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-gray-200 transform transition-transform duration-200 ease-in-out sm:translate-x-0 sm:static sm:flex sm:flex-col sm:shrink-0"
>
    <div class="h-full flex flex-col overflow-y-auto">

        <!-- Logo + Close Button (mobile) -->
        <div class="flex items-center justify-between px-4 h-16 border-b border-gray-100 shrink-0">
            <a href="{{ route('dashboard') }}">
                <x-application-logo class="block h-9 w-auto fill-current text-gray-800" />
            </a>
            <button @click="sidebarOpen = false" class="sm:hidden text-gray-400 hover:text-gray-600">
                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Navigation Links -->
        <nav class="flex-1 px-3 py-4 space-y-1">

            @php
                $linkClass = fn ($active) => $active
                    ? 'bg-gray-100 text-gray-900'
                    : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900';
            @endphp

            <a href="{{ route('dashboard') }}"
               class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $linkClass(request()->routeIs('dashboard')) }}">
                {{ __('Dashboard') }}
            </a>

            @role('admin')
                <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Data Master</p>
                <a href="{{ route('faculties.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $linkClass(request()->routeIs('faculties.*')) }}">Fakultas</a>
                <a href="{{ route('study-programs.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $linkClass(request()->routeIs('study-programs.*')) }}">Program Studi</a>
                <a href="{{ route('courses.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $linkClass(request()->routeIs('courses.*')) }}">Mata Kuliah</a>
                <a href="{{ route('academic-years.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $linkClass(request()->routeIs('academic-years.*')) }}">Tahun Ajaran</a>

                <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Akun</p>
                <a href="{{ route('lecturers.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $linkClass(request()->routeIs('lecturers.*')) }}">Dosen</a>
                <a href="{{ route('students.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $linkClass(request()->routeIs('students.*')) }}">Mahasiswa</a>

                <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Perkuliahan</p>
                <a href="{{ route('classes.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $linkClass(request()->routeIs('classes.*')) }}">Kelas</a>
            @endrole

            @role('dosen')
                <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Perkuliahan</p>
                <a href="{{ route('lecturer-classes.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $linkClass(request()->routeIs('lecturer-classes.*')) }}">Kelas Saya</a>
            @endrole

            @role('mahasiswa')
                <p class="px-3 pt-4 pb-1 text-xs font-semibold text-gray-400 uppercase tracking-wider">Perkuliahan</p>
                <a href="{{ route('enrollments.available') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $linkClass(request()->routeIs('enrollments.available')) }}">Kelas Tersedia</a>
                <a href="{{ route('enrollments.my') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $linkClass(request()->routeIs('enrollments.my')) }}">KRS Saya</a>
                <a href="{{ route('student-grades.index') }}" class="flex items-center px-3 py-2 text-sm font-medium rounded-md {{ $linkClass(request()->routeIs('student-grades.index')) }}">Nilai Saya</a>
            @endrole

        </nav>

        <!-- User Info + Profile/Logout -->
<!-- User Info + Profile/Logout -->
        <div class="border-t border-gray-200 p-3 shrink-0 relative"
             x-data="{ userMenuOpen: false }"
             @click.outside="userMenuOpen = false">

            <button @click="userMenuOpen = ! userMenuOpen"
                    class="w-full flex items-center justify-between px-3 py-2 text-sm font-medium rounded-md text-gray-600 hover:bg-gray-50 hover:text-gray-900">
                <span class="truncate">{{ Auth::user()->name }}</span>
                <svg class="fill-current h-4 w-4 shrink-0 transition-transform"
                     :class="userMenuOpen ? 'rotate-180' : ''"
                     xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                </svg>
            </button>

            <div x-show="userMenuOpen"
                 x-cloak
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="transform opacity-0 scale-95 translate-y-1"
                 x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-100"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute bottom-full left-3 right-3 mb-2 rounded-md shadow-lg ring-1 ring-black ring-opacity-5 bg-white overflow-hidden">

                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                    {{ __('Profile') }}
                </a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <a href="{{ route('logout') }}"
                       onclick="event.preventDefault(); this.closest('form').submit();"
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 cursor-pointer">
                        {{ __('Log Out') }}
                    </a>
                </form>
            </div>
        </div>
    </div>
</aside>