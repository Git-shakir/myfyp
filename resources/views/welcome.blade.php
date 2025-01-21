@extends('firebase.app')

@section('content')
    <style>
        .welcome-background {
            background-image: url('{{ asset('images/image2.png') }}');
            background-size: cover;
            background-position: bottom;
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

        .welcome-overlay {
            background-color: rgba(255, 255, 255, 0.632);
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

    <!-- How to Use Section -->
    <section class="how-to-use">
        <div class="container">
            <h2>How to Use LivestoCare</h2>
            <ul>
                <li><i class="fas fa-sign-in-alt"></i> <strong>Register or Log In:</strong> Create an account or log in using your credentials.</li>
                <li><i class="fas fa-upload"></i> <strong>Add Livestock Data:</strong> Input details about your livestock, including tags and performance data.</li>
                <li><i class="fas fa-chart-line"></i> <strong>Analyze Insights:</strong> Use our analytics dashboard to track trends and performance.</li>
                <li><i class="fas fa-bell"></i> <strong>Set Alerts:</strong> Receive notifications for important events such as health checks or milestones.</li>
            </ul>
        </div>
    </section>

    <!-- FAQ Section -->
    <section class="faq-section">
        <div class="container">
            <h2>Frequently Asked Questions</h2>
            <div class="faq-item">
                <h4>How do I add new livestock data?</h4>
                <p>Navigate to the "Add Data" section in the dashboard, fill out the required fields, and submit.</p>
            </div>
            <div class="faq-item">
                <h4>What is RFID integration?</h4>
                <p>RFID integration allows you to use tags for seamless livestock tracking and identification.</p>
            </div>
            <div class="faq-item">
                <h4>Can I export my data?</h4>
                <p>Yes, you can export your livestock data in CSV format from the dashboard settings.</p>
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
