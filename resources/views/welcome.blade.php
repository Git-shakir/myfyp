@extends('firebase.app')

@section('content')
    <style>
        .welcome-background {
            background-image: url('{{ asset('images/imagebackground.png') }}');
            background-size: cover;
            background-position: center;
            position: relative;
            height: 100vh;
        }

        .welcome-content {
            position: relative;
            z-index: 1;
            padding-top: 30px;
            text-align: center;
            color: white;
        }

        .welcome-overlay {
            background-color: rgba(0, 0, 0, 0.51);
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: 0;
        }

        .hero-title {
            font-size: calc(2rem + 1vw);
            font-weight: bold;
        }

        .hero-subtitle {
            font-size: 1.5rem;
            margin-top: 10px;
        }

        .features-section i {
            font-size: 3rem;
            margin-bottom: 15px;
        }

        .footer-links a {
            color: white;
            margin-right: 10px;
        }

        .navbar.sticky-top {
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.401);
        }
    </style>

    <!-- Hero Section -->
    <div class="container-fluid welcome-background">
        <div class="welcome-overlay"></div>
        <div class="welcome-content">
            <h1 class="hero-title">Welcome to LivestoCare</h1>
            <p class="hero-subtitle">An effortless livestock management for farmers.</p>
            <a href="#features" class="btn btn-primary btn-lg mt-3">Discover More</a>
        </div>
    </div>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2>LivestoCare Features</h2>
                <p>Discover the tools we offer to simplify livestock management through our web platform.</p>
            </div>
            <div class="row text-center">
                <div class="col-md-4">
                    <i class="fas fa-database text-primary"></i>
                    <h4>Centralized Livestock Records</h4>
                    <p>Manage and organize your livestock data all in one place with ease.</p>
                </div>
                <div class="col-md-4">
                    <i class="fas fa-tags text-warning"></i>
                    <h4>RFID Integration</h4>
                    <p>Efficiently track and identify livestock using RFID tags integrated into the platform.</p>
                </div>
                <div class="col-md-4">
                    <i class="fas fa-chart-bar text-success"></i>
                    <h4>Data Insights</h4>
                    <p>Analyze livestock performance and trends to make informed decisions.</p>
                </div>
            </div>
        </div>
    </section>


    <!-- Footer -->
    <footer class="bg-dark text-white py-4">
        <div class="container text-center">
            <p>&copy; 2025 LivestoCare. All rights reserved.</p>
        </div>
    </footer>
</script>
@endsection
