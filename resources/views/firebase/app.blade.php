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
</head>

<body>
    @include('firebase.inc.navbar') <!-- Optional Navbar -->
    @yield('content') <!-- Main content -->
    @include('firebase.inc.footer') <!-- Optional Footer -->

    <!-- Firebase Configuration -->
    <script>
        // Load environment variables (if using Node.js)
        require('dotenv').config();

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

        // Initialize Firebase
        const app = firebase.initializeApp(firebaseConfig);
        const db = firebase.database();

        // Listening for triggers
        const dbRef = db.ref('/triggers');

        // Listen for changes to the 'edit_uid' trigger
        dbRef.child('edit_uid').on('value', (snapshot) => {
            const uid = snapshot.val();
            console.log('edit_uid detected:', uid); // Debugging
            if (uid) {
                console.log('Redirecting to edit page...');
                window.location.href = `/edit-animalData?uid=${uid}`;
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
    </script>
</body>

</html>
