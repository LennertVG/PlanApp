@extends('sections.app')
    @section('content')
            @if(Auth::user())
                <button id="displayButton" class="fixed top-0 right-0 p-6 text-right" onclick="toggleTaskComponent()">Taak toevoegen</button>

                <div id="taskComponent" class="max-w-7xl mx-auto p-6 lg:p-8" style="display: none;">
                    <div class="flex justify-center">
                        @livewire('add-task')
                    </div>
                </div>
            @endif

        </div>
    @endsection