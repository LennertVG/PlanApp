@extends('sections.app')
@section('content')
    @if(Auth::user())

        <?php
            $taskTypes = \App\Models\Tasktype::all();
            $courses = \App\Models\Course::all();
        ?>

        <div class="page-container">
                    @if (Auth::user()->role_id == 4)
                        <div class="userstats-container-small">
                            @livewire('userstats')
                        </div>
                    @endif
            <div class="outer-container-home">
                <div class="inner-left-container-home">
                    <div class="calendar-home">
                        <h2 id="monthYear"></h2>
                        <div class="days-calendar-home" id="days">
                            <!-- Days will be generated here by JavaScript -->
                        </div>
                    </div>
                    <div class="planny-container-home">
                        <img src="{{ asset('mascotte.svg') }}" alt="Planny"/>
                    </div>
                </div>

                <div class="inner-right-container-home">
                    <div class="salute-container-home">
                        <div class="salute-home-text">

                            <div class="salute-home-title">
                                <h2>Hallo, {{ Auth::user()->firstname }}!</h2>
                            </div>

                            @if (Auth::user()->role_id == 4)
                            <div class="salute-home-sentence">
                                <p>Doe zo verder!</p>
                            </div>
                            @endif

                            @if (Auth::user()->role_id == 3)
                            <div class="salute-home-sentence">
                                <p>Lesgeven is een roeping!</p>
                            </div>
                            @endif

                        </div>
                        <div class="salute-home-addtask">
                            <button id="addTaskButton" class="btn custom-reward-btn mb-2">Taak toevoegen</button>
                        </div>
                    </div>

                    <div class="tasks-container-home">
                        <div class="row row-cols-1 row-cols-md-3 g-4 custom-grid-tasks">
                            {{-- Only returning tasks that have a deadline in the future and are not completed, sorted by deadline ascending --}}
                            @foreach ($tasks->filter(function ($task) {
                                return $task->deadline >= now() && $task->pivot->completed !== 1 && $task->pivot->completed !== 2;
                            })->sortBy('deadline') as $task)
                                <div class="col">

                                    <div class="card task card-home" style="height: 100%" 
                                         data-course="{{ $task->course->name }}" 
                                         data-tasktype="{{ $task->tasktype->name }}" 
                                         data-name="{{ $task->name }}" 
                                         data-deadline="{{ $task->deadline }}" 
                                         data-description="{{ $task->description }}" 
                                         data-createdby="{{ $task->created_by }}"
                                         data-taskid="{{ $task->task_id }}">

                                        <div class="card-body"> 
                                            <div class="task-card-outer">

                                                <div class="task-card-left">
                                                    <img class="task-card-icon" src="{{ asset('assets/icons/'.$task->course->iconPath) }}" alt="Planny"/>
                                                </div>

                                                <div class="task-card-right">
                                                    <div class="card-content-container">
                                                        <div class="card-content-left">
                                                            <h3>{{ $task->course->name }}</h3>
                                                            <div class="card-text">
                                                                <p>{{ $task->name }}</p>
                                                                <p>{{ $task->formatted_deadline }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="card-content-right">
                                                            <div class="tasktype-emblem" style="background-color: 
                                                                @if($task->tasktype_id == 1) 
                                                                    #ef3056; color: white;
                                                                @elseif($task->tasktype_id == 2) 
                                                                    #ffe8a3
                                                                @elseif($task->tasktype_id == 3) 
                                                                    #9ab87a
                                                                @endif">
                                                                {{ $task->tasktype->name }}
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div id="taskModal" class="modal">
                            <div class="modal-content">
                                <span class="close">&times;</span>
                                <h2 id="modalCourse"></h2>
                                <p id="modalTaskType"></p>
                                <p id="modalTaskName"></p>
                                <p id="modalDeadline"></p>
                                <p id="modalDescription"></p>

                                <form method="POST" action="/upload-task-file" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="task_id" id="hiddenTaskId" value="task_id">
                                    <input class="form-control form-control-sm mt-1" id="formFileSm" type="file" name="task_file">
                                    <button type="submit" class="custom-reward-btn-home w-100 mt-2">Taak indienen</button>

                                    @if ($errors->any())
                                        <div class="alert alert-danger">
                                            <ul>
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif

                                </form>
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
                                        <input id="deadline" name="deadline" class="form-control mb-2" type="datetime-local">

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

                                        <button type="submit" class="custom-reward-btn-home w-100 mt-1">Taak toevoegen</button>
                                    </div>
                                </form>

                            </div>
                        </div>
                    </div>
                </div>
            </div>  
        </div>

    @endif
@endsection
