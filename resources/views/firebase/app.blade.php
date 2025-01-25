<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>LivestoCare</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ url('images\LivestoCare Logo.png') }}" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css"
        rel="stylesheet">

    <script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-database-compat.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.7-beta.0/inputmask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

    <script src="{{ asset('js/age-calculator.js') }}"></script>


    <!-- Custom Styles -->
    <style>
        html,
        body {
            height: 100%;
        }

        .main-container {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .content {
            flex: 1;
        }

        .footer {
            background-color: #00ff40;
            padding: 10px 0;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="main-container">
        <!-- Navbar -->
        @include('firebase.layout.navbar')

        <!-- Main Content -->
        <div class="content">
            @yield('content')
        </div>

        <!-- Footer -->
        @include('firebase.layout.footer')

    </div>

    <script>
        // Laravel route URL for Livestock Checkup
        const checkupAnimalDataBaseUrl = "{{ route('checkup-animal', ['livestockUid' => '__ANIMAL_KEY__']) }}";
        // Laravel route URL for adding animal data
        const addAnimalDataBaseUrl = "{{ route('add-animalData', ['uid' => '__LIVESTOCK_UID__']) }}";

        console.log('Initializing Firebase...');

        // Firebase configuration
        const firebaseConfig = {
            apiKey: "{{ env('FIREBASE_API_KEY') }}",
            authDomain: "{{ env('FIREBASE_AUTH_DOMAIN') }}",
            databaseURL: "{{ env('FIREBASE_DATABASE_URL') }}",
            projectId: "{{ env('FIREBASE_PROJECT_ID') }}",
            storageBucket: "{{ env('FIREBASE_STORAGE_BUCKET') }}",
            messagingSenderId: "{{ env('FIREBASE_MESSAGING_SENDER_ID') }}",
            appId: "{{ env('FIREBASE_APP_ID') }}",
        };

        // Initialize Firebase
        const app = firebase.initializeApp(firebaseConfig);
        const db = firebase.database();

        // Listening for triggers
        const dbRef = db.ref('/triggers');

        // Listen for changes to the 'edit_uid' trigger
        dbRef.child('edit_uid').on('value', (snapshot) => {
            const livestockUid = snapshot.val();
            console.log('edit_uid trigger detected. Animal Key:', livestockUid);

            if (livestockUid) {
                // Replace placeholder with the actual livestockUid
                const checkupUrl = checkupAnimalDataBaseUrl.replace('__ANIMAL_KEY__', livestockUid);
                console.log('Redirecting to checkup page:', checkupUrl);

                // Redirect to the checkup page
                window.location.href = checkupUrl;

                // Remove the trigger to avoid duplicate redirects
                dbRef.child('edit_uid').remove()
                    .then(() => console.log('edit_uid trigger removed successfully'))
                    .catch(error => console.error('Error removing edit_uid trigger:', error));
            } else {
                console.warn('No livestockUid found in the edit_uid trigger.');
            }
        });

        // Listen for changes to the 'new_uid' trigger
        dbRef.child('new_uid').on('value', (snapshot) => {
            const livestockUid = snapshot.val();
            console.log('new_uid detected:', livestockUid);

            if (livestockUid) {
                const addUrl = addAnimalDataBaseUrl.replace('__LIVESTOCK_UID__', livestockUid);
                console.log('Redirecting to add page:', addUrl);
                window.location.href = addUrl;
                dbRef.child('new_uid').remove()
                    .then(() => console.log('new_uid trigger removed successfully'))
                    .catch(error => console.error('Error removing new_uid trigger:', error));
            }
        });
    </script>


    <!-- Centralized Script -->
    <script>
        function calculateAgeDetails(birthDate) {
            if (!birthDate) return { formattedAge: null, totalMonths: null };

            const birthDateObj = new Date(birthDate);

            // Ensure the date is valid
            if (isNaN(birthDateObj)) return { formattedAge: null, totalMonths: null };

            const today = new Date();
            let years = today.getFullYear() - birthDateObj.getFullYear();
            let months = today.getMonth() - birthDateObj.getMonth();
            let days = today.getDate() - birthDateObj.getDate();

            // Adjust for negative days
            if (days < 0) {
                months -= 1;
                const prevMonth = new Date(today.getFullYear(), today.getMonth(), 0);
                days += prevMonth.getDate();
            }

            // Adjust for negative months
            if (months < 0) {
                years -= 1;
                months += 12;
            }

            const totalMonths = years * 12 + months; // Calculate total months
            const formattedAge = `${years} years, ${months} months, ${days} days`;

            return { formattedAge, totalMonths };
        }

        // Function to dynamically update the age field
        function updateAgeFields(birthDateSelector, ageDisplaySelector) {
            const birthDate = document.querySelector(birthDateSelector)?.value;

            if (birthDate) {
                const { formattedAge } = calculateAgeDetails(birthDate);
                if (formattedAge) {
                    document.querySelector(ageDisplaySelector).value = formattedAge;
                } else {
                    document.querySelector(ageDisplaySelector).value = '';
                }
            }
        }

        // Example initialization for pages where needed
        document.addEventListener('DOMContentLoaded', function () {
            const birthDateInput = document.querySelector('#bdate');
            const ageDisplayInput = document.querySelector('#age');

            if (birthDateInput && ageDisplayInput) {
                updateAgeFields('#bdate', '#age');

                // Optional: Add a daily refresh for the age
                setInterval(() => {
                    updateAgeFields('#bdate', '#age');
                }, 24 * 60 * 60 * 1000); // Refresh daily
            }
        });
    </script>

</body>

</html>
