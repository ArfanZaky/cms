<div class="navbar-bg"></div>
<nav class="navbar navbar-expand-lg main-navbar justify-content-end">
    <form class="form-inline mr-auto">
        <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a>
            </li>
            <li><a href="#" data-toggle="search" class="nav-link nav-link-lg d-sm-none"><i
                        class="fas fa-search"></i></a></li>
        </ul>
      
    </form>
    <ul class="navbar-nav navbar-right ">
        <li class="dropdown"><a href="#" data-toggle="dropdown"
                class="nav-link dropdown-toggle nav-link-lg nav-link-user">
                <img alt="image" src="{{ asset('assets/img/avatar/avatar-1.png') }}" class="rounded-circle mr-1">
                
                <div class="d-sm-none d-lg-inline-block">
                    @if (Auth::check())
                        {{ Auth::user()->name }}
                    @endif
                </div>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <div class="dropdown-title">
                    Auto logout in <b id="countdown"></b> 
                </div>
                @php
                    $current = \Request::query();
                    $route = !empty($current) ? url()->full().'&dev=true' : url()->full().'?dev=true';
                    $route = (request()->has('dev')) ? str_replace('&dev=true', '', $route) : $route;
                    $route = (request()->has('dev')) ? str_replace('?dev=true', '', $route) : $route;
                @endphp
                <a href="{{ $route }}" class="dropdown-item has-icon">
                    <i class="fas fa-cogs"></i>
                    @if (request()->has('dev'))
                        User Mode
                    @else
                        Administrator Mode
                    @endif
                </a>
                <a href="{{ route('engine') }}"
                class="dropdown-item has-icon">
                    <i class="fas fa-bolt"></i> Activities
                </a>
                <a href="{{ route('settings') }}"
                 class="dropdown-item has-icon">
                    <i class="fas fa-cog"></i> Settings
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf

                    <a class="dropdown-item has-icon text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); this.closest('form').submit();">
                        <i class="fas fa-sign-out-alt"></i> {{ __('Logout') }}
                    </a>
                </form>
            </div>
        </li>
    </ul>
</nav>
