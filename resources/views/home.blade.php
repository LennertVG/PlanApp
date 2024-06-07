@extends('sections.app')
@section('content')
    @if(Auth::user())
        <div class="container">
            <div class="date">
                <h1>{{ date('d/m/Y') }}</h1>
            </div>
            <div class="tasks">
                <h2 class="title">Upcoming tasks</h2>
                <div class="task-list">
                    @foreach ($tasks->filter(function ($task) {
                        return $task->deadline >= now();
                    })->sortBy('deadline') as $task)
                        <div class="task" data-course="{{ $task->course->name }}" data-tasktype="{{ $task->tasktype->name }}" data-name="{{ $task->name }}" data-deadline="{{ $task->deadline }}" data-description="{{ $task->description }}" data-createdby="{{ $task->created_by }}">
                            <p>
                                {{ $task->course->name }}
                            </p>
                            <p class="tasktype" style="background-color: 
                                @if($task->tasktype_id == 1) 
                                    #ef3056; color: white;
                                @elseif($task->tasktype_id == 2) 
                                    #ffe8a3
                                @elseif($task->tasktype_id == 3) 
                                    #9ab87a
                                @endif
                            ">
                                {{ $task->tasktype->name }}
                            </p>
                            <p>
                                {{ $task->name }}
                            </p>
                            <p>
                                {{ $task->deadline }}
                            </p>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- The Modal -->
        <div id="taskModal" class="modal">
            <div class="modal-content">
                <span class="close">&times;</span>
                <h2 id="modalCourse"></h2>
                <p id="modalTaskType"></p>
                <p id="modalTaskName"></p>
                <p id="modalDeadline"></p>
                <p id="modalDescription"></p>
                <p id="modalCreatedBy"></p>
            </div>
        </div>
    @endif
@endsection
