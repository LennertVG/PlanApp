@extends('sections.app')
    @section('content')
        <div>
            @if(Auth::user())
                <div class="flex justify-center">
                    @livewire('use-rewards')
                </div>
            @endif
        </div>
    @endsection