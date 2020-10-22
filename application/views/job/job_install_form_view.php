<div id="container">
	<header id="header" class="navbar navbar-static-top">
		<div class="container-fluid">
			<div class="header-logo py-2 px-4 d-flex">
				<a href="<?php echo site_url('job-claim'); ?>">
					<img src="<?php echo base_url(); ?>img/logo.png" class="mx-auto d-block">
				</a> 
			</div>
			<div class="d-flex">
				<div class="header-user py-2 px-3 d-flex">
					<a href=""><img src="<?php echo base_url(); ?>img/user.png" class="user-img rounded-circle mr-1"><span class="px-2"><?php echo $this->data['name'].' '.$this->data['lastname']; ?></span></a>
				</div>
				<div class="header-signout py-2 px-4 d-flex">
					<a href="<?php echo site_url('logout'); ?>"><i class="fas fa-sign-out-alt"></i></i><span class="px-2">Logout</span></a>
				</div>
			</div>
		</div>
	</header>
	<nav id="column-left">
		<div class="navigation">
			<span class="fa fa-bars mr-1"></span> Navigation
		</div>
		<ul id="menu" class="in">
			<?php if ($this->data['user-type'] == 1 || $this->data['user-type'] == 9) { ?>

				<li id="menu-catalog">
					<a href="#collapse1" data-toggle="collapse" class="parent collapsed"><i class="fas fa-tools"></i> Jobs</a>
					<ul id="collapse1" class="collapse">
						<li><a href="<?php echo site_url('job-list'); ?>">Jobs List</a></li>
						<li><a href="<?php echo site_url('job-install'); ?>">Installation List</a></li>
						<li><a href="<?php echo site_url('job-claim'); ?>">Claim & Repair</a></li>
					</ul>
				</li>

			<?php } ?>

			<?php if ($this->data['user-type'] == 1 || $this->data['user-type'] == 8) { ?>

				<li id="menu-financial">
					<a href="#collapse2" data-toggle="collapse" class="parent collapsed"><i class="fas fa-file-invoice-dollar"></i></i> Financial</a>
					<ul id="collapse2" class="collapse">
						<li><a href="<?php echo site_url('financial-list'); ?>">Financial List</a></li>
						<li><a href="<?php echo site_url('financial-report'); ?>">Financial Report</a></li>
					</ul>
				</li>

			<?php } ?>

			<?php if ($this->data['user-type'] == 1 || $this->data['user-type'] == 9) { ?>

				<li id="menu-user">
					<a href="#collapse3" data-toggle="collapse" class="parent collapsed"><i class="fas fa-user"></i> Customer</a>
					<ul id="collapse3" class="collapse">
						<li><a href="<?php echo site_url('customer-list'); ?>">Customer Information</a></li>
					</ul>
				</li>

				<li id="menu-technician">
					<a href="#collapse4" data-toggle="collapse" class="parent collapsed"><i class="fas fa-user-cog"></i> Technician</a>
					<ul id="collapse4" class="collapse">
						<li><a href="<?php echo site_url('technician-list'); ?>">Technician Information</a></li>
						<li><a href="<?php echo site_url('technician-verify'); ?>">Technician Verify</a></li>
						<li><a href="<?php echo site_url('dealer-list'); ?>">Dealer</a></li>
					</ul>
				</li>

			<?php } ?>

			<?php if ($this->data['user-type'] == 1 || $this->data['user-type'] == 9) { ?>

				<li id="menu-system">
					<a href="#collapse5" data-toggle="collapse" class="parent collapsed"><i class="fas fa-cog"></i> System</a>
					<ul id="collapse5" class="collapse">
						<?php if ($this->data['user-type'] == 1) { ?>
							<li><a href="<?php echo site_url('user-list'); ?>">User</a></li>
						<?php } ?>
						<li><a href="<?php echo site_url('api/service-cost'); ?>">Service Cost</a></li>
						<li><a href="<?php echo site_url('api/service-type'); ?>">Service Type</a></li>
						<li><a href="<?php echo site_url('api/problems'); ?>">Problems</a></li>
					</ul>
				</li>

			<?php } ?>
		</ul>
	</nav>
	<div id="content">
		<div class="page-header">
			<div class="container-fluid">
				<h1 class="pl-3">Installation Form</h1>
				<div class="float-right ml-1">
					<a href="<?php echo site_url('job-install'); ?>" data-toggle="tooltip" data-placement="top" title="Cancel" class="btn btn btn-default font-14"><i class="fas fa-reply"></i></a>
				</div>
				<div class="float-right">
					<a href="#" data-toggle="tooltip" data-placement="top" title="Assign Job" class="btn btn-primary font-14"><i class="fas fa-save"></i></a>
				</div>
			</div>
		</div>
		<div class="row p-4">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<i class="fas fa-user-edit"></i> Installation Form
					</div>
					<div class="card-body">
						<form class="form-inline" action="<?php echo site_url('job-install/form/'.$this->data['order_id'])."?&method=".$this->data['method']; ?>" method="post">
							<legend>Installation Details</legend>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-customer">Customer</label>
								<div class="col-10 form-input">
									<input type="text" name="name" value="<?php echo $this->data['customer']; ?>" id="input-customer" name="customer" class="form-control w-100" readonly="readonly">
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-telephone">Telephone</label>
								<div class="col-10 form-input">
									<input type="text" name="telephone" value="<?php echo $this->data['telephone']; ?>" id="telephone" name="input-telephone" class="form-control w-100" readonly="readonly">
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-product">Product</label>
								<div class="col-10 form-input">
									<input type="text" name="product" value="<?php echo $this->data['product']; ?>" id="product" name="input-product" class="form-control w-100" readonly="readonly">
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-cost">Installation List</label>
								<div class="col-10 form-input">
									<ol class="mb-0">
										<?php 

										$total = 0;

										if ($this->data['service_cost']) {

											foreach ($this->data['service_cost'] as $cost) {

												for ($i=0; $i < $cost['quantity'] ; $i++) { 
													echo "<li>".$cost['name']." ". $cost['value'] ." - ". $cost['cost']." ฿</li>";

													$serial[] = $cost['name']." ". $cost['value'] ." - ". $cost['cost'];

													$total = $total + $cost['cost'];
												}
											}
										}

										?>
										
									</ol>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-total">Installation Total</label>
								<div class="col-10 form-input">
									<?php echo $total." ฿"; ?>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-service_fee">Free Installation</label>
								<div class="col-10 form-input">
									<select class="form-control w-100" id="input-service_fee" name="service_fee">
										<?php if ($this->data['service_fee']) { ?>
											<option value="1" selected="">No</option>
											<option value="0">Yes</option>
										<?php } else { ?>
											<option value="1">No</option>
											<option value="0" selected="">Yes</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-appointment_datetime">Appointment Datetime <br>(<?php echo $this->data['appointment_time']; ?>)</label>
								<div class="col-10 form-input">
									<input type="text" name="appointment_datetime" placeholder="Appointment Datetime" id="input-appointment_datetime" class="form-control w-100 date-time" value="<?php echo $this->data['appointment_datetime']; ?>">
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-telephone">Location</label>
								<div class="col-10 form-input">
									<div class="" id="map" style="width: 100%; height: 700px;"></div>
									<input type="hidden" id="latitude" name="latitude" value="<?php echo $this->data['latitude']; ?>">
									<input type="hidden" id="longitude" name="longitude" value="<?php echo $this->data['longitude']; ?>">
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-comment">Comment</label>
								<div class="col-10 form-input">
									<textarea rows="5" name="comment" placeholder="Comment" id="input-comment" class="form-control w-100"><?php echo $this->data['comment']; ?></textarea>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-type">Status</label>
								<div class="col-10 form-input">
									<select class="form-control w-100" id="input-status" name="status" <?php echo $this->data['disable'] ?>>
										<?php if ($this->data['status']) { ?>
											<option value="0">Wait</option>
											<option value="1"selected="">Assigned</option>
										<?php } else { ?>
											<option value="0" selected="">Wait</option>
											<option value="1">Assigned</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-type">Technician</label>
								<div class="col-10 form-input">
									<select class="form-control w-100" id="input-technician" name="technician" required="">
										<option value=""></option>
										<?php 

										if ($this->data['technician_list']) {

											foreach ($this->data['technician_list'] as $technician) {
												if ($this->data['tech_id'] == $technician['id']) {
													echo '<option value = "'. $technician['id'] .'" selected>'. $technician['name'].' '. $technician['lastname'] .' - '. $technician['radius'] .'</option>';
												} else {
													echo '<option value = "'. $technician['id'] .'">'. $technician['name'].' '. $technician['lastname'] .' - '. $technician['radius'] .'</option>';
												}
											}
										}

										?>
									</select>
									<small class="error-text pt-2 d-none">Please select Technician</small>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('.date-time').datetimepicker({ footer: true, modal: true, format: 'dd/mm/yyyy HH:MM' });

	$(".btn-primary").click(function(){

		if ($('#input-status').val() == 1) {
			if ($('#input-technician').val()) {
				$("#input-technician").removeClass('error');
				$(".error-text").addClass('d-none');
				$("form").submit();
			} else {
				$("#input-technician").addClass('error-input');
				$(".error-text").removeClass('d-none');

				$('html,body').animate({
					scrollTop: $("#input-technician").offset().top
				}, 1500);
			}
		} else {
			$("form").submit();
		}
	});
</script>
<script>

	function initMap() {

		var myLatLng = {lat: parseFloat($('#latitude').val()), lng: parseFloat($('#longitude').val())};

		var map = new google.maps.Map(document.getElementById('map'), {
			center: myLatLng,
			zoom: 16
		});

		var marker = new google.maps.Marker({
			position: myLatLng,
			map: map
		});
	}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDqGCBqDj4d-4xRzUVDUIu0rySU5cnxr1U&callback=initMap" async defer></script>