<?php

namespace Custom\Student\Livewire;

use Livewire\Component;
use Custom\Student\Models\Student;
use Livewire\WithPagination;

class StudentIndex extends Component
{
    use WithPagination;

    public function delete($id)
    {
        Post::findOrFail($id)->delete();
    }

    public function render()
    {
        return view('student::livewire.student-index', [
            'students' => Student::latest()->paginate(5)
        ]);
    }
}
