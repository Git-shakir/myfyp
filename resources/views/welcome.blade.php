@extends('firebase.app')

@section('content')
    <style>
        .welcome-background {
            background-image: url('{{ asset('images/livestock farm bg.png') }}');
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            position: relative;
            height: 50vh;
        }

        .welcome-content {
            position: relative;
            z-index: 1;
            padding-top: 100px;
            text-align: center;
            color: rgb(0, 0, 0);
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
            margin-top: 10px;

        }

        .footer-links a {
            color: white;
            margin-right: 10px;
        }

        .navbar.sticky-top {
            box-shadow: 0px 4px 6px rgba(0, 0, 0, 0.401);
        }

        .how-to-use {
            background-color: #f9f9f9;
            padding: 50px 20px;
        }

        .how-to-use h2 {
            font-size: 2rem;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .how-to-use ul {
            list-style: none;
            padding: 0;
        }

        .how-to-use ul li {
            margin-bottom: 15px;
            font-size: 1.2rem;
        }

        .how-to-use ul li i {
            color: #007bff;
            margin-right: 10px;
        }

        .faq-section {
            padding: 50px 20px;
            background-color: #fff;
        }

        .faq-section h2 {
            margin-bottom: 20px;
        }

        .faq-item {
            margin-bottom: 15px;
        }

        .faq-item h4 {
            cursor: pointer;
            color: #007bff;
            transition: color 0.3s ease;
        }

        .faq-item h4:hover {
            color: #0056b3;
        }

        .faq-item p {
            display: none;
            margin-top: 5px;
            font-size: 1rem;
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
                <p>Discover tools that simplify livestock management through an intuitive web platform.</p>
            </div>
            <div class="row text-center">
                <div class="col-md-4">
                    <i class="fas fa-database text-primary"></i>
                    <h4>Centralized Livestock Records</h4>
                    <p>Effortlessly manage and organize livestock data, including species, breed, age, and health details, all in one platform.</p>
                </div>
                <div class="col-md-4">
                    <i class="fas fa-tags text-warning"></i>
                    <h4>RFID Integration</h4>
                    <p>Accurately identify the livestock using RFID tags.</p>
                </div>
                <div class="col-md-4">
                    <i class="fas fa-chart-bar text-success"></i>
                    <h4>Health Check-Up Management</h4>
                    <p>Enable clinicians to conduct detailed physical checkup and store results for each livestock, ensuring comprehensive care.</p>
                </div>
            </div>
        </div>
    </section>

    <script>
        document.querySelectorAll('.faq-item h4').forEach(item => {
            item.addEventListener('click', () => {
                const answer = item.nextElementSibling;
                answer.style.display = answer.style.display === 'block' ? 'none' : 'block';
            });
        });
    </script>
@endsection
