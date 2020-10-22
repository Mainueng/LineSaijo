<!DOCTYPE html>
<html>
<head>
	<title></title>
</head>
<body>
	<?php 

		if (isset($authURL)) {
			echo '<a href="' . htmlspecialchars($authURL) . '">Log in with Facebook!</a>'; 
		} else {
			echo '<a href="' . htmlspecialchars($logoutURL) . '">Log Out!</a>'; 
		}

		echo $app;
		
	?>
</body>
</html>