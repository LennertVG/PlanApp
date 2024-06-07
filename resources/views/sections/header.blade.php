<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan App</title>
    @livewireStyles
    {{-- Bootstrap v.5.3 link --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href={{asset('css/style.css')}}>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css" integrity="sha512-SnH5WK+bZxgPHs44uWIX+LLJAJ9/2PkPKZ5QiAj6Ta86w+fsb2TkcmfRyVX3pBnMFcV7oQPJkl9QevSCWr3W6A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script>
        function toggleTaskComponent() {
            const taskComponent = document.getElementById('taskComponent');
            const displayButton = document.getElementById('displayButton');

            if (taskComponent.style.display === 'none') {
                taskComponent.style.display = 'block';
                displayButton.style.display = 'none';
            } else {
                taskComponent.style.display = 'none';
                displayButton.style.display = 'block';
            }
        }
    </script>
</head>
<body>
    <header>
        <nav class="navbar navbar-expand-lg bg-body-tertiary">
            <div class="container-fluid">
                <a class="navbar-brand" href="/">PlanApp</a>
                
                <div class="d-block d-lg-none">
                    @livewire('userstats')
                </div>
                               
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav ms-auto">
                        <li class="nav-item dropdown" style="padding-top: 1.1rem;">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Taken
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="add-task">Toevoegen</a></li>
                                <li><a class="dropdown-item" href="tasks-by-user">Taken</a></li>
                            </ul>
                        </li>
                    @if(Auth::guest())
                        <li class="nav-item dropdown" style="padding-top: 1.1rem;">
                            <a class="nav-link dropdown-toggle" href="/account" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Account
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/login">Login</a></li>
                                <li><a class="dropdown-item" href="/register">Register</a></li>
                            </ul>
                        </li>
                    @else
                        <li class="nav-item dropdown" style="padding-top: 1.1rem;">
                            <a class="nav-link dropdown-toggle" href="/account" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="/profile">Settings</a></li>
                                <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button class="dropdown-item" type="submit">Logout</button>
                                </form>
                            </ul>
                        </li>
                        <div class="d-none d-lg-block">
                            @livewire('userstats')
                        </div>
                    @endif

                </ul>
                </div>
            </div>
            </nav>
    </header>