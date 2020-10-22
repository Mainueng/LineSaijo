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
				<h1 class="pl-3">Jobs Installation</h1>
			</div>
		</div>
		<div class="row p-4">
			<div class="col-9 px-2">
				<div class="card">
					<div class="card-header">
						<i class="fas fa-list mr-1"></i> Jobs Installation
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="job-list-table" class="table table-hover">
								<thead>
									<tr>
										<th scope="col" class="text-center">Order ID</th>
										<th scope="col" class="text-center">Customer</th>
										<th scope="col" class="text-center">Telephone</th>
										<th scope="col" class="text-center">Product</th>
										<th scope="col" class="text-center">Location</th>
										<th scope="col" class="text-center">Appointment Date</th>
										<th scope="col" class="text-center">Appointment TIme</th>
										<th scope="col" class="text-center">Status</th>
										<th scope="col" class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 

									if ($this->data['job_install']) {

										foreach ($this->data['job_install'] as $result) { ?>
											<tr>
												<th scope="row" class="text-center"><?php echo $result['order_id']; ?></th>
												<td class="text-center"><?php echo $result['name']." ".$result['lastname']; ?></td>
												<td class="text-center"><?php echo $result['telephone']; ?></td>
												<td class="text-center"><?php echo $result['product']; ?></td>
												<td class="text-center pointer">
													<?php if ($result['latitude'] && $result['longitude']) { ?>
														<img src="img/map.png" data-toggle="modal" data-target="#modal" onclick="initMap(<?php echo $result['latitude'] ?>,<?php echo $result['longitude'] ?>)">
													<?php } else {
														echo "-";
													} ?>
												</td>
												<td class="text-center"><?php echo $result['date']; ?></td>
												<td class="text-center"><?php echo $result['time']; ?></td>
												<td class="text-center"><?php echo $result['status']; ?></td>
												<td class="text-center">
													<?php if ($result['app']) { ?>
														<?php if ($result['status'] == 'Wait'){ ?>
															<a href="<?php echo site_url('job-install/form/'.$result['order_id']).'?&method=add'; ?>"><i class="fas fa-user-edit"></i> Assign</a>
														<?php } else { ?>
															<a href="<?php echo site_url('job-install/form/'.$result['order_id']).'?&method=edit'; ?>"><i class="fas fa-edit"></i> Edit</a>
														<?php } ?>
													<?php 
														} else {
															echo 'Application not installed.';
														} 
													?>
												</td>
											</td>
										</tr>
									<?php } } ?>
								</tbody>
							</table>
						</div>
						<div class="modal" id="modal">
							<div class="modal-dialog modal-dialog-centered modal-lg">
								<div class="modal-content">
									<div class="modal-header">
										<h5 class="modal-title" id="exampleModalCenterTitle">Location</h5>
										<button type="button" class="close" data-dismiss="modal">&times;</button>
									</div>
									<div class="modal-body">
										<div class="modal-body" id="map"></div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="row pt-3">
					<div class="col-9">
						<?php echo $this->pagination->create_links(); ?>
					</div>
					<div class="col-3 text-right result-pagination">
						<?php echo $this->data['result_count']; ?>
					</div>
				</div>
			</div>
			<div class="col-3 px-2">
				<div class="filter-container card">
					<div class="card-header"><i class="fa fa-filter"></i> Filter</div>
					<div class="card-body">
						<div class="form-group">
							<label for="job_id">Order ID</label>
							<input type="text" class="form-control" id="job_id" name="filter_order_id" placeholder="Order ID">
						</div>
						<div class="form-group">
							<label for="customer">Customer</label>
							<input type="text" class="form-control" id="customer" name="filter_customer" placeholder="Customer">
						</div>
						<div class="form-group">
							<label for="telephone">Telephone</label>
							<input type="text" class="form-control" id="telephone" name="filter_telephone" placeholder="Telephone">
						</div>
						<div class="form-group">
							<label for="appointment_date">Appointment Date</label>
							<div class="input-group">
								<input type="text" id="appointment_date" name="filter_appointment_date" placeholder="Appointment Date" class="date form-control" />
							</div>
						</div>
						<div class="form-group">
							<label for="appointment_time">Appointment Time</label>
							<select class="form-control" id="appointment_time" name="filter_appointment_time">
								<option value=""></option>
								<option value="09:00 - 12:00">09:00 - 12:00</option>
								<option value="12:00 - 15:00">12:00 - 15:00</option>
								<option value="15:00 - 17:00">15:00 - 17:00</option>
							</select>
						</div>
						<div class="form-group">
							<label for="status">Status</label>
							<select class="form-control" id="status" name="filter_status">
								<option value=""></option>
								<option value="1">Assigned</option>
								<option value="0">Wait</option>
							</select>
						</div>
						<div class="form-group text-right">
							<button type="button" id="button-filter" class="btn btn-default"><i class="fa fa-filter"></i> Filter</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">
	$('#button-filter').on('click', function() {

		url = '';

		var filter_order_id = $('input[name=\'filter_order_id\']').val();

		if (filter_order_id) {
			url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
		}

		var filter_customer = $('input[name=\'filter_customer\']').val();

		if (filter_customer) {
			url += '&filter_customer=' + encodeURIComponent(filter_customer);
		}

		var filter_telephone = $('input[name=\'filter_telephone\']').val();

		if (filter_telephone !== '') {
			url += '&filter_telephone=' + encodeURIComponent(filter_telephone);
		}

		var filter_appointment_date = $('input[name=\'filter_appointment_date\']').val();

		if (filter_appointment_date !== '') {
			url += '&filter_appointment_date=' + encodeURIComponent(filter_appointment_date);
		}

		var filter_appointment_time = $('select[name=\'filter_appointment_time\']').val();

		if (filter_appointment_time !== '') {
			url += '&filter_appointment_time=' + encodeURIComponent(filter_appointment_time);
		}

		var filter_status = $('select[name=\'filter_status\']').val();

		if (filter_status !== '') {
			url += '&filter_status=' + encodeURIComponent(filter_status);
		}

		location = 'job-install?' + url;
	});

	$('.date').datepicker({ format: 'dd/mm/yyyy' });

</script>
<script>
	function initMap($latitude = 0,$longitude = 0) {

		var myLatLng = {lat: parseFloat($latitude), lng: parseFloat($longitude)};

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