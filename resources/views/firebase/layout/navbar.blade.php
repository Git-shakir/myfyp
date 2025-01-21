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
                    color: #ffffff !important;
                    /* Default color */
                    text-decoration: none;
                }

                .nav-link:hover {
                    color: #000000 !important;
                    /* Hover color */
                }

                .nav-link.active-link {
                    color: #000000 !important;
                    /* Hover color */
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

                <form action="{{ route('logout') }}" method="POST" class="ms-3 d-inline"
                    onsubmit="return confirmLogout()">
                    @csrf
                    <button type="button" class="btn btn-danger" onclick="showLogoutModal()">Logout</button>
                </form>
            </ul>
        </div>
    </div>

    <!-- Confirmation Modal -->
    <div id="logoutModal" class="modal" tabindex="-1" style="display: none;">
        <div class="modal-dialog">
            <div class="modal-content custom-modal-bg">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Logout</h5>
                    <button type="button" class="btn-close" onclick="hideLogoutModal()"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to log out?</p>
                </div>
                <div class="modal-footer">
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="btn btn-danger">Yes, Log Out</button>
                    </form>
                    <button type="button" class="btn btn-secondary" onclick="hideLogoutModal()">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
    function showLogoutModal() {
        document.getElementById('logoutModal').style.display = 'block';
    }

    function hideLogoutModal() {
        document.getElementById('logoutModal').style.display = 'none';
    }
</script>

<!-- Custom Styles -->
<style>
    .navbar-toggler-icon {
        background-image: url("data:image/svg+xml;charset=utf8,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3E%3Cpath stroke='rgba(255, 255, 255, 1)' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3E%3C/svg%3E");
    }

    /* Custom modal background color */
    .custom-modal-bg {
        background-color: #b5c7d9;
        /* Beige color */
        color: #333;
        /* Text color */
        border-radius: 10px;
        /* Optional rounded corners */
    }

    /* Optional: Add padding or other styles */
    .custom-modal-bg .modal-header,
    .custom-modal-bg .modal-footer {
        border: none;
        /* Remove borders */
    }
</style>
