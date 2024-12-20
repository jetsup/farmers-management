<nav class="sidebar sidebar-offcanvas" id="sidebar">
    <div class="sidebar-brand-wrapper d-none d-lg-flex align-items-center justify-content-center fixed-top">
        <a class="sidebar-brand brand-logo" href="{{ route('dashboard-admin') }}"><img src="assets/images/logo.svg"
                alt="logo" /></a>
        <a class="sidebar-brand brand-logo-mini" href="{{ route('dashboard-admin') }}"><img
                src="assets/images/logo-mini.svg" alt="logo" /></a>
    </div>
    <ul class="nav">
        <li class="nav-item profile">
            <div class="profile-desc">
                <div class="profile-pic">
                    <div class="count-indicator">
                        <img class="img-xs rounded-circle " src="assets/images/faces/profile-picture-avartar.jpg"
                            alt="">
                        <span class="count bg-success"></span>
                    </div>
                    @auth
                        <div class="profile-name">
                            <h5 class="mb-0 font-weight-normal">{{ auth()->user()->name }}</h5>
                            <span>{{ ucwords(auth()->user()->user_type) }} Member</span>
                        </div>
                    @endauth
                </div>
                <a href="#" id="profile-dropdown" data-toggle="dropdown"><i class="mdi mdi-dots-vertical"></i></a>
                <div class="dropdown-menu dropdown-menu-right sidebar-dropdown preview-list"
                    aria-labelledby="profile-dropdown">
                    <a href="#" class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                <i class="mdi mdi-settings text-primary"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject ellipsis mb-1 text-small">Account settings</p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                <i class="mdi mdi-onepassword  text-info"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject ellipsis mb-1 text-small">Change Password</p>
                        </div>
                    </a>
                    <div class="dropdown-divider"></div>
                    <a href="#" class="dropdown-item preview-item">
                        <div class="preview-thumbnail">
                            <div class="preview-icon bg-dark rounded-circle">
                                <i class="mdi mdi-calendar-today text-success"></i>
                            </div>
                        </div>
                        <div class="preview-item-content">
                            <p class="preview-subject ellipsis mb-1 text-small">To-do list</p>
                        </div>
                    </a>
                </div>
            </div>
        </li>
        <li class="nav-item nav-category">
            <span class="nav-link">Navigation</span>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('milk-reception-admin') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-speedometer"></i>
                </span>
                <span class="menu-title">Milk Reception</span>
            </a>
        </li>
        <hr>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('dashboard-admin') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-speedometer"></i>
                </span>
                <span class="menu-title">Dashboard</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('farmers-admin') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-nature-people"></i>
                </span>
                <span class="menu-title">Farmers</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('records-admin') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-file-chart"></i>
                </span>
                <span class="menu-title">Records</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('cow-breeds-admin') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-nature-people"></i>
                </span>
                <span class="menu-title">Cow Breeds</span>
            </a>
        </li>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('milk-rates-admin') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-cash-multiple"></i>
                </span>
                <span class="menu-title">Rates</span>
            </a>
        </li>
        <hr>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('notifications-admin') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-bell-outline"></i>
                </span>
                <span class="menu-title">Notifications</span>
            </a>
        </li>
        <hr>
        <li class="nav-item menu-items">
            <a class="nav-link" href="{{ route('users-admin') }}">
                <span class="menu-icon">
                    <i class="mdi mdi-account"></i>
                </span>
                <span class="menu-title">Users</span>
            </a>
        </li>
    </ul>
</nav>
