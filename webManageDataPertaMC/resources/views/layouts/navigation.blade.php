<nav x-data="{ open: false }" class="bg-gradient-to-r from-red-600 via-red-600 to-red-700 shadow-xl fixed top-0 left-0 right-0 z-[99999] border-b border-red-800 overflow-visible backdrop-blur-sm transition-all duration-500 ease-out">
    <!-- Animated decorative diagonal lines dengan glow effect -->
    <div class="absolute top-0 right-0 h-full w-28 sm:w-36 overflow-hidden">
        <div class="absolute top-0 -right-2 sm:right-0 h-full w-12 sm:w-20 bg-gradient-to-br from-blue-500 to-blue-700 transform -skew-x-[30deg] origin-bottom transition-all duration-700 ease-in-out hover:scale-105 shadow-lg"></div>
        <div class="absolute top-0 -right-6 sm:-right-4 h-full w-8 sm:w-12 bg-gradient-to-br from-white to-gray-100 transform -skew-x-[30deg] origin-bottom transition-all duration-700 ease-in-out hover:scale-105 shadow-md"></div>
        <!-- Subtle glow effect -->
        <div class="absolute top-0 right-0 h-full w-full bg-gradient-to-l from-red-400/20 to-transparent pointer-events-none"></div>
    </div>
    
    <!-- Primary Navigation Menu -->
    <div class="w-full px-1 sm:px-2 lg:px-4 relative z-[99999]">
        <div class="flex justify-between items-center py-2">
            <!-- Logo dan Nama Perusahaan -->
            <div class="flex items-center flex-shrink-0 ml-2 sm:ml-4">
                <div class="flex-shrink-0">
                    <div class="w-10 h-10 sm:w-12 sm:h-12 bg-white rounded-full flex items-center justify-center shadow-xl border-2 border-red-200 p-1 sm:p-1.5 transition-all duration-500 ease-out hover:scale-110 hover:shadow-2xl hover:border-red-300 cursor-pointer transform hover:rotate-3">
                        @if(file_exists(public_path('image/pertamina_logo.jpg')))
                            <img src="{{ asset('image/pertamina_logo.jpg') }}" 
                                 alt="Pertamina Logo" 
                                 class="w-full h-full object-contain rounded-full">
                        @elseif(file_exists(public_path('image/pertamina_logo.png')))
                            <img src="{{ asset('image/pertamina_logo.png') }}" 
                                 alt="Pertamina Logo" 
                                 class="w-full h-full object-contain rounded-full">
                        @elseif(file_exists(public_path('images/pertamina_logo.svg')))
                            <img src="{{ asset('images/pertamina_logo.svg') }}" 
                                 alt="Pertamina Logo" 
                                 class="w-full h-full object-contain">
                        @else
                            <!-- Custom Pertamina-style logo fallback -->
                            <div class="w-full h-full rounded-full flex items-center justify-center bg-gradient-to-br from-red-600 via-red-600 to-red-700 relative overflow-hidden">
                                <!-- Gear/oil industry symbol -->
                                <svg class="w-5 h-5 sm:w-7 sm:h-7 text-white" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M12,15.5A3.5,3.5 0 0,1 8.5,12A3.5,3.5 0 0,1 12,8.5A3.5,3.5 0 0,1 15.5,12A3.5,3.5 0 0,1 12,15.5M19.43,12.97C19.47,12.65 19.5,12.33 19.5,12C19.5,11.67 19.47,11.34 19.43,11L21.54,9.37C21.73,9.22 21.78,8.95 21.66,8.73L19.66,5.27C19.54,5.05 19.27,4.96 19.05,5.05L16.56,6.05C16.04,5.66 15.5,5.32 14.87,5.07L14.5,2.42C14.46,2.18 14.25,2 14,2H10C9.75,2 9.54,2.18 9.5,2.42L9.13,5.07C8.5,5.32 7.96,5.66 7.44,6.05L4.95,5.05C4.73,4.96 4.46,5.05 4.34,5.27L2.34,8.73C2.22,8.95 2.27,9.22 2.46,9.37L4.57,11C4.53,11.34 4.5,11.67 4.5,12C4.5,12.33 4.53,12.65 4.57,12.97L2.46,14.63C2.27,14.78 2.22,15.05 2.34,15.27L4.34,18.73C4.46,18.95 4.73,19.03 4.95,18.95L7.44,17.94C7.96,18.34 8.5,18.68 9.13,18.93L9.5,21.58C9.54,21.82 9.75,22 10,22H14C14.25,22 14.46,21.82 14.5,21.58L14.87,18.93C15.5,18.68 16.04,18.34 16.56,17.94L19.05,18.95C19.27,19.03 19.54,18.95 19.66,18.73L21.66,15.27C21.78,15.05 21.73,14.78 21.54,14.63L19.43,12.97Z" />
                                </svg>
                                <!-- Letter P overlay -->
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-white font-black text-xs sm:text-sm bg-red-700/30 rounded-full w-4 h-4 sm:w-5 sm:h-5 flex items-center justify-center">P</span>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                <div class="ml-2 sm:ml-3 min-w-0 flex-1">
                    <h1 class="text-sm sm:text-lg lg:text-xl font-bold text-white drop-shadow-lg truncate transition-all duration-300 ease-out hover:drop-shadow-xl">Pertamina Maintenance dan Construction</h1>
                    <p class="text-red-100 text-xs hidden sm:block transition-all duration-300 ease-out hover:text-white">Sistem Data Management</p>
                </div>
            </div>

            <!-- Navigation Menu - Desktop -->
            <div class="hidden sm:flex items-center space-x-4 lg:space-x-6 mr-8 sm:mr-12">
                @canAccess('project_read')
                    <a href="{{ route('home') }}" 
                       class="text-white hover:text-red-100 px-2 sm:px-3 py-1 text-xs sm:text-sm font-semibold transition-all duration-500 ease-out border-b-2 {{ request()->routeIs('home', 'projects.*') ? 'border-white bg-red-700/80 shadow-lg scale-105' : 'border-transparent hover:border-red-200 hover:bg-red-700/30 hover:scale-105 hover:shadow-md' }} rounded-t-lg relative overflow-hidden group">
                        <span class="relative z-10">Home</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000 ease-out"></div>
                    </a>
                @else
                    <button class="@privilegeButton('project_read', 'mengakses halaman home/project') text-gray-400 px-2 sm:px-3 py-1 text-xs sm:text-sm font-semibold border-b-2 border-transparent rounded-t-lg">
                        <i class="fas fa-lock mr-1"></i>Home
                    </button>
                @endcanAccess
                
                @canAccess('document_read')
                    <a href="{{ route('documents.index') }}" 
                       class="text-white hover:text-red-100 px-2 sm:px-3 py-1 text-xs sm:text-sm font-semibold transition-all duration-500 ease-out border-b-2 {{ request()->routeIs('documents.*') ? 'border-white bg-red-700/80 shadow-lg scale-105' : 'border-transparent hover:border-red-200 hover:bg-red-700/30 hover:scale-105 hover:shadow-md' }} rounded-t-lg relative overflow-hidden group">
                        <span class="relative z-10">Documents</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000 ease-out"></div>
                    </a>
                @else
                    <button class="@privilegeButton('document_read', 'mengakses halaman documents') text-gray-400 px-2 sm:px-3 py-1 text-xs sm:text-sm font-semibold border-b-2 border-transparent rounded-t-lg">
                        <i class="fas fa-lock mr-1"></i>Documents
                    </button>
                @endcanAccess
                
                @canAccess('bidang_read')
                    <a href="{{ route('bidangs.index') }}" 
                       class="text-white hover:text-red-100 px-2 sm:px-3 py-1 text-xs sm:text-sm font-semibold transition-all duration-500 ease-out border-b-2 {{ request()->routeIs('bidangs.*') ? 'border-white bg-red-700/80 shadow-lg scale-105' : 'border-transparent hover:border-red-200 hover:bg-red-700/30 hover:scale-105 hover:shadow-md' }} rounded-t-lg relative overflow-hidden group">
                        <span class="relative z-10">GL Code</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000 ease-out"></div>
                    </a>
                @else
                    <button class="@privilegeButton('bidang_read', 'mengakses halaman kode bidang') text-gray-400 px-2 sm:px-3 py-1 text-xs sm:text-sm font-semibold border-b-2 border-transparent rounded-t-lg">
                        <i class="fas fa-lock mr-1"></i>GL Code
                    </button>
                @endcanAccess
                
                <!-- Account Dropdown -->
                <div class="relative">
                    <div x-data="{ accountOpen: false }" class="relative">
                        <button @click="accountOpen = !accountOpen" 
                                onclick="toggleAccountDropdown()"
                                class="text-white hover:text-red-100 px-2 sm:px-3 py-1 text-xs sm:text-sm font-semibold transition-all duration-500 ease-out border-b-2 {{ request()->routeIs('admin.*') ? 'border-white bg-red-700/80 shadow-lg scale-105' : 'border-transparent hover:border-red-200 hover:bg-red-700/30 hover:scale-105 hover:shadow-md' }} rounded-t-lg inline-flex items-center relative overflow-hidden group">
                                <span class="relative z-10">Account</span>
                                <svg class="ml-1 h-3 w-3 transition-all duration-500 ease-out relative z-10" :class="{ 'rotate-180 scale-110': accountOpen }" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                                <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/5 to-transparent translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-1000 ease-out"></div>
                        </button>

                        <!-- Enhanced Dropdown Menu -->
                        <div x-show="accountOpen" 
                             @click.outside="accountOpen = false"
                             x-transition:enter="transition ease-out duration-300"
                             x-transition:enter-start="transform opacity-0 scale-90 translate-y-[-10px]"
                             x-transition:enter-end="transform opacity-100 scale-100 translate-y-0"
                             x-transition:leave="transition ease-in duration-200"
                             x-transition:leave-start="transform opacity-100 scale-100 translate-y-0"
                             x-transition:leave-end="transform opacity-0 scale-90 translate-y-[-10px]"
                             id="accountDropdown"
                             style="display: none;"
                             class="absolute right-0 mt-2 w-56 rounded-xl shadow-2xl bg-white/95 backdrop-blur-md ring-1 ring-black/10 z-[99998] border border-gray-200/50">
                            <div class="py-1">
                                <!-- Enhanced Admin Info -->
                                <div class="px-4 py-3 border-b border-gray-200/50 bg-gradient-to-r from-gray-50 to-gray-100/50 backdrop-blur-sm">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8">
                                            <div class="h-8 w-8 rounded-full flex items-center justify-center text-white font-bold text-xs shadow-lg transition-transform duration-300 hover:scale-110" style="background: linear-gradient(135deg, {{ auth('admin')->user()->getRoleColor() }}, {{ auth('admin')->user()->getRoleColor() }}cc);">
                                                {{ substr(auth('admin')->user()->username, 0, 2) }}
                                            </div>
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900 transition-colors duration-200 hover:text-gray-700">{{ auth('admin')->user()->username }}</div>
                                            <div class="text-xs text-gray-500 transition-colors duration-200 hover:text-gray-600">{{ auth('admin')->user()->role }}</div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Menu Items -->
                                <!-- Create Account Menu -->
                                @canAccess('account_create')
                                    <a href="{{ route('admin.management.create') }}" 
                                       @click="accountOpen = false"
                                       class="group block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-green-50 hover:to-emerald-50 hover:text-green-600 transition-all duration-300 ease-out hover:translate-x-1 hover:shadow-sm rounded-md mx-1">
                                        <svg class="w-4 h-4 mr-2 inline transition-transform duration-300 group-hover:scale-110 group-hover:rotate-90" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                        </svg>
                                        Create Account
                                    </a>
                                @else
                                    <button class="@privilegeButton('account_create', 'membuat account baru') block w-full text-left px-4 py-2 text-sm text-gray-400 cursor-not-allowed">
                                        <i class="fas fa-lock w-4 h-4 mr-2 inline opacity-50"></i>
                                        Create Account <span class="text-xs">(No Access)</span>
                                    </button>
                                @endcanAccess

                                <!-- Manage Account Menu -->
                                @canAccess('account_update')
                                    <a href="{{ route('admin.management.index') }}" 
                                       @click="accountOpen = false"
                                       class="group block px-4 py-2 text-sm text-gray-700 hover:bg-gradient-to-r hover:from-blue-50 hover:to-indigo-50 hover:text-blue-600 transition-all duration-300 ease-out hover:translate-x-1 hover:shadow-sm rounded-md mx-1">
                                        <svg class="w-4 h-4 mr-2 inline transition-transform duration-300 group-hover:scale-110" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                                        </svg>
                                        Manage Account
                                    </a>
                                @else
                                    <button class="@privilegeButton('account_update', 'mengelola account') block w-full text-left px-4 py-2 text-sm text-gray-400 cursor-not-allowed">
                                        <i class="fas fa-lock w-4 h-4 mr-2 inline opacity-50"></i>
                                        Manage Account <span class="text-xs">(No Access)</span>
                                    </button>
                                @endcanAccess

                                <div class="border-t border-gray-200 my-1"></div>

                                <form method="POST" action="{{ route('admin.logout') }}">
                                    @csrf
                                    <button type="submit" class="group block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gradient-to-r hover:from-red-50 hover:to-rose-50 transition-all duration-300 ease-out hover:translate-x-1 hover:shadow-sm rounded-md mx-1">
                                        <svg class="w-4 h-4 mr-2 inline transition-transform duration-300 group-hover:scale-110 group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Exit
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Hamburger untuk Mobile -->
            <!-- Enhanced Hamburger untuk Mobile -->
            <div class="flex items-center sm:hidden mr-8">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-lg text-red-200 hover:text-white hover:bg-red-700/50 focus:outline-none focus:bg-red-700 focus:text-white transition-all duration-300 ease-out hover:scale-110 hover:shadow-lg backdrop-blur-sm">
                    <svg class="h-5 w-5 transition-all duration-300 ease-out" :class="{ 'rotate-90': open }" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex transition-all duration-300" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden transition-all duration-300" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Enhanced Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" 
         x-show="open"
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 transform -translate-y-2"
         x-transition:enter-end="opacity-100 transform translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 transform translate-y-0"
         x-transition:leave-end="opacity-0 transform -translate-y-2"
         class="hidden sm:hidden bg-gradient-to-b from-red-700 to-red-800 relative z-[99998] shadow-xl backdrop-blur-sm">
        <div class="pt-2 pb-3 space-y-1 border-t border-red-500/50 px-2">
            @canAccess('project_read')
                <a href="{{ route('home') }}" 
                   class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('home', 'projects.*') ? 'text-white bg-red-800' : 'text-red-100 hover:text-white hover:bg-red-800' }} transition-colors duration-200">
                    🏠 Home
                </a>
            @else
                <button class="@privilegeButton('project_read', 'mengakses halaman home/project') block w-full text-left px-3 py-2 text-sm font-medium rounded-md text-red-200 opacity-50 cursor-not-allowed">
                    <i class="fas fa-lock mr-1"></i> 🏠 Home (No Access)
                </button>
            @endcanAccess
            
            @canAccess('document_read')
                <a href="{{ route('documents.index') }}" 
                   class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('documents.*') ? 'text-white bg-red-800' : 'text-red-100 hover:text-white hover:bg-red-800' }} transition-colors duration-200">
                    📄 Documents
                </a>
            @else
                <button class="@privilegeButton('document_read', 'mengakses halaman documents') block w-full text-left px-3 py-2 text-sm font-medium rounded-md text-red-200 opacity-50 cursor-not-allowed">
                    <i class="fas fa-lock mr-1"></i> 📄 Documents (No Access)
                </button>
            @endcanAccess
            
            @canAccess('bidang_read')
                <a href="{{ route('bidangs.index') }}" 
                   class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('bidangs.*') ? 'text-white bg-red-800' : 'text-red-100 hover:text-white hover:bg-red-800' }} transition-colors duration-200">
                    📊 Kode Bidang
                </a>
            @else
                <button class="@privilegeButton('bidang_read', 'mengakses halaman kode bidang') block w-full text-left px-3 py-2 text-sm font-medium rounded-md text-red-200 opacity-50 cursor-not-allowed">
                    <i class="fas fa-lock mr-1"></i> 📊 Kode Bidang (No Access)
                </button>
            @endcanAccess
        </div>
        
        <!-- Account section for mobile -->
        <div class="pt-4 pb-1 border-t border-red-600">
            <!-- Admin info header -->
            <div class="px-4 pb-2">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-8 w-8">
                        <div class="h-8 w-8 rounded-full flex items-center justify-center text-white font-bold text-xs" style="background-color: {{ auth('admin')->user()->getRoleColor() }}">
                            {{ substr(auth('admin')->user()->username, 0, 2) }}
                        </div>
                    </div>
                    <div class="ml-3">
                        <div class="text-sm font-medium text-white">{{ auth('admin')->user()->username }}</div>
                        <div class="text-xs text-red-200">{{ auth('admin')->user()->role }}</div>
                    </div>
                </div>
            </div>
            
            <!-- Account menu items -->
            <div class="space-y-1 px-2">
                <!-- Create Account Menu -->
                @canAccess('account_create')
                    <a href="{{ route('admin.management.create') }}" 
                       class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.management.create') ? 'text-white bg-red-800' : 'text-red-100 hover:text-white hover:bg-red-800' }} transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        ➕ Create Account
                    </a>
                @else
                    <button class="@privilegeButton('account_create', 'membuat account baru') block w-full text-left px-3 py-2 text-sm text-red-200 opacity-50 cursor-not-allowed">
                        <i class="fas fa-lock w-4 h-4 mr-2 inline opacity-50"></i>
                        ➕ Create Account (No Access)
                    </button>
                @endcanAccess

                <!-- Manage Account Menu -->
                @canAccess('account_update')
                    <a href="{{ route('admin.management.index') }}" 
                       class="block px-3 py-2 text-sm font-medium rounded-md {{ request()->routeIs('admin.management.index', 'admin.management.show', 'admin.management.edit') ? 'text-white bg-red-800' : 'text-red-100 hover:text-white hover:bg-red-800' }} transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m13.5-9a2.5 2.5 0 11-5 0 2.5 2.5 0 015 0z"></path>
                        </svg>
                        👥 Manage Account
                    </a>
                @else
                    <button class="@privilegeButton('account_update', 'mengelola account') block w-full text-left px-3 py-2 text-sm text-red-200 opacity-50 cursor-not-allowed">
                        <i class="fas fa-lock w-4 h-4 mr-2 inline opacity-50"></i>
                        👥 Manage Account (No Access)
                    </button>
                @endcanAccess

                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="w-full text-left block px-3 py-2 text-sm font-medium rounded-md text-red-100 hover:text-white hover:bg-red-800 transition-colors duration-200">
                        <svg class="w-4 h-4 mr-2 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                        </svg>
                        🚪 Exit
                    </button>
                </form>
            </div>
        </div>
    </div>
</nav>

<script>
// Enhanced JavaScript untuk dropdown dengan smooth animations
function toggleAccountDropdown() {
    const dropdown = document.getElementById('accountDropdown');
    if (dropdown) {
        if (dropdown.style.display === 'none' || dropdown.style.display === '') {
            dropdown.style.display = 'block';
            dropdown.style.opacity = '0';
            dropdown.style.transform = 'scale(0.9) translateY(-10px)';
            dropdown.style.filter = 'blur(4px)';
            
            // Enhanced animate in
            requestAnimationFrame(() => {
                dropdown.style.transition = 'all 300ms cubic-bezier(0.4, 0.0, 0.2, 1)';
                dropdown.style.opacity = '1';
                dropdown.style.transform = 'scale(1) translateY(0)';
                dropdown.style.filter = 'blur(0px)';
            });
        } else {
            dropdown.style.transition = 'all 200ms cubic-bezier(0.4, 0.0, 0.2, 1)';
            dropdown.style.opacity = '0';
            dropdown.style.transform = 'scale(0.9) translateY(-10px)';
            dropdown.style.filter = 'blur(2px)';
            
            setTimeout(() => {
                dropdown.style.display = 'none';
            }, 200);
        }
    }
}

// Enhanced close dropdown when clicking outside
document.addEventListener('click', function(event) {
    const dropdown = document.getElementById('accountDropdown');
    const button = event.target.closest('button');
    
    if (dropdown && dropdown.style.display === 'block') {
        // Jika yang diklik bukan button account atau elemen dalam dropdown
        if (!button || !button.textContent.includes('Account')) {
            if (!dropdown.contains(event.target)) {
                dropdown.style.transition = 'all 200ms cubic-bezier(0.4, 0.0, 0.2, 1)';
                dropdown.style.opacity = '0';
                dropdown.style.transform = 'scale(0.9) translateY(-10px)';
                dropdown.style.filter = 'blur(2px)';
                
                setTimeout(() => {
                    dropdown.style.display = 'none';
                }, 200);
            }
        }
    }
});

// Enhanced smooth page transitions
document.addEventListener('DOMContentLoaded', function() {
    const dropdown = document.getElementById('accountDropdown');
    if (dropdown) {
        const links = dropdown.querySelectorAll('a');
        links.forEach(link => {
            link.addEventListener('click', (e) => {
                // Add subtle page transition effect
                document.body.style.transition = 'opacity 150ms ease-out';
                document.body.style.opacity = '0.95';
                
                setTimeout(() => {
                    dropdown.style.display = 'none';
                }, 50);
            });
        });
    }
    
    // Add smooth scroll behavior to all internal links
    const internalLinks = document.querySelectorAll('a[href^="' + window.location.origin + '"]');
    internalLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            // Add page transition effect
            document.body.style.transition = 'all 200ms cubic-bezier(0.4, 0.0, 0.2, 1)';
            document.body.style.transform = 'translateY(-2px)';
            document.body.style.opacity = '0.98';
        });
    });
});

// Add smooth navbar scroll effect
let lastScrollTop = 0;
const navbar = document.querySelector('nav');

window.addEventListener('scroll', function() {
    const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
    
    if (scrollTop > lastScrollTop && scrollTop > 100) {
        // Scrolling down
        navbar.style.transform = 'translateY(-2px)';
        navbar.style.boxShadow = '0 10px 25px rgba(0, 0, 0, 0.15)';
    } else {
        // Scrolling up
        navbar.style.transform = 'translateY(0)';
        navbar.style.boxShadow = '0 4px 15px rgba(0, 0, 0, 0.1)';
    }
    
    lastScrollTop = scrollTop;
}, { passive: true });
</script>
