<x-app-layout>
    <x-slot name="header">
        <h2>Spécialités médicales</h2>
    </x-slot>

    <div>
        <a href="{{ route('specialties.create') }}" style="display:inline-flex; align-items:center; gap:6px;">
            <x-heroicon-o-plus class="w-5 h-5" />
            Ajouter une spécialité
        </a>

        <ul>
            @foreach ($specialties as $specialty)
                <li>
                    {{ $specialty->name }}

                    <a href="{{ route('specialties.edit', $specialty) }}" style="display:inline-flex; align-items:center; gap:4px;">
                        <x-heroicon-o-pencil-square class="w-5 h-5" />
                        Modifier
                    </a>

                    <form action="{{ route('specialties.destroy', $specialty) }}" method="POST" style="display:inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" style="display:inline-flex; align-items:center; gap:4px;">
                            <x-heroicon-o-trash class="w-5 h-5" />
                            Supprimer
                        </button>
                    </form>
                </li>
            @endforeach
        </ul>
    </div>
</x-app-layout>
