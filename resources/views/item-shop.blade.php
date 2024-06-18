@extends('sections.app')
    @section('content')
        <div>
            @if (Auth::user()->role_id == 4)
                <div class="userstats-container-small">
                    @livewire('userstats')
                </div>
            @endif
            @if(Auth::user())
                <div class="flex justify-center">
                    @livewire('item-shop')
                </div>
            @endif
        </div>
    @endsection