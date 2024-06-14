@extends('sections.app')
@section('content')
@if(Auth::user())
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <?php
                    {{-- dd($studentsByGroupByCourse); --}}
                ?>
                @foreach ($studentsByGroupByCourse as $teacher)
                    <div class="titlecontainer-tasks-teacher">
                        <h2>Welkom, professor {{ $teacher->name }}</h2>
                    </div>
                    <div class="accordion" id="accordion-teacher-{{ $teacher->id }}">
                        @foreach ($teacher->courses as $courseIndex => $course)
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="heading-course-{{ $course->id }}">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-course-{{ $course->id }}" aria-expanded="false" aria-controls="collapse-course-{{ $course->id }}">
                                        {{ $course->name }}
                                    </button>
                                </h2>
                                <div id="collapse-course-{{ $course->id }}" class="accordion-collapse collapse" aria-labelledby="heading-course-{{ $course->id }}" data-bs-parent="#accordion-teacher-{{ $teacher->id }}">
                                    <div class="accordion-body">
                                        <div class="accordion" id="accordion-course-{{ $course->id }}">
                                            @foreach ($course->groups as $groupIndex => $group)
                                                <div class="accordion-item">
                                                    <h2 class="accordion-header" id="heading-group-{{ $course->id }}-{{ $groupIndex }}">
                                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-group-{{ $course->id }}-{{ $groupIndex }}" aria-expanded="false" aria-controls="collapse-group-{{ $course->id }}-{{ $groupIndex }}">
                                                            <div class="klas-titel">{{ $group->grade }}{{ $group->class }}</div>
                                                        </button>
                                                    </h2>
                                                    <div id="collapse-group-{{ $course->id }}-{{ $groupIndex }}" class="accordion-collapse collapse" aria-labelledby="heading-group-{{ $course->id }}-{{ $groupIndex }}" data-bs-parent="#accordion-course-{{ $course->id }}">
                                                        <div class="accordion-body">
                                                            @foreach ($group->users as $student)
                                                                <p>{{ $student->firstname }} {{ $student->name }}</p>
                                                                <?php
                                                                    $studentWithTasks = \App\Models\User::with(['tasks' => function ($query) use ($course) {
                                                                        $query->where('course_id', $course->id);
                                                                    }])->find($student->id);

                                                                    $tasks = $studentWithTasks->tasks->map(function ($task) {
                                                                        $task->formatted_deadline = \Carbon\Carbon::parse($task->deadline)->format('d-m-Y');
                                                                        return $task;
                                                                    });
                                                                ?>
                                                                <ul>
                                                                    @foreach ($tasks as $task)
                                                                        <li>
                                                                            {{ $task->name }} - Deadline: {{ $task->formatted_deadline }}
                                                                            @if($task->pivot->uploadPath)
                                                                                - Uploaded File: <a href="{{ asset('storage/' . $task->pivot->uploadPath) }}">Download</a>
                                                                            @endif
                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            @endforeach
                                                        </div>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            </div>
        </div>
    </div>
@endif
@endsection