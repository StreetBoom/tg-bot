@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="{{ route('home') }}">Telegram Bot Project</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('dashboard') }}">
                                <img src="{{ Auth::user()->avatar }}" alt="Avatar" class="rounded-circle" width="30" height="30">
                                {{ Auth::user()->name }}
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="{{ route('logout') }}"
                               onclick="event.preventDefault();
                                         document.getElementById('logout-form').submit();">
                                Logout
                            </a>
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </li>
                    @else
{{--
--}}
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <h1 class="text-center">Welcome to Telegram Bot Project</h1>
    <p class="text-center">Here is the functionality of the bot:</p>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">Feature 1: Description of feature 1</li>
        <li class="list-group-item">Feature 2: Description of feature 2</li>
        <li class="list-group-item">Feature 3: Description of feature 3</li>
        <li class="list-group-item">Feature 4: Description of feature 4</li>
        <li class="list-group-item">Feature 5: Description of feature 5</li>
    </ul>
@endsection
