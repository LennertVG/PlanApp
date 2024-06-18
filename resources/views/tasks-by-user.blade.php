@extends('sections.app')
@section('content')
@if(Auth::user())
<div class="container">
    @if (Auth::user()->role_id == 4)
        <div class="userstats-container-small">
            @livewire('userstats')
        </div>
    @endif
    <div class="row">
        <div class="col-md-12">
            <div class="titlecontainer-tasks">
                <h2>Taken van <strong> {{ Auth::user()->firstname }}</strong></h2>
            </div>
            <div class="row row-cols-1 row-cols-md-3 g-4 custom-grid-tasks">
                @foreach ($tasks as $task)
                {{--  --}}
                <div class="col">
                    <div class="card" style="height: 100%;">
                        <div class="card-body">
                        <div style="display: flex; justify-content: space-between">
                            <h3 class="tasktype-emblem2" style="background-color:
                                @if($task->tasktype_id == 1)
                                    #ef3056; color: white;
                                @elseif($task->tasktype_id == 2)
                                    #ffe8a3; color: white;
                                @elseif($task->tasktype_id == 3)
                                    #9ab87a; color: white;
                                @endif
                                ">
                                {{ $task->tasktype->name }}
                            </h3>
                            @if($task->pivot->completed == 2) <span style="height: 100%;" class="badge bg-success">Compleet</span> @endif
                            @if($task->pivot->completed == 1) <span style="height: 100%;" class="badge bg-secondary">Nog te evalueren</span> @endif
                            @if($task->pivot->completed == 0) <span style="height: 100%;" class="badge bg-danger">Nog niet gestart</span> @endif
                        </div>

                            <p class="card-text">
                                <table class="table task-cards" style="height: auto">
                                    <tbody>
                                        <tr>
                                            <th class="table-head" scope="row">Naam</th>
                                            <td>{{ $task->name }}</td>
                                        </tr>
                                        <tr>
                                            <th class="table-head" scope="row">Vak</th>
                                            <td>{{ $task->course->name }}</td>
                                        </tr>
                                        <tr>
                                            <th class="table-head" scope="row">Deadline</th>
                                            <td>{{ $task->formatted_deadline }}</td>
                                        </tr>
                                        <tr>
                                            <th class="table-head" scope="row">Beschrijving</th>
                                            <td>
                                                <div class="scrollarea">
                                                {{ $task->description }}
                                                </div>
                                            </td>
                                        </tr>
                                        <tr>
                                            <th class="table-head" scope="row">Upload</th>
                                            <td>
                                                @if($task->uploadPath)
                                                    <div class="file-upload">
                                                        <a href="{{ $task->uploadPath }}" target="_blank">Open File</a>
                                                        <form method="POST" action="/delete-task-file" style="display: inline;">
                                                            @csrf
                                                            <input type="hidden" name="task_id" value="{{ $task->task_id }}">
                                                            <button type="submit" class="btn btn-danger btn-sm">x</button>
                                                        </form>
                                                    </div>
                                                @endif
                                            </td>
                                        </tr>

                                    </tbody>
                                </table>
                                @if ($task->uploadPath == 'NULL' || $task->uploadPath == NULL || !$task->uploadPath)
                                <form method="POST" action="/upload-task-file" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="task_id" value="{{ $task->task_id }}">
                                    <input class="form-control form-control-sm" id="formFileSm" type="file" name="task_file">
                                    <button type="submit" class="custom-reward-btn-home w-100 mt-2">Bestand uploaden</button>                         
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
                                @endif                               
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endif
@endsection
