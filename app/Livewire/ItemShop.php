<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Reward;
use Illuminate\Support\Facades\Auth;

class ItemShop extends Component
{

    public $rewards;
    public $currentAmount;
    public $userRewards;
    public $reward;

    public function getRewards(){
        $this->rewards = Reward::all();
        // dd($this->rewards);
    }
    public function getRewardsByUser(){
        // look for rewards for user in pivot table
        $this->userRewards = Auth::user()->rewards;
        // dd($this->userRewards);
    }

    public function addReward($id){
        $reward = Reward::find($id);
        $user = Auth::user();
        $pivotRecord = $reward->users()->where('user_id', $user->id)->withPivot('amount')->first();

        // if reward is not in pivot table, add it
        if (!$pivotRecord) {
            Auth::user()->rewards()->attach($id);
        }
        // if reward is in pivot table AND amount is less than max amount in task table, update amount
        else {
            // add amount to reward
            $reward->users()->updateExistingPivot($user->id, ['amount' => $pivotRecord->pivot->amount + 1]);
        }
    }
    
    public function removeCoins($id){
        $user = Auth::user();
        $reward = Reward::find($id);
        // remove coins from user
        $user->coins -= $reward->price;
        $user->save();
    }


    public function buyReward($id){
        $this->reward = Reward::find($id);
        $pivotRecord = $this->reward->users()->where('user_id', Auth::user()->id)->first();
        // dd($this->reward);
        // dd($pivotRecord->pivot->amount);
        if($this->reward->price > Auth::user()->coins){
            session()->flash('notification', 'Je hebt niet genoeg coins!');
        }
        // if you have more of this reward than the max amount in the task table, throw message
        elseif($pivotRecord && $pivotRecord->pivot->amount >= $this->reward->maxAmount){
            session()->flash('notification', 'Je kan niet meer van deze reward kopen!');
        }
        else{
            $this->addReward($id);
            $this->removeCoins($id);
            session()->flash('success', 'Je hebt een beloning gekocht!');
        }
    }

    public function mount()
    {
        $this->getRewards();
        $this->getRewardsByUser();
    }

    public function render()
    {
        return view('livewire.item-shop');
    }

    public function hydrate()
    {
        $this->getRewards();
        $this->getRewardsByUser();
    }
}
