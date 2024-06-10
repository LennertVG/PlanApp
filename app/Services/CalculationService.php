<?php

namespace App\Services;
use Illuminate\Support\Facades\Auth;

class CalculationService
{
    
    public function calculateRequiredXp()
    {
        $user = Auth::user();
        $requiredXp = ($user->level + 1) * 100;
        return $requiredXp;
    }

    public function calculateRewardMultiplier()
    {
        $user = Auth::user();
        $rewardMultiplier = 1;

        // Determine the reward multiplier based on the streak count
        if ($user->streakCount >= 20) {
            $rewardMultiplier = 2;
        } elseif ($user->streakCount >= 15) {
            $rewardMultiplier = 1.75;
        } elseif ($user->streakCount >= 10) {
            $rewardMultiplier = 1.5;
        } elseif ($user->streakCount >= 5) {
            $rewardMultiplier = 1.25;
        }

        return $rewardMultiplier;
    }
}