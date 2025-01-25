<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body style="background-color: #5a95d0;">
    <div class="d-flex justify-content-center align-items-center" style="height: 100vh;">
        <div class="card shadow-sm" style="width: 350px;">
            <div class="card-body">
                <!-- Logo Section -->
                <div class="text-center mb-3">
                    <img src="{{ url('images\LivestoCare Logo.png') }}" alt="Logo" style="max-width: 150px;">
                </div>
                <!-- End of Logo Section -->
                <h4 class="card-title text-center mb-3">Forgot Password</h4>

                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('send-reset-password-email') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Enter your email" required>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Send Password Reset Email</button>
                </form>
                <p class="text-center mt-3"><a href="{{ route('loginpage') }}">Back to Login</a></p>
            </div>
        </div>
    </div>
</body>
</html>
