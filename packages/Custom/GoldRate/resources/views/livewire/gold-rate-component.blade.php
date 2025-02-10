<div class="container mt-4">
    <!-- Success Message -->
    @if(session()->has('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif

    <!-- Gold Rate Form -->
    <div class="card shadow p-4 mb-4">
        <h4 class="mb-3">{{ $editingId ? 'Update' : 'Add' }} Gold Rate</h4>
        <form wire:submit.prevent="{{ $editingId ? 'update' : 'store' }}">
            <div class="mb-3">
                <label class="form-label">Metal</label>
                <input type="text" wire:model="metal" class="form-control" placeholder="Enter metal type">
                @error('metal') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">With Duty Free</label>
                <input type="number" wire:model="with_duty_free" class="form-control" placeholder="Enter with duty-free rate">
                @error('with_duty_free') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Duty Free</label>
                <input type="number" wire:model="duty_free" class="form-control" placeholder="Enter duty-free rate">
                @error('duty_free') <span class="text-danger">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="btn btn-primary">{{ $editingId ? 'Update' : 'Add' }}</button>
        </form>
    </div>

    <!-- Gold Rates Table -->
    <div class="card shadow p-4">
        <h4 class="mb-3">Gold Rate List</h4>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>Metal</th>
                    <th>With Duty Free</th>
                    <th>Duty Free</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach($goldRates as $goldRate)
                    <tr>
                        <td>{{ $goldRate->metal }}</td>
                        <td>{{ $goldRate->with_duty_free }}</td>
                        <td>{{ $goldRate->duty_free }}</td>
                        <td>
                            <button wire:click="edit({{ $goldRate->id }})" class="btn btn-warning btn-sm">Edit</button>
                            <button wire:click="delete({{ $goldRate->id }})" class="btn btn-danger btn-sm">Delete</button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
