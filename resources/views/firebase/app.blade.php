<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LivestoCare</title>

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ url('images\LivestoCareLogo.png') }}" />

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.9.1/font/bootstrap-icons.min.css"
        rel="stylesheet">

    <script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-app-compat.js"></script>
    <script src="https://www.gstatic.com/firebasejs/9.22.1/firebase-database-compat.js"></script>


    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/inputmask/5.0.8-beta.0/inputmask.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>

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
            background-color: #0080ff;
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

    <!-- Firebase Configuration -->
    {{-- <script>
        // Load environment variables (if using Node.js)
        require('dotenv').config();

        console.log('Initializing Firebase...');

        // Firebase configuration
        const firebaseConfig = {
            apiKey: process.env.FIREBASE_API_KEY,
            authDomain: process.env.FIREBASE_AUTH_DOMAIN,
            databaseURL: process.env.FIREBASE_DATABASE_URL,
            projectId: process.env.FIREBASE_PROJECT_ID,
            storageBucket: process.env.FIREBASE_STORAGE_BUCKET,
            messagingSenderId: process.env.FIREBASE_MESSAGING_SENDER_ID,
            appId: process.env.FIREBASE_APP_ID,
        };

        // Log the configuration
        console.log('Firebase Config:', firebaseConfig);

        // Initialize Firebase
        try {
            const app = firebase.initializeApp(firebaseConfig);
            console.log('Firebase initialized:', app.name);
        } catch (error) {
            console.error('Error initializing Firebase:', error);
        }

        try {
            const db = firebase.database();
            console.log('Database initialized:', db);
        } catch (error) {
            console.error('Error initializing database:', error);
        }

        // Listening for triggers
        try {
            const dbRef = db.ref('/triggers');
            console.log('Database reference created for path "/triggers":', dbRef);
        } catch (error) {
            console.error('Error creating database reference:', error);
        }

        // Listen for changes to the 'edit_uid' trigger
        dbRef.child('edit_uid').on('value', (snapshot) => {
            const animalKey = snapshot.val();
            console.log('Snapshot value:', snapshot.val());
            console.log('Value received from "/triggers":', snapshot.val());

            console.log('edit_uid detected:', animalKey); // Debugging
            if (animalKey) {
                console.log('Redirecting to edit page...');
                window.location.href = `/edit-animalData/${animalKey}`;
                dbRef.child('edit_uid').remove();
            }
        });

        // Listen for changes to the 'new_uid' trigger
        dbRef.child('new_uid').on('value', (snapshot) => {
            const uid = snapshot.val();
            console.log('new_uid detected:', uid); // Debugging
            if (uid) {
                console.log('Redirecting to add page...');
                window.location.href = `/add-animalData?uid=${uid}`;
                dbRef.child('new_uid').remove();
            }
        });
    </script> --}}

    <script>
        // Laravel route URL for editing animal data
        const editAnimalDataBaseUrl = "{{ route('edit-animalData', ['animalKey' => '__ANIMAL_KEY__']) }}";
        // Laravel route URL for adding animal data
        const addAnimalDataBaseUrl = "{{ route('add-animalData', ['uid' => '__ANIMAL_KEY__']) }}";


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
            const animalKey = snapshot.val();
            console.log('Snapshot value:', snapshot.val());

            if (animalKey) {
                console.log('edit_uid detected:', animalKey);
                const editUrl = editAnimalDataBaseUrl.replace('__ANIMAL_KEY__', animalKey);
                console.log('Redirecting to edit page:', editUrl);
                window.location.href = editUrl;
                dbRef.child('edit_uid').remove()
                    .then(() => console.log('edit_uid trigger removed successfully'))
                    .catch(error => console.error('Error removing edit_uid trigger:', error));
            }
        });

        // Listen for changes to the 'new_uid' trigger
        dbRef.child('new_uid').on('value', (snapshot) => {
            const animalKey = snapshot.val();
            console.log('new_uid detected:', animalKey);

            if (animalKey) {
                const addUrl = addAnimalDataBaseUrl.replace('__ANIMAL_KEY__', animalKey);
                console.log('Redirecting to add page:', addUrl);
                window.location.href = addUrl;
                dbRef.child('new_uid').remove()
                    .then(() => console.log('new_uid trigger removed successfully'))
                    .catch(error => console.error('Error removing new_uid trigger:', error));
            }
        });
    </script>

</body>

</html>
