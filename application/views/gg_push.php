<!DOCTYPE html>
<html>
<head>
    <title>403 Forbidden</title>
</head>
<body>

<body>
<!-- Insert these scripts at the bottom of the HTML, but before you use any Firebase services -->

<!-- Firebase App (the core Firebase SDK) is always required and must be listed first -->
<script src="https://www.gstatic.com/firebasejs/5.9.4/firebase-app.js"></script>

<!-- Add Firebase products that you want to use -->
<script src="https://www.gstatic.com/firebasejs/5.9.4/firebase-auth.js"></script>
<script src="https://www.gstatic.com/firebasejs/5.9.4/firebase-database.js"></script>

<script>
    // TODO: Replace the following with your app's Firebase project configuration
    var firebaseConfig = {
        // ...
    };
    // Initialize Firebase
    firebase.initializeApp(firebaseConfig);
</script>
<body>


<script src="https://www.gstatic.com/firebasejs/5.10.0/firebase.js"></script>
<script>
    // Initialize Firebase
    var config = {
        apiKey: "AIzaSyDCBvfNphU7umKb8HmovoelzgQAIWt_ZAw",
        authDomain: "saijo-demo.firebaseapp.com",
        databaseURL: "https://saijo-demo.firebaseio.com",
        projectId: "saijo-demo",
        storageBucket: "saijo-demo.appspot.com",
        messagingSenderId: "54878068130"
    };
    firebase.initializeApp(config);
</script>
</html>
