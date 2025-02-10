<?php
namespace Custom\Employee\Livewire;

use Livewire\Component;
use Custom\Employee\Models\Employee;

class EmployeeCreate extends Component
{
    public $employee_id, $name;

    protected $rules = [
        'name' => 'required|string|max:255'
    ];

    public function mount($employeeId = null)
    {
        if ($employeeId) {
            $employee = Employee::findOrFail($employeeId);
            $this->employee_id = $employee->id;
            $this->name = $employee->name;
        }
    }

    public function save()
    {
        $this->validate();

        Employee::updateOrCreate(
            ['id' => $this->employee_id],
            ['name' => $this->name]
        );

        return redirect()->route('employees.index');
    }

    public function render()
    {
        return view('employee::livewire.employee-create');
    }
}
