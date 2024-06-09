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
                <h2 class="title">Upcoming tasks</h2>
                <button id="addTaskButton" class="btn btn-success">Add Task</button>
                <div class="task-list">
                    @foreach ($tasks->filter(function ($task) {
                        return $task->deadline >= now();
                    })->sortBy('deadline') as $task)
                        <div class="task" data-course="{{ $task->course->name }}" data-tasktype="{{ $task->tasktype->name }}" data-name="{{ $task->name }}" data-deadline="{{ $task->deadline }}" data-description="{{ $task->description }}" data-createdby="{{ $task->created_by }}">
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
                    @endforeach
                </div>
                <div id="taskForm" style="display: none; background-color: #2c3e50; padding: 20px; border-radius: 10px; color: white; width: 500px; position: fixed; z-index: 1000; top: 50%; left: 50%; transform: translate(-50%, -50%); box-shadow: 0 4px 8px rgba(0,0,0,0.2);">
                    <button id="hideButton" style="height: 20px; width: 20px; float: right; margin-top: 0; padding: 0;"><i class="fa-solid fa-circle-xmark" style="color: #ff0000;"></i></button>
                    <form method="POST" action="{{ route('task.store') }}">
                        @csrf
                        <div class="mt-4">
                            <label for="name" style="display: block; margin-bottom: 10px; font-weight: bold;">Task Name</label>
                            <input id="name" name="name" class="block mt-1 w-full" type="text" style="width: 100%; padding: 10px; border-radius: 5px; border: none; color: black; margin-bottom: 20px;">
                            
                            <label for="description" style="display: block; margin-bottom: 10px; font-weight: bold;">Description</label>
                            <textarea id="description" name="description" class="block mt-1 w-full" style="width: 100%; padding: 10px; border-radius: 5px; border: none; color: black; height: 100px; margin-bottom: 20px;"></textarea>
                            
                            <label for="deadline" style="display: block; margin-bottom: 10px; font-weight: bold;">Deadline</label>
                            <input id="deadline" name="deadline" class="block mt-1 w-full" type="date" style="width: 100%; padding: 10px; border-radius: 5px; border: none; color: black; margin-bottom: 20px;">
                            
                            <label for="course" style="display: block; margin-bottom: 10px; font-weight: bold;">Course</label>
                            <select id="course" name="course" class="block mt-1 w-full" style="width: 100%; padding: 10px; border-radius: 5px; border: none; color: black; margin-bottom: 20px;">
                                @foreach ($courses as $course)
                                    <option value="{{ $course['id'] }}">{{ $course['name'] }}</option>
                                @endforeach
                            </select>

                            <label for="taskType" style="display: block; margin-bottom: 10px; font-weight: bold;">Task Type</label>
                            <select id="taskType" name="taskType" class="block mt-1 w-full" style="width: 100%; padding: 10px; border-radius: 5px; border: none; color: black; margin-bottom: 20px;">
                                @foreach ($taskTypes as $taskType)
                                    <option value="{{ $taskType['id'] }}">{{ $taskType['name'] }}</option>
                                @endforeach
                            </select>
                            
                            <input type="hidden" name="user_id" value="{{ auth()->user()->id }}">
                            <input type="hidden" name="completed" value="0">
                            <input type="hidden" name="created_at" value="{{ date('Y-m-d H:i:s') }}">
                            <input type="hidden" name="createdBy" value="{{ auth()->user()->role_id }}">
                            
                            <button type="submit" class="btn btn-primary" style="width: 100%; padding: 10px; border-radius: 5px; background-color: #2980b9; border: none; font-size: 16px; font-weight: bold; color: white;">Add Task</button>
                        </div>
                    </form>
                </div>
            </div>
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
            </div>
        </div>
    @endif
@endsection