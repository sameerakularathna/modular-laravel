<div>
    <h1 class="text-2xl font-bold mb-4">Package Manager</h1>

    <!-- Package Installation Form -->
    <div class="mb-6">
        <input type="text" wire:model="gitUrl" value="" placeholder="Enter GitHub repository URL" class="border p-2 w-full">
        <button wire:click="installPackage1" class="bg-blue-500 text-white px-4 py-2 mt-2" wire:loading.attr="disabled">
            {{ $loading ? 'Installing...' : 'Install Package' }}
        </button>
    
    </div>
 <!-- Success/Error Messages -->
 @if (session()->has('success'))
        <div class="text-green-600">{{ session('success') }}</div>
    @endif
    @if (session()->has('error'))
        <div class="text-red-600">{{ session('error') }}</div>
    @endif
    <!-- Package List -->
    <div>
    <h2 class="text-xl font-bold mb-4">Custom Packages</h2>

    <!-- List of Custom Packages -->
    <ul>
        @forelse ($customPackages as $package)
            <li class="mb-2">
                <strong>{{ $package['name'] }}</strong> (Version: {{ $package['version'] }})
                <button wire:click="installCustomPackageFromUI('{{ $package['name'] }}')" class="bg-blue-500 text-white px-2 py-1 ml-2">
                    Install
                </button>
            </li>
        @empty
            <li>No custom packages found.</li>
        @endforelse
    </ul>
</div>
</div>