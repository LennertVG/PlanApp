<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\Reward;
use App\Models\User;
use App\Models\Group;

class UseRewards extends Component
{
    public $rewards;
    public $userRewards;
    public $user;
    public $reward;
    public $group;
    public $studentsByGroupByCourse;
    public $teacherId;


    public function mount()
    {
        $this->getRewards();
        $this->getRewardsByUser();
        $this->getRewardsByGroup();
        $this->getAllStudentsFromTeacher();
    }

    
    public function render()
    {
        return view('livewire.use-rewards');
    }
    
    public function getAllStudentsFromTeacher()
        {
            if (Auth::check()) {
                $teacherId = Auth::user()->id;
                $this->studentsByGroupByCourse = \App\Models\User::whereHas('courses', function ($courseQuery) use ($teacherId) {
                    $courseQuery->where('user_id', $teacherId); // Adjust this column name as needed
                })->with([
                    'courses' => function ($courseQuery) use ($teacherId) {
                        $courseQuery->where('user_id', $teacherId)->with([
                            'groups' => function ($groupQuery) {
                                $groupQuery->with([
                                    'users' => function ($userQuery) {
                                        $userQuery->with([
                                        ]);
                                    }
                                ]);
                            }
                        ]);
                    }
                ])->get();
                return $this->studentsByGroupByCourse;
                // dd($studentsByGroupByCourse);
            }
        }

    public function getRewards(){
        $this->rewards = Reward::all();
    }

    public function getRewardsByUser(){
        $this->userRewards = Auth::user()->rewards()->get();
    }

    public function getRewardsByGroup(){
        $this->group = Auth::user()->groups()->get();
    }
}
