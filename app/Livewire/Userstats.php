<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Services\CalculationService;

class Userstats extends Component
{
    public $user;
    public $streakCount;
    public $xp;
    public $level;
    public $coins;
    public $progressPercentage;
    public $maxXp;
    public $user_id;

    protected $calculationService;

    public function mount(CalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
        $this->updateStats();
    }

    public function hydrate(CalculationService $calculationService)
    {
        $this->calculationService = $calculationService;
    }

    public function render()
    {
        return view('livewire.userstats');
    }

    public function updateStats()
    {
        $this->user = User::find(Auth::user()->id);
        $user_id = $this->user->id;

        $this->streakCount = $this->user->streakCount;
        $this->xp = $this->user->xp;
        $this->level = $this->user->level;
        $this->coins = $this->user->coins;

        $requiredXp = $this->calculationService->calculateRequiredXp($user_id);
        $this->maxXp = $requiredXp;
        $this->progressPercentage = ($this->xp / $requiredXp) * 100;
        $this->progressPercentage = max(0, min(100, $this->progressPercentage));
        $this->progressPercentage = number_format($this->progressPercentage, 1);
    }
}