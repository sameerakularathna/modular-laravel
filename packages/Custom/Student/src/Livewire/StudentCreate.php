<?php
namespace Custom\Student\Livewire;

use Livewire\Component;
use Custom\Student\Models\Student;

class StudentCreate extends Component
{
    public $student_id, $name;

    protected $rules = [
        'name' => 'required|string|max:255'
    ];

    public function mount($studentId = null)
    {
        if ($studentId) {
            $student = Student::findOrFail($studentId);
            $this->student_id = $student->id;
            $this->name = $student->name;
        }
    }

    public function save()
    {
        $this->validate();

        Student::updateOrCreate(
            ['id' => $this->student_id],
            ['name' => $this->name]
        );

        return redirect()->route('students.index');
    }

    public function render()
    {
        return view('student::livewire.student-create');
    }
}
