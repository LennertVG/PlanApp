@extends('sections.app')
@section('content')
@if(Auth::user())
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="titlecontainer-tasks">
                <h2>Hieronder alle taken van {{ Auth::user()->name }}</h2>
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
                                            <th scope="row">Naam:</th>
                                            <td>{{ $task->name }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Vak:</th>
                                            <td>{{ $task->course->name }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Deadline:</th>
                                            <td>{{ $task->formatted_deadline }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Beschrijving:</th>
                                            <td>{{ $task->description }}</td>
                                        </tr>
                                        <tr>
                                            <th scope="row">Gecreëerd door:</th>
                                            <td>{{ $task->created_by }}</td>
                                        </tr>
                                    </tbody>
                                </table>
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
