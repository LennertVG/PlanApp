<?php

namespace App\Livewire;

use Livewire\Component;

class AddTask extends Component
{

    public $courses = [];

    public function render()
    {
        return view('livewire.add-task');
    }
    public function mount()
    {
        // $this->courses = \App\Models\Course::all();
        $this->courses = [
            [
                "id" => 1,
                "name" => "Introduction to Programming"
            ],
            [
                "id" => 2,
                "name" => "Web Development Fundamentals"
            ],
            [
                "id" => 3,
                "name" => "Data Analysis with Python"
            ]
        ];
    }
}
