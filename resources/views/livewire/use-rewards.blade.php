@extends('sections.app')
@section('content')
@if(Auth::user())
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                {{-- Display session success message --}}
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                @foreach ($studentsByGroupByCourse as $teacher)

                    <div class="titlecontainer-tasks-teacher">
                        <div class="titlecontainer-teacher-left">
                            Welkom, professor {{$teacher->name}}
                        </div>
                        <div class="titlecontainer-teacher-right">
                            REWARDS INNEN
                        </div>
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
                                                            <div class="accordion" id="accordion-group-{{ $groupIndex }}">
                                                                @foreach ($group->users as $student)
                                                                    <div class="accordion-item">
                                                                        <h2 class="accordion-header" id="heading-student-{{ $student->id }}">
                                                                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-student-{{ $student->id }}" aria-expanded="false" aria-controls="collapse-student-{{ $student->id }}">
                                                                                {{ $student->firstname }} {{ $student->name }}
                                                                            </button>
                                                                        </h2>
                                                                        <div id="collapse-student-{{ $student->id }}" class="accordion-collapse collapse" aria-labelledby="heading-student-{{ $student->id }}" data-bs-parent="#accordion-group-{{ $groupIndex }}">
                                                                            <div class="accordion-body">
                                                                                <?php
                                                                                    $studentWithRewards = \App\Models\User::with('rewards')->find($student->id);
                                                                                    $rewards = $studentWithRewards->rewards;
                                                                                ?>
                                                                                <ul>
                                                                                    @foreach ($rewards as $reward)
                                                                                    @if($reward->pivot->amount != 0)
                                                                                        <li class="reward-item mb-2">
                                                                                            <button type="button" class="btn btn-dark btn-sm" data-bs-toggle="modal" data-bs-target="#rewardModal-{{ $reward->id }}">
                                                                                                View Description
                                                                                            </button>
                                                                                            <form method="POST" action="/use-reward" style="display:inline;">
                                                                                                @csrf
                                                                                                <input type="hidden" name="reward_id" value="{{ $reward->id }}">
                                                                                                <input type="hidden" name="student_id" value="{{ $student->id }}">
                                                                                                <button class="btn custom-reward-btn mr-2" type="submit">Gebruik reward</button>
                                                                                            </form>
                                                                                            <i class="fa-solid fa-{{ $reward->iconPath }}" style="margin-left: 5px"></i> - {{ $reward->name }} : {{ $reward->pivot->amount }}
                                                                                        </li>
                                                                                    @endif

                                                                                        {{-- Reward Modal --}}
                                                                                        <div class="modal fade" id="rewardModal-{{ $reward->id }}" tabindex="-1" aria-labelledby="rewardModalLabel-{{ $reward->id }}" aria-hidden="true">
                                                                                            <div class="modal-dialog">
                                                                                                <div class="modal-content">
                                                                                                    <div class="modal-header">
                                                                                                        <h5 class="modal-title" id="rewardModalLabel-{{ $reward->id }}">{{ $reward->name }}</h5>
                                                                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                                                                    </div>
                                                                                                    <div class="modal-body">
                                                                                                        {{ $reward->description }}
                                                                                                    </div>
                                                                                                    <div class="modal-footer">
                                                                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                                                                    </div>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    @endforeach
                                                                                </ul>
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
