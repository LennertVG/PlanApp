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

    <script>
        // Get the modal
        var modal = document.getElementById("taskModal");

        // Get the <span> element that closes the modal
        var span = document.getElementsByClassName("close")[0];

        // Get all task elements
        var tasks = document.getElementsByClassName("task");

        // When the user clicks on a task, open the modal 
        for (var i = 0; i < tasks.length; i++) {
            tasks[i].onclick = function() {
                modal.style.display = "block";
                document.getElementById("modalCourse").innerText = this.dataset.course;
                document.getElementById("modalTaskType").innerText = this.dataset.tasktype;
                document.getElementById("modalTaskName").innerText = this.dataset.name;
                document.getElementById("modalDeadline").innerText = this.dataset.deadline;
                document.getElementById("modalDescription").innerText = this.dataset.description;
                document.getElementById("modalCreatedBy").innerText = "Created by: " + this.dataset.createdby;
            }
        }

        // When the user clicks on <span> (x), close the modal
        span.onclick = function() {
            modal.style.display = "none";
        }

        // When the user clicks anywhere outside of the modal, close it
        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }
    </script>
@endsection
