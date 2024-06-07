<div wire:poll.1s="updateStats" class="userstats" style="background-color: #2c3e50; padding-left: 20px; padding-right: 20px; border-radius: 10px; color: white; padding-bottom: 20px;">
    <span> {{ $xp }}/{{ $maxXp }} XP -
     Level {{ $level }} - 
     {{ $coins }} <i class="fa-solid fa-sack-dollar"></i>
     
     <?php
        {{-- display an image based on the streakCount --}}
        if ($streakCount > 0 && $streakCount < 5) {
            echo ' - streak: ' . $streakCount;
        }
        elseif ($streakCount >= 5 && $streakCount < 10) {
            echo ' - streak: ' . $streakCount . ' <i class="fa-solid fa-fire-flame-curved" style="color: yellow;"></i> x1.25';
        }
        elseif ($streakCount >= 10 && $streakCount < 15) {
            echo ' - streak: ' . $streakCount . ' <i class="fa-solid fa-fire-flame-simple" style="color: orange;"></i> x1.5';
        }
        elseif ($streakCount >= 15 && $streakCount < 20) {
            echo ' - streak: ' . $streakCount . ' <i class="fa-solid fa-fire" style="color: red;"></i> x1.75';
        }
        elseif ($streakCount >= 20) {
            echo ' - streak: ' . $streakCount . ' <i class="fa-solid fa-fire" style="color: aqua;"></i> x2';
        }
     ?>
     
     </span>
    
    <div class="progress" style="height: 20px; background-color: #34495e; border-radius: 10px; margin-top: 10px;">
        <div class="progress-bar" role="progressbar" style="width: {{ $progressPercentage }}%; background-color: #1abc9c;" aria-valuenow="{{ $progressPercentage }}" aria-valuemin="0" aria-valuemax="100">{{ $progressPercentage }}%</div>
    </div>
</div>
