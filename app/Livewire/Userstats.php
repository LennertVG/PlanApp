<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class Userstats extends Component
{
    public $user;
    public $streakCount;
    public $xp;
    public $level;
    public $coins;
    public $progressPercentage;
    public $maxXp;

    public function render()
    {
        return view('livewire.userstats');
    }

    public function mount()
    {
        $this->updateStats();
    }

    public function updateStats()
    {
        $this->user = User::find(Auth::user()->id);

        $this->streakCount = $this->user->streakCount;
        $this->xp = $this->user->xp;
        $this->level = $this->user->level;
        $this->coins = $this->user->coins;

        $xpRequiredForNextLevel = ($this->level + 1) * 100;
        $this->maxXp = $xpRequiredForNextLevel;
        $this->progressPercentage = ($this->xp / $xpRequiredForNextLevel) * 100;
        $this->progressPercentage = max(0, min(100, $this->progressPercentage));
        $this->progressPercentage = number_format($this->progressPercentage, 1);
    }
}
