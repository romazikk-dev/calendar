@php
    $current_full_controller_name = request()->route()->getActionName();
    $current_controller = controllerFromFullClassName($current_full_controller_name);
@endphp

<nav class="main-navbar navbar navbar-expand-sm navbar-light bg-light">
    <!-- <a class="navbar-brand" href="#">
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-house-fill" viewBox="0 0 16 16">
          <path fill-rule="evenodd" d="M8 3.293l6 6V13.5a1.5 1.5 0 0 1-1.5 1.5h-9A1.5 1.5 0 0 1 2 13.5V9.293l6-6zm5-.793V6l-2-2V2.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5z"/>
          <path fill-rule="evenodd" d="M7.293 1.5a1 1 0 0 1 1.414 0l6.647 6.646a.5.5 0 0 1-.708.708L8 2.207 1.354 8.854a.5.5 0 1 1-.708-.708L7.293 1.5z"/>
        </svg>
    </a> -->
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item @if(Route::current()->getName() == 'dashboard.index') active @endif">
                <a class="nav-link" href="{{ route('dashboard.index') }}">{{__('text.home')}}</a>
            </li>
            <li class="nav-item @if($current_controller == 'worker') active @endif">
                <a class="nav-link" href="{{ route('dashboard.worker.index') }}">{{__('text.workers')}}</a>
            </li>
            <li class="nav-item @if($current_controller == 'hall') active @endif">
                <a class="nav-link" href="{{ route('dashboard.hall.index') }}">{{__('text.halls')}}</a>
            </li>
            <li class="nav-item @if(Route::current()->getName() == 'dashboard.template.index') active @endif">
                <a class="nav-link" href="{{ route('dashboard.template.index') }}">{{__('text.templates')}}</a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link" href="#">Events</a>
            </li> -->
            <li class="nav-item @if(Route::current()->getName() == 'dashboard.client.index') active @endif">
                <a class="nav-link" href="{{ route('dashboard.client.index') }}">{{__('text.clients')}}</a>
            </li>
            <li class="nav-item @if(Route::current()->getName() == 'dashboard.bookings.index') active @endif">
                <a class="nav-link" href="{{ route('dashboard.bookings.index') }}">{{__('text.bookings')}}</a>
            </li>
            <!-- <li class="nav-item">
                <a class="nav-link" href="#">Schedule</a>
            </li> -->
            
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="settingsNavbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{__('text.settings')}}
                </a>
                <div class="dropdown-menu" aria-labelledby="settingsNavbarDropdown">
                    
                    <ul class="main-navbar-settings">
                    @foreach(\Setting::getNav() as $nav)
                        <li>
                            @if(!empty($nav['includes']))
                                <a href="{{ $nav['route'] }}">
                                    {{$nav['title']}}
                                    <span class="chevr">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="10" height="10" fill="currentColor" class="bi bi-chevron-double-right" viewBox="0 0 16 16">
                                            <path fill-rule="evenodd" d="M3.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L9.293 8 3.646 2.354a.5.5 0 0 1 0-.708z"/>
                                            <path fill-rule="evenodd" d="M7.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L13.293 8 7.646 2.354a.5.5 0 0 1 0-.708z"/>
                                        </svg>
                                    </span>
                                </a>
                                @if(count($nav['items']) > 0)
                                <ul>
                                    @foreach($nav['items'] as $item)
                                    <li>
                                        <a href="{{$item['route']}}">{{$item['title']}}</a>
                                    </li>
                                    @endforeach
                                </ul>
                                @endif
                            @else
                                <a href="{{ $nav['route'] }}">
                                    {{ $nav['title'] }}
                                </a>
                            @endif
                        </li>
                    @endforeach
                    </ul>
                    
                </div>
            </li>
            
            
            <!-- <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                  Dropdown
                </a>
                <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="#">Action</a>
                    <a class="dropdown-item" href="#">Another action</a>
                    <div class="dropdown-divider"></div>
                    <a class="dropdown-item" href="#">Something else here</a>
                </div>
            </li> -->
        </ul>
        <ul class="navbar-nav ml-auto">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{ Auth::user()->email }}
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{route('dashboard.settings.index')}}">Settings</a>
                    <form method="POST" action="{{ route('logout', ['user']) }}">
                        @csrf
                        <a class="dropdown-item" href="#"
                                onclick="event.preventDefault();
                                            this.closest('form').submit();">
                            {{ __('Log out') }}
                        </a>
                    </form>
                </div>
            </li>
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    {{app()->getLocale()}}
                </a>
                <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdown">
                    <a class="dropdown-item" href="{{ route('dashboard.switch_lang', ['de']) }}">De</a>
                    <a class="dropdown-item" href="{{ route('dashboard.switch_lang', ['en']) }}">En</a>
                </div>
            </li>
        </ul>
    </div>
</nav>