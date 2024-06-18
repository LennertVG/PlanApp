<div wire:poll.5s class="container my-5">
    <div class="mb-5">
        {{-- Display user's rewards --}}
        @if($userRewards->isNotEmpty())
            <h2 class="mb-4">Jouw huidige rewards</h2>
            <div class="row">
                @foreach($userRewards as $reward)
                    @if($reward->pivot->amount != 0)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div style="display: flex; justify-content: space-between;">
                                <h5 class="card-title">{{ $reward->name }}</h5>
                                <i style="font-size: 1.5em" class="fa-solid fa-{{ $reward->iconPath }}"></i>
                                </div>
                                <p class="card-text">{{ $reward->pivot->amount }} / {{ $reward->maxAmount }}</p>
                            </div>
                        </div>
                    </div>
                    @endif
                @endforeach
            </div>
        @endif
    </div>
    <div>
        <div class="mb-4">
            {{-- Notification field --}}
            @if (session()->has('notification'))
                <div class="alert alert-danger" role="alert">
                    {{ session('notification') }}
                </div>
            @endif
            @if (session()->has('success'))
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endif
        </div>
        {{-- Shop Items --}}
        <h2 class="mb-4">Item Shop</h2>
        @if($rewards->isNotEmpty())
            <div class="row">
                @foreach($rewards as $reward)
                    <div class="col-md-4 mb-4">
                        <div class="card h-100">
                            <div class="card-body">
                                <div style="display: flex; justify-content: space-between">
                                    <h5 class="card-title">{{ $reward->name }}</h5>
                                    <i style="font-size: 20px" class="fa-solid fa-{{ $reward->iconPath }}"></i>
                                </div>
                                <p class="card-text">{{ $reward->description }}</p>
                                <hr>
                                <div style="display: flex; justify-content: space-between ; align-items: flex-end">
                                    <div>
                                        <p class="card-text"><strong>{{ $reward->price }} coins</strong></p>
                                        <p class="card-text">Max: {{ $reward->maxAmount }}</p>
                                    </div>
                                    <div>
                                <button wire:click="buyReward({{ $reward->id }})" class="btn custom-reward-btn" style="margin-left:0px;">Kopen</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
