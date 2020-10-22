<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Welcome to CodeIgniter</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto:100,300,400,500,700,900|Material+Icons" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/vuetify/dist/vuetify.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, minimal-ui">

    <style type="text/css">

        ::selection {
            background-color: #E13300;
            color: white;
        }

        ::-moz-selection {
            background-color: #E13300;
            color: white;
        }

        body {
            background-color: #fff;
            margin: 40px;
            font: 13px/20px normal Helvetica, Arial, sans-serif;
            color: #4F5155;
        }

        a {
            color: #003399;
            background-color: transparent;
            font-weight: normal;
        }

        h1 {
            color: #444;
            background-color: transparent;
            border-bottom: 1px solid #D0D0D0;
            font-size: 19px;
            font-weight: normal;
            margin: 0 0 14px 0;
            padding: 14px 15px 10px 15px;
        }

        code {
            font-family: Consolas, Monaco, Courier New, Courier, monospace;
            font-size: 12px;
            background-color: #f9f9f9;
            border: 1px solid #D0D0D0;
            color: #002166;
            display: block;
            margin: 14px 0 14px 0;
            padding: 12px 10px 12px 10px;
        }

        #body {
            margin: 0 15px 0 15px;
        }

        p.footer {
            text-align: right;
            font-size: 11px;
            border-top: 1px solid #D0D0D0;
            line-height: 32px;
            padding: 0 10px 0 10px;
            margin: 20px 0 0 0;
        }

        #container {
            margin: 10px;
            border: 1px solid #D0D0D0;
            box-shadow: 0 0 8px #D0D0D0;
        }

        .container-1 {
           display: flex;
        }

        .container-1 {
            border: 1px #CCCCCC solid;
            padding: 10px;
        }

        .box-1 {
            flex: 2;
        }

        .box-2 {
            flex: 1;
        }

        .box-3 {
            flex: 1;
        }


    </style>
</head>
<body>

<div id="container">
    <h1>Welcome to CodeIgniter!!!!</h1>
    <!--
	<div id="body">
		<p>The page you are looking at is being generated dynamically by CodeIgniter.</p>

		<p>If you would like to edit this page you'll find it located at:</p>
		<code>application/views/welcome_message.php</code>

		<p>The corresponding controller for this page is found at:</p>
		<code>application/controllers/Welcome.php</code>

		<p>If you are exploring CodeIgniter for the very first time, you should start by reading the <a href="user_guide/">User Guide</a>.</p>
	</div>

	<p class="footer">Page rendered in <strong>{elapsed_time}</strong> seconds. <?php echo (ENVIRONMENT === 'development') ? 'CodeIgniter Version <strong>' . CI_VERSION . '</strong>' : '' ?></p>
</div>
-->

<div class="container-1">

    <div class="box-1">
        <h3>Box One</h3>
        <p>Test Flex Css3 ,container is adipiscing elit</p>
    </div>
    <div class="box-2">
        <h3>Box Two</h3>
        <p>Test Flex Css3 ,container is adipiscing elit</p>
    </div>
    <div class="box-3">
        <h3>Box Three</h3>
        <p>Test Flex Css3 ,container is adipiscing elit</p>
        <div id="app">
            <v-app>
                <v-content>
                    <v-container>Hello world</v-container>
                </v-content>
            </v-app>
        </div>
    </div>

</div>








    <script src="https://cdn.jsdelivr.net/npm/vue/dist/vue.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/vuetify/dist/vuetify.js"></script>


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

    <script>
        new Vue({el: '#app'})
    </script>
</body>
</html>
