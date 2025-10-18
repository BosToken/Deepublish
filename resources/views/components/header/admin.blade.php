@props(['user' => null, 'class' => 'navbar navbar-expand-lg navbar-light bg-light'])

<nav class="{{ $class }}">
    <div class="container-fluid">
        <a class="navbar-brand fw-bold" href="#">Hi, {{ $user->name }}</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="taskLink" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Task
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="taskLink">
                            <li><a class="dropdown-item" href="{{ route('task.admin') }}">List</a></li>
                            <li><a class="dropdown-item" href="{{ route('task.create.admin') }}">Add</a></li>
                        </ul>
                    </li>
                </ul>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="userLink" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            User
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userLink">
                            <li><a class="dropdown-item" href="{{ route('user.admin') }}">List</a></li>
                            <li><a class="dropdown-item" href="{{ route('user.create.admin') }}">Add</a></li>
                        </ul>
                    </li>
                </ul>
            </ul>

            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button"
                        data-bs-toggle="dropdown" aria-expanded="false">
                        Menu
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdownMenuLink">
                        <li><a class="dropdown-item text-danger" href="{{ route('logout') }}">Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
