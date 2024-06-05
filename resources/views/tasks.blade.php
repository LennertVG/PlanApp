@extends('sections.app')
    @section('content')
            @if(Auth::user())
                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                        <h1>{{ $title }}</h1>
                        @foreach ($allUsers as $user)
                            <h2>{{ $user->name }}</h2>
                            <ul>
                                @foreach ($user->tasks as $task)
                                    <li>
                                        <strong>Task Name:</strong> {{ $task->name }}<br>
                                        <strong>Course ID:</strong> {{ $task->course_id }}<br>
                                        <strong>Deadline:</strong> {{ $task->deadline }}<br>
                                        <strong>Description:</strong> {{ $task->description }}<br>
                                        <strong>Created By:</strong> {{ $task->created_by }}<br>
                                        <strong>Task Type ID:</strong> {{ $task->tasktype_id }}<br>
                                    </li>
                                @endforeach
                            </ul>
                        @endforeach
                        </div>
                    </div>
                </div>
            @endif
    @endsection