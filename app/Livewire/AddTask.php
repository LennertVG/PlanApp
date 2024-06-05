<?php

namespace App\Livewire;

use Livewire\Component;

class AddTask extends Component
{

    public $courses = [];
    public $taskTypes = [];

    public function render()
    {
        return view('livewire.add-task');
    }
    public function mount()
    {
        $this->courses = \App\Models\Course::all();
        // $this->courses = [
        //     [
        //         "id" => 1,
        //         "name" => "Wiskunde"
        //     ],
        //     [
        //         "id" => 2,
        //         "name" => "Nederlands"
        //     ],
        //     [
        //         "id" => 3,
        //         "name" => "Natuurwetenschappen"
        //     ]
        // ];

        $this->taskTypes = \App\Models\Tasktype::all();
        // $this->taskTypes = [
        //     [
        //         "id" => 1,
        //         "name" => "Samenvatten"
        //         ],
        //     [
        //         "id" => 2,
        //         "name" => "Extra Werk"
        //     ]
        //     ];
    }
}
