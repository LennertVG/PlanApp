@extends('sections.app')
@section('content')
@if(Auth::user())
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="titlecontainer-tasks">
                <h2>Hieronder alle taken van: <strong> {{ Auth::user()->firstname }}</strong></h2>
            </div>
            <div class="row row-cols-1 row-cols-md-3 g-4 custom-grid-tasks">
                @foreach ($tasks as $task)
                <div class="col">
                    <div class="card">
                        <div class="card-body">
                            <h3 class="card-title">{{ $task->tasktype->name}}</h3>
                            <p class="card-text">
                                <table class="table task-cards">
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
                                            <th class="table-head" scope="row">Gecreëerd door</th>
                                            <td>{{ $task->created_by }}</td>
                                        </tr>
                                        <tr>
                                            <th class="table-head" scope="row">Upload</th>
                                            <td><a href="{{ $task->uploadPath }}">{{ $task->uploadPath }}</a></td>
                                        </tr>
                                    </tbody>
                                </table>
                                @if ($task->uploadPath == 'NULL' || $task->uploadPath == NULL || !$task->uploadPath)
                                <form method="POST" action="/upload-task-file" enctype="multipart/form-data">
                                    @csrf
                                    <input type="hidden" name="task_id" value="{{ $task->id }}">
                                    <input class="form-control form-control-sm" id="formFileSm" type="file" name="task_file">
                                    <button type="submit" class="btn btn-secondary btn-sm mt-2">Upload File</button>                         
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
                            {{-- button to confirm completion --}}
                                <form method="POST" action="{{ route('task.confirmCompletion') }}">
                                    @csrf
                                    <input type="hidden" name="task_id" value="{{ $task->task_id }}">
                                    <button type="submit" class="btn btn-outline-success mt-2">Taak voltooid</button>
                                </form>
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
