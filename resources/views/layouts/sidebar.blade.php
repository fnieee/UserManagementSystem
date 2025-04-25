<nav id="sidebar" class="col-md-3 col-lg-2 d-md-block bg-dark sidebar collapse">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link text-white {{ request()->is('users*') ? 'active' : '' }}" href="{{ route('users.index') }}">
                    Users
                </a>
            </li>
        </ul>
    </div>
</nav>
