<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des Utilisateurs') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    
                    <div class="text-center">
                        @if (session('success'))
                            <div class="mb-6 px-6 py-2 rounded-full inline-block font-medium shadow-sm transition-all"
                                 style="background-color: #d1fae5; color: #065f46; border: 1px solid #34d399;">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('error'))
                            <div class="mb-6 px-6 py-2 rounded-full inline-block font-medium shadow-sm transition-all"
                                 style="background-color: #fee2e2; color: #991b1b; border: 1px solid #f87171;">
                                {{ session('error') }}
                            </div>
                        @endif
                    </div>

                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Liste des utilisateurs</h3>
                        
                        <!-- Role Filters -->
                        <div class="flex space-x-2">
                            <a href="{{ request()->fullUrlWithQuery(['role' => null]) }}" 
                               class="px-4 py-2 rounded-full text-sm font-medium transition-colors border shadow-sm"
                               style="{{ !request('role') ? 'background-color: #0596de; color: white; border-color: #0596de;' : 'background-color: white; color: #374151; border-color: #d1d5db;' }}">
                                Tous
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['role' => 'doctor']) }}" 
                               class="px-4 py-2 rounded-full text-sm font-medium transition-colors border shadow-sm"
                               style="{{ request('role') === 'doctor' ? 'background-color: #0596de; color: white; border-color: #0596de;' : 'background-color: white; color: #374151; border-color: #d1d5db;' }}">
                                Médecins
                            </a>
                            <a href="{{ request()->fullUrlWithQuery(['role' => 'patient']) }}" 
                               class="px-4 py-2 rounded-full text-sm font-medium transition-colors border shadow-sm"
                               style="{{ request('role') === 'patient' ? 'background-color: #0596de; color: white; border-color: #0596de;' : 'background-color: white; color: #374151; border-color: #d1d5db;' }}">
                                Patients
                            </a>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'name', 'sort_order' => request('sort_by') === 'name' && request('sort_order') === 'asc' ? 'desc' : 'asc']) }}" class="group flex items-center space-x-1 hover:text-gray-900">
                                            <span>Nom</span>
                                            @if(request('sort_by') === 'name')
                                                <svg class="w-4 h-4 text-gray-900" 
                                                    fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    @if(request('sort_order') === 'asc')
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    @endif
                                                </svg>
                                            @else
                                                <!-- Inactive Sort Icon (Always visible now) -->
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                                </svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        <a href="{{ request()->fullUrlWithQuery(['sort_by' => 'created_at', 'sort_order' => request('sort_by') === 'created_at' && request('sort_order') === 'asc' ? 'desc' : 'asc']) }}" class="group flex items-center space-x-1 hover:text-gray-900">
                                            <span>Date d'inscription</span>
                                            @if(request('sort_by') === 'created_at' || !request('sort_by'))
                                                <svg class="w-4 h-4 text-gray-900" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    @if(request('sort_order') === 'asc')
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path>
                                                    @else
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    @endif
                                                </svg>
                                            @else
                                                <!-- Inactive Sort Icon (Always visible now) -->
                                                <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l4-4 4 4m0 6l-4 4-4-4"></path>
                                                </svg>
                                            @endif
                                        </a>
                                    </th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @foreach($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm text-gray-500">{{ $user->email }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($user->role === 'admin')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-purple-100 text-purple-800">Admin</span>
                                            @elseif($user->role === 'doctor')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Médecin</span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Patient</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if(auth()->id() !== $user->id)
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-600 hover:text-red-900">Supprimer</button>
                                                </form>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
