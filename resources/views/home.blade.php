@extends('sections.app')
@section('content')
    @if(Auth::user())
        <?php
            $taskTypes = \App\Models\Tasktype::all();
            $courses = \App\Models\Course::all();
        ?>
        <div class="page-container">
            <div class="outer-container-home">
                <div class="inner-left-container-home">
                    <div class="calendar-home">
                        <h2 id="monthYear"></h2>
                        <div class="days-calendar-home" id="days">
                            <!-- Days will be generated here by JavaScript -->
                        </div>
                    </div>
                </div>
                <div class="inner-right-container-home">
                    <div class="salute-container-home">
                        <div class="salute-home-text">
                            <div class="salute-home-title">
                                <h2>Hallo, {{ Auth::user()->firstname }}!</h2>
                            </div>
                            <div class="salute-home-sentence">
                                <p>Doe zo verder!</p>
                            </div>
                        </div>
                        <div class="salute-home-addtask">
                            <button id="addTaskButton" class="btn btn-success mb-2">Taak toevoegen</button>
                        </div>
                    </div>
                    <div class="tasks-container-home">
                        <div class="row row-cols-1 row-cols-md-3  custom-grid-tasks">
                            {{-- Only returning tasks that have a deadline in the future, sorted by deadline ascending --}}
                            @foreach ($tasks->filter(function ($task) {
                                return $task->deadline >= now();
                            })->sortBy('deadline') as $task)
                            <div class="col">
                                <div class="card task" data-course="{{ $task->course->name }}" data-tasktype="{{ $task->tasktype->name }}" data-name="{{ $task->name }}" data-deadline="{{ $task->deadline }}" data-description="{{ $task->description }}" data-createdby="{{ $task->created_by }}">
                                    <div class="card-body">
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
                            @endforeach
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
                </div>
            </div>  
        </div>  
            <script>
                function generateCalendar() {
                    const today = new Date();
                    const currentMonth = today.getMonth();
                    const currentYear = today.getFullYear();
                    const currentDate = today.getDate();

                    const monthYear = document.getElementById('monthYear');
                    monthYear.innerText = today.toLocaleString('default', { month: 'long' }) + ' ' + currentYear;

                    const daysContainer = document.getElementById('days');
                    daysContainer.innerHTML = '';

                    const firstDay = new Date(currentYear, currentMonth, 1).getDay();
                    const daysInMonth = new Date(currentYear, currentMonth + 1, 0).getDate();

                    // Add empty divs for days of the previous month
                    for (let i = 0; i < firstDay; i++) {
                        daysContainer.appendChild(document.createElement('div'));
                    }

                    // Add divs for each day of the current month
                    for (let day = 1; day <= daysInMonth; day++) {
                        const dayDiv = document.createElement('div');
                        dayDiv.innerText = day;
                        dayDiv.classList.add('day');
                        if (day === currentDate) {
                            dayDiv.classList.add('today');
                        }
                        daysContainer.appendChild(dayDiv);
                    }
                }
                generateCalendar();
            </script>
    @endif
@endsection
