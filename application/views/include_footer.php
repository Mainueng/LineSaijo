<div class="notification card" id="notification" onclick="open_job()">
	<div class="row card-body p-relative">
		<button type="button" class="close" id="close-notification" onclick="close_notification()">&times;</button>
		<div class="col-3">
			<img src="" id="notification-img">
		</div>
		<div class="col-9 pl-0">
			<b><p id="notification-title" class="font-16 mb-1"></p></b>
			<p id="notification-body" class="font-14 mb-0"></p>
		</div>
	</div>
</div>

<script src="<?php echo base_url()?>js/canvasjs.min.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.14.1/firebase-app.js"></script>
<script src="https://www.gstatic.com/firebasejs/7.14.1/firebase-messaging.js"></script>
<script type="text/javascript">
	const firebaseConfig = {
		apiKey: "AIzaSyBKp4os0Uv8PXH4LrCbyfXw_K9Hhod22Eg",
		authDomain: "saijo-denki-e-service-8af8a.firebaseapp.com",
		databaseURL: "https://saijo-denki-e-service-8af8a.firebaseio.com",
		projectId: "saijo-denki-e-service-8af8a",
		storageBucket: "saijo-denki-e-service-8af8a.appspot.com",
		messagingSenderId: "880968949880",
		appId: "1:880968949880:web:76eb3c73b33c9dede8cd8d",
		measurementId: "G-L8E87D6L3P"
	};
  // Initialize Firebase
  firebase.initializeApp(firebaseConfig);

  const messaging = firebase.messaging();
  messaging.requestPermission()
  .then(function() {
  	return messaging.getToken();
  })
  .then(function(token) {
  	$.post("<?php echo site_url('token'); ?>",
  	{
  		token: token,
  	});
  })
  .catch(function (err) {
  	console.log(err);
  });

  messaging.onMessage(function(payload) {

    console.log(payload);

    var title = payload.notification.title;
    var body = payload.notification.body;
    var icon = payload.notification.icon;

    document.getElementById("notification").style.display = 'block';
    document.getElementById("notification-img").setAttribute("src","<?php echo base_url()?>"+icon);
    document.getElementById("notification-title").innerHTML = title;
    document.getElementById("notification-body").innerHTML = body;

  });

  function close_notification()
  {
  	document.getElementById("notification").style.display = 'none';
  }

  function open_job()
  {
    var text = document.getElementById("notification-body").textContent;
    var id = text.split(" ");

    var re = /\d+/g;
    var job_id = text.match(re);

    window.location.href = "<?php echo base_url();?>job-claim/form/"+job_id+"?&method=edit";

  }

</script>
</body>
</html>