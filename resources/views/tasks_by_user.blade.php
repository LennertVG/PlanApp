@extends('sections.app')
    @section('content')
            @if(Auth::user())

                <div class="container">
                    <div class="row">
                        <div class="col-md-12">
                        <h2>Tasks by user</h2>
                        @foreach ($tasks as $task)
                            <ul>
                                <li>
                                    <strong>Naam:</strong> {{ $task->name }}<br>
                                    <strong>Vak:</strong> {{ $task->course->name }}<br>
                                    <strong>Deadline:</strong> {{ $task->formatted_deadline }}<br>
                                    <strong>Beschrijving:</strong> {{ $task->description }}<br>
                                    <strong>Gecreëerd door:</strong> {{ $task->created_by }}<br>
                                    <strong>Taaktype ID:</strong> {{ $task->tasktype_id }}<br>
                                    <form action="{{ route('task.confirmCompletion', $task->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="task_id" value="{{ $task->id }}">
                                        <button type="submit">Markeren als voltooid</button>
                                    </form>
                                </li>
                            </ul>
                        @endforeach
                        </div>
                    </div>
                </div>
            @endif
    @endsection