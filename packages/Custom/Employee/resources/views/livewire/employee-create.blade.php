<div>
    <form wire:submit.prevent="save">
        @csrf
        <div>
            <label>Title</label>
            <input type="text" wire:model="name" class="form-control">
            @error('name') <span class="text-danger">{{ $message }}</span> @enderror
        </div>

        <button type="submit" class="btn btn-success">Save</button>
    </form>
</div>
