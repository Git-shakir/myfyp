<nav class="navbar navbar-expand-lg navbar sticky-top" style="background-color: #758dee;"> <!-- Added sticky-top -->

    <div class="container-fluid">
        <!-- Favicon -->
        <img src="{{ url('images/LivestoCareLogo.png') }}" alt="LivestoCare Logo" width="30" height="30"
            class="me-2">
        <a class="navbar-brand nav-link" href="#">LivestoCare</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
            aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <style>
                /* Custom styles for nav-link */
                .nav-link {
                    color: #ffffff !important; /* Default color */
                    text-decoration: none;
                }

                .nav-link:hover {
                    color: #000000 !important; /* Hover color */
                }

                .nav-link.active-link {
                    color: #000000 !important; /* Hover color */
                    text-decoration: underline;
                    text-decoration-thickness: 2px;
                    text-underline-offset: 4px;
                }

                .navbar.sticky-top {
                    box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.193);
                }
            </style>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('welcome') ? 'active-link' : '' }}"
                        href="{{ url('welcome') }}">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('animalsData') ? 'active-link' : '' }}"
                        href="{{ url('animalsData') }}">Livestock List</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('activity-logs') ? 'active-link' : '' }}"
                        href="{{ url('activity-logs') }}">Activity Logs</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('reports') ? 'active-link' : '' }}"
                        href="{{ url('reports') }}">Reports</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link {{ Request::is('settings') ? 'active-link' : '' }}"
                        href="{{ url('settings') }}">Settings</a>
                </li>

                <form action="{{ route('logout') }}" method="POST" class="ms-3 d-inline">
                    @csrf
                    <button type="submit" class="btn btn-danger">Logout</button>
                </form>
            </ul>
        </div>
    </div>
</nav>
