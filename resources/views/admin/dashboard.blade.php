<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Tableau de bord Administrateur') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Welcome Section -->
            <div class="mb-8">
                <h3 class="text-lg font-medium text-gray-900">Vue d'ensemble</h3>
                <p class="text-sm text-gray-500">Voici ce qui se passe sur votre plateforme aujourd'hui.</p>
            </div>

            <!-- Stats Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-10">
                <!-- Doctors Card -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6 flex items-center transition hover:shadow-md">
                    <div class="p-4 rounded-full mr-5" style="background-color: #eff6ff; color: #2563eb;">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Médecins</div>
                        <div class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['doctors_count'] }}</div>
                    </div>
                </div>

                <!-- Patients Card -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6 flex items-center transition hover:shadow-md">
                    <div class="p-4 rounded-full mr-5" style="background-color: #f0fdfa; color: #0d9488;">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Patients</div>
                        <div class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['patients_count'] }}</div>
                    </div>
                </div>

                <!-- Appointments Card -->
                <div class="bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 p-6 flex items-center transition hover:shadow-md">
                    <div class="p-4 rounded-full mr-5" style="background-color: #eef2ff; color: #4f46e5;">
                        <!-- Changed Icon to Calendar -->
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <div class="text-sm font-medium text-gray-500 uppercase tracking-wide">Rendez-vous</div>
                        <div class="text-3xl font-bold text-gray-900 mt-1">{{ $stats['appointments_count'] }}</div>
                    </div>
                </div>
            </div>

            <!-- Management Section -->
            <div class="mb-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4 mt-4">Gestion & Administration</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Manage Users -->
                    <a href="{{ route('admin.users') }}" class="group block bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 hover:border-blue-500 hover:ring-1 hover:ring-blue-500 transition-all duration-200">
                        <div class="p-6 flex items-center"> <!-- Changed items-start to items-center for better alignment -->
                            <div class="p-3 bg-gray-50 group-hover:bg-blue-50 rounded-lg text-gray-500 group-hover:text-blue-600 transition-colors mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1"> <!-- Added flex-1 to push arrow right -->
                                <h4 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors">Gérer les utilisateurs</h4>
                                <p class="text-gray-500 text-sm mt-1">Consultez, modifiez ou supprimez les comptes utilisateurs (médecins et patients).</p>
                            </div>
                            <div class="ml-4"> <!-- Added Margin Left for safe space -->
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-blue-500 transform group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </a>

                    <!-- Manage Specialties -->
                    <a href="{{ route('specialties.index') }}" class="group block bg-white overflow-hidden shadow-sm rounded-xl border border-gray-100 hover:border-blue-500 hover:ring-1 hover:ring-blue-500 transition-all duration-200">
                        <div class="p-6 flex items-center"> <!-- Changed items-start to items-center -->
                            <div class="p-3 bg-gray-50 group-hover:bg-blue-50 rounded-lg text-gray-500 group-hover:text-blue-600 transition-colors mr-4">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                                </svg>
                            </div>
                            <div class="flex-1"> <!-- Added flex-1 -->
                                <h4 class="text-lg font-bold text-gray-900 group-hover:text-blue-600 transition-colors">Gérer les spécialités</h4>
                                <p class="text-gray-500 text-sm mt-1">Ajoutez ou modifiez les spécialités médicales disponibles sur la plateforme.</p>
                            </div>
                            <div class="ml-4">
                                <svg class="w-5 h-5 text-gray-300 group-hover:text-blue-500 transform group-hover:translate-x-1 transition-all" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                                </svg>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
