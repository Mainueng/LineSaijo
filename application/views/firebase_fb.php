<?php 



?>


<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<script src="https://www.gstatic.com/firebasejs/7.7.0/firebase-app.js"></script>

	<!-- If you enabled Analytics in your project, add the Firebase SDK for Analytics -->
	<script src="https://www.gstatic.com/firebasejs/7.7.0/firebase-analytics.js"></script>

	<!-- Add Firebase products that you want to use -->
	<script src="https://www.gstatic.com/firebasejs/7.7.0/firebase-auth.js"></script>
	<script src="https://www.gstatic.com/firebasejs/7.7.0/firebase-firestore.js"></script>
	<script>

		var firebaseConfig = {
			apiKey: "AIzaSyAXM_zT7TbekM5mMEci4E44OQ3Z4CEmnvg",
			authDomain: "saijo-denki-core.firebaseapp.com",
			databaseURL: "https://saijo-denki-core.firebaseio.com",
			projectId: "saijo-denki-core",
			storageBucket: "saijo-denki-core.appspot.com",
			messagingSenderId: "135052309742",
			appId: "1:135052309742:web:72e804d2a0ed939ccfafff",
			measurementId: "G-FCRFL5VBBF"
		};

		firebase.initializeApp(firebaseConfig);
		firebase.analytics();

		var provider = new firebase.auth.FacebookAuthProvider();
		var apple_provider = new firebase.auth.OAuthProvider('apple.com');

		function onSignInButtonClick(){
			firebase.auth().signInWithPopup(provider).then(function(result) {
				console.log(result.credential.accessToken);
			}).catch(function(error) {
				console.log(error);
			});
		}

		function onAppleSignInButtonClick(){
			firebase.auth().signInWithPopup(apple_provider).then(function(result) {
				console.log(result.credential.accessToken);
			}).catch(function(error) {
				console.log(error);
			});
		}
	</script>
	<button onclick="onSignInButtonClick()">Login FB</button>
	<button onclick="onAppleSignInButtonClick()">Login Apple</button>
</body>
</html>