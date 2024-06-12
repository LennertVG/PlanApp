@extends('sections.app')
    @section('content')
        <div>
            @if(Auth::user())
                <div class="flex justify-center">
                    @livewire('item-shop')
                </div>
            @endif
        </div>
    @endsection