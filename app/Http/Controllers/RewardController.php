<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Reward;
use App\Models\User;

class RewardController extends Controller
{
    public function useReward(Request $request){
        $this->reward = Reward::find($request->reward_id);
        // dd($this->reward);
        $this->user = User::find($request->student_id);
        // dd($this->user);
        $pivotRecord = $this->user->rewards()->where('reward_id', $request->reward_id)->first();
        // dd($pivotRecord);
        $pivotRecord->pivot->amount -= 1;
        $pivotRecord->pivot->save();
        session()->flash('success', 'Reward gebruikt');
        return redirect()->back();
    }
}
