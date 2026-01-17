<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Gestion des Utilisateurs') }}
        </h2>
    </x-slot>

    <div class="py-12" x-data="{
        showEditModal: false,
        editForm: { id: null, name: '', email: '', role: '', address: '', city: '', zip_code: '' },
        editUser(user) {
            this.editForm = {
                id: user.id,
                name: user.name,
                email: user.email,
                role: user.role,
                address: user.doctor_profile ? user.doctor_profile.address : '',
                city: user.doctor_profile ? user.doctor_profile.city : '',
                zip_code: user.doctor_profile ? user.doctor_profile.zip_code : ''
            };
            this.showEditModal = true;
        },
        closeModal() {
            this.showEditModal = false;
        }
    }">
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

                    <div class="space-y-6 mb-6">
                        <!-- Header: Title & Role Filters -->
                        <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
                            <h3 class="text-lg font-medium text-gray-900">Liste des utilisateurs</h3>
                        </div>

                        <!-- Search Form -->
                        <form method="GET" action="{{ route('admin.users') }}" class="flex items-end gap-4 bg-gray-50 p-4 w-full">
                            <!-- Preserve Filters -->
                            @if(request('role')) <input type="hidden" name="role" value="{{ request('role') }}"> @endif
                            @if(request('sort_by')) <input type="hidden" name="sort_by" value="{{ request('sort_by') }}"> @endif
                            @if(request('sort_order')) <input type="hidden" name="sort_order" value="{{ request('sort_order') }}"> @endif


                            <!-- Text Search (Flex Grow) -->
                            <div class="flex-1 min-w-0">
                                <div class="relative mt-1">
                                    <x-text-input id="search" name="search" type="text" 
                                                  class="block w-full h-10 pl-10" 
                                                  placeholder="Nom, email..." 
                                                  :value="request('search')" />
                                </div>
                            </div>
                            
                            <!-- Date Search (Fixed Width) -->
                            <div class="flex-1 min-w-0">
                                <x-text-input id="date" name="date" type="date" 
                                              class="mt-1 block w-full h-10" 
                                              :value="request('date')" />
                            </div>

                            <!-- Role Quick Filters (Centered Text) -->
                            <div class="flex items-center gap-2">
                                <a href="{{ request()->fullUrlWithQuery(['role' => null]) }}" 
                                   class="h-13 px-4 rounded-full text-sm font-medium transition-colors border shadow-sm inline-flex items-center justify-center whitespace-nowrap"
                                   style="{{ !request('role') ? 'background-color: #0596de; color: white; border-color: #0596de;' : 'background-color: white; color: #374151; border-color: #d1d5db;' }}">
                                    Tous
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['role' => 'doctor']) }}" 
                                   class="h-13 px-4 rounded-full text-sm font-medium transition-colors border shadow-sm inline-flex items-center justify-center"
                                   style="{{ request('role') === 'doctor' ? 'background-color: #0596de; color: white; border-color: #0596de;' : 'background-color: white; color: #374151; border-color: #d1d5db;' }}">
                                    Médecins
                                </a>
                                <a href="{{ request()->fullUrlWithQuery(['role' => 'patient']) }}" 
                                   class="h-13 px-4 rounded-full text-sm font-medium transition-colors border shadow-sm inline-flex items-center justify-center"
                                   style="{{ request('role') === 'patient' ? 'background-color: #0596de; color: white; border-color: #0596de;' : 'background-color: white; color: #374151; border-color: #d1d5db;' }}">
                                    Patients
                                </a>

                                <!-- Action Buttons -->
                                <div class="ml-auto flex gap-2">
                                    <x-primary-button class="h-10 px-6 justify-center">
                                        Filtrer
                                    </x-primary-button>
                                    
                                    @if(request('search') || request('date'))
                                        <a href="{{ route('admin.users', ['role' => request('role')]) }}" 
                                        class="h-10 w-10 inline-flex items-center justify-center border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                            X
                                        </a>
                                    @endif
                                </div>

                            </div>

                            
                        </form>
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
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ville</th>
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
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                            @if($user->role === 'doctor' && $user->doctorProfile)
                                                {{ $user->doctorProfile->city ?? '-' }}
                                            @elseif($user->role === 'patient')
                                                <div class="flex justify-center">
                                                    <svg
                                                        xmlns="http://www.w3.org/2000/svg"
                                                        class="w-5 h-5 text-gray-300"
                                                        fill="none"
                                                        viewBox="0 0 24 24"
                                                        stroke="currentColor"
                                                        stroke-width="2"
                                                    >
                                                        <path
                                                            stroke-linecap="round"
                                                            stroke-linejoin="round"
                                                            d="M6 18L18 6M6 6l12 12"
                                                        />
                                                    </svg>
                                                </div>
                                            @else
                                                -
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $user->created_at->format('d/m/Y') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            @if($user->role !== 'admin')
                                                <button @click="editUser({{ $user }})" class="p-2 text-gray-400 hover:text-orange-600 hover:bg-orange-50 rounded-lg transition-colors" title="Modifier">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                                                    </svg>
                                                </button>
                                            @endif
                                            @if(auth()->id() !== $user->id)
                                                <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="inline-block" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet utilisateur ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Supprimer">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                        </svg>
                                                    </button>
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

            <!-- Edit User Modal -->
            <div x-show="showEditModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                    <div x-show="showEditModal" 
                         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" 
                         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" 
                         class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="closeModal" aria-hidden="true"></div>

                    <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                    <div x-show="showEditModal" 
                         x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" 
                         x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" 
                         class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md w-full">
                        
                        <form method="POST" :action="'/admin/users/' + editForm.id">
                            @csrf
                            @method('PUT')
                            
                            <div class="bg-white px-6 pt-6 pb-6 sm:p-8">
                                <div class="sm:flex sm:items-start">
                                    <div class="text-center sm:text-left w-full">
                                        
                                        <!-- Title -->
                                        <h3 class="text-lg font-semibold text-gray-900 mb-6" id="modal-title">
                                            Modifier l'utilisateur
                                        </h3>

                                        <!-- Form -->
                                        <div class="space-y-6">

                                            <!-- Name -->
                                            <div>
                                                <x-input-label for="edit_name" value="Nom" />
                                                <x-text-input
                                                    id="edit_name"
                                                    name="name"
                                                    type="text"
                                                    class="mt-2 block w-full"
                                                    x-model="editForm.name"
                                                    required
                                                />
                                            </div>

                                            <!-- Email -->
                                            <div>
                                                <x-input-label for="edit_email" value="Email" />
                                                <x-text-input
                                                    id="edit_email"
                                                    name="email"
                                                    type="email"
                                                    class="mt-2 block w-full"
                                                    x-model="editForm.email"
                                                    required
                                                />
                                            </div>

                                            <!-- Role -->
                                            <div>
                                                <x-input-label for="edit_role" value="Rôle" />
                                                <select
                                                    id="edit_role"
                                                    name="role"
                                                    x-model="editForm.role"
                                                    class="mt-2 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                                                >
                                                    <option value="patient">Patient</option>
                                                    <option value="doctor">Médecin</option>
                                                </select>
                                            </div>

                                            <!-- Address (Doctors Only) -->
                                            <template x-if="editForm.role === 'doctor'">
                                                <div class="pt-6 space-y-4 border-t border-gray-200">
                                                    <p class="text-sm font-semibold text-gray-700">
                                                        Adresse (Médecin)
                                                    </p>

                                                    <x-text-input
                                                        name="address"
                                                        type="text"
                                                        class="block w-full mt-3"
                                                        placeholder="Adresse"
                                                        x-model="editForm.address"
                                                    />

                                                    <div class="grid grid-cols-2 gap-6 mt-3">
                                                        <x-text-input
                                                            name="city"
                                                            type="text"
                                                            class="block w-full mt-3"
                                                            placeholder="Ville"
                                                            x-model="editForm.city"
                                                        />
                                                        <x-text-input
                                                            name="zip_code"
                                                            type="text"
                                                            class="block w-full mt-3"
                                                            placeholder="Code postal"
                                                            x-model="editForm.zip_code"
                                                        />
                                                    </div>
                                                </div>
                                            </template>

                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 flex gap-3 mt-3">
                                <x-primary-button class="w-full justify-center gap-3">
                                    Enregistrer
                                </x-primary-button>

                                <x-secondary-button class="w-full justify-center" @click="closeModal">
                                    Annuler
                                </x-secondary-button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
