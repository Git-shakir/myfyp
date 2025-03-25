{{-- Path: resources/views/authentication/register.blade.php --}}

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
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
                <form action="{{ route('firebase.register') }}" method="POST">
                    @csrf
                    <div class="form-group mb-3">
                        <label for="role">Role</label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="" disabled selected>Select your role</option>
                            <option value="farmer">Farmer</option>
                            <option value="clinician">Clinician</option>
                        </select>
                        @error('role')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="name">Name</label>
                        <input type="text" name="name" id="name" class="form-control"
                            placeholder="Enter your name" required>
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="email">Email</label>
                        <input type="email" name="email" id="email" class="form-control"
                            placeholder="Enter email" required>
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="password">Password</label>
                        <input type="password" name="password" id="password" class="form-control"
                            placeholder="Enter password" required>
                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <div class="form-group mb-3">
                        <label for="password_confirmation">Confirm Password</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="form-control" placeholder="Confirm password" required>
                        @error('password_confirmation')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Register</button>
                </form>
                <p class="text-center mt-3">Already have an account? <a href="{{ route('loginpage') }}">Log In</a></p>
            </div>
        </div>
    </div>
</body>

</html>
