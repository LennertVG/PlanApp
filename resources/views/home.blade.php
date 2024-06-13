@extends('sections.app')
@section('content')
    @if(Auth::user())

        <?php
            $taskTypes = \App\Models\Tasktype::all();
            $courses = \App\Models\Course::all();
        ?>

        <div class="container">
            <div class="date">
                <h1>{{ date('d/m/Y') }}</h1>
            </div>
            <div class="tasks">
                <h2 class="title">Deadlines</h2>
                
                <button id="addTaskButton" class="btn btn-success mb-2">Taak toevoegen</button>

                <div class="task-list">
                    @foreach ($tasks->filter(function ($task) {
                        return $task->deadline >= now();
                    })->sortBy('deadline') as $task)
                        <div class="task" data-id="{{ $task->id }}" data-course="{{ $task->course->name }}" data-tasktype="{{ $task->tasktype->name }}" data-name="{{ $task->name }}" data-deadline="{{ $task->deadline }}" data-description="{{ $task->description }}" data-createdby="{{ $task->created_by }}">
                            <p>{{ $task->course->name }}</p>
                            <p class="tasktype" style="background-color: 
                                @if($task->tasktype_id == 1) 
                                    #ef3056; color: white;
                                @elseif($task->tasktype_id == 2) 
                                    #ffe8a3
                                @elseif($task->tasktype_id == 3) 
                                    #9ab87a
                                @endif">
                                {{ $task->tasktype->name }}
                            </p>
                            <p>{{ $task->name }}</p>
                            <p>{{ $task->deadline }}</p>
                        </div>

                        <div id="taskModal" class="modal">

                            <div class="modal-content">
                            <span class="close">&times;</span>
                            <h2 id="modalCourse"></h2>
                            <p id="modalTaskType"></p>
                            <p id="modalTaskName"></p>
                            <p id="modalDeadline"></p>
                            <p id="modalDescription"></p>
                            <p id="modalCreatedBy"></p>

                            {{-- <form method="POST" action="/upload-task-file" enctype="multipart/form-data">
                                @csrf
                                <input type="hidden" name="task_id" value="{{ $task->id }}">
                                <input class="form-control form-control-sm mt-2" id="formFileSm" type="file" name="task_file">
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <button id="turnInTaskButton" class="btn btn-success w-100 mt-2">Taak indienen</button>
                            </form> --}}

                            <form method="POST" action="{{ route('task.markInProgress', $task->task_id) }}">
                                @csrf
                                <input type="hidden" name="task_id" value="{{ $task->task_id }}">
                                <button type="submit" class="btn btn-primary w-100 mt-2">Taak indienen</button>
                            </form>

                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <div id="taskForm" class="modal">
            <div class="modal-content">
                <span class="close" id="taskFormClose">&times;</span>
                <form method="POST" action="{{ route('task.store') }}">
                    @csrf
                    <div class="form-group">
                        <label for="name">Taak naam</label>
                        <input id="name" name="name" class="form-control mb-2" type="text">

                        <label for="description">Beschrijving</label>
                        <textarea id="description" name="description" class="form-control mb-2"></textarea>

                        <label for="deadline">Deadline</label>
                        <input id="deadline" name="deadline" class="form-control mb-2" type="date">

                        <label for="course">Vak</label>
                        <select id="course" name="course" class="form-control mb-2">
                            @foreach ($courses as $course)
                                <option value="{{ $course['id'] }}">{{ $course['name'] }}</option>
                            @endforeach
                        </select>

                        <label for="taskType">Type taak</label>
                        <select id="taskType" name="taskType" class="form-control mb-2">
                            @foreach ($taskTypes as $taskType)
                                <option value="{{ $taskType['id'] }}">{{ $taskType['name'] }}</option>
                            @endforeach
                        </select>

                        <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                        <input type="hidden" name="completed" value="0">
                        <input type="hidden" name="created_at" value="{{ date('Y-m-d H:i:s') }}">
                        <input type="hidden" name="createdBy" value="{{ auth()->user()->role_id }}">

                        <button type="submit" class="btn btn-primary w-100">Taak toevoegen</button>
                    </div>
                </form>
            </div>
        </div>
        
        
    @endif
@endsection
