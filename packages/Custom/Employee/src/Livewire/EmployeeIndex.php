<?php

namespace Custom\Employee\Livewire;

use Livewire\Component;
use Custom\Employee\Models\Employee;
use Livewire\WithPagination;

class EmployeeIndex extends Component
{
    use WithPagination;

    public function delete($id)
    {
        Post::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('employee::livewire.employee-index', [
            'employees' => Employee::latest()->paginate(5)
        ]);
    }
}
