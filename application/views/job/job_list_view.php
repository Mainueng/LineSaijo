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
				<h1 class="pl-3">Jobs List</h1>
			</div>
		</div>
		<div class="row p-4">
			<div class="col-9 px-2">
				<div class="card">
					<div class="card-header">
						<i class="fas fa-list mr-1"></i> Jobs List
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="job-list-table" class="table table-hover">
								<thead>
									<tr>
										<th scope="col" class="text-center">Job ID</th>
										<th scope="col" class="text-center">Customer</th>
										<th scope="col" class="text-center">Service</th>
										<th scope="col" class="text-center">Appointment Date</th>
										<th scope="col" class="text-center">Location</th>
										<th scope="col" class="text-center">Status</th>
										<th scope="col" class="text-center">Technician</th>
										<th scope="col" class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 

									if ($this->data['job_list']) {

										foreach ($this->data['job_list'] as $result) { ?>
											<tr>
												<th scope="row" class="text-center"><?php echo $result['job_id']; ?></th>
												<td class="text-center"><?php echo $result['name']." ".$result['lastname']; ?></td>
												<td class="text-center"><?php echo ucfirst($result['service']); ?></td>
												<td class="text-center"><?php echo $result['appointment_datetime']; ?></td>
												<td class="text-center pointer">
													<?php if ($result['latitude'] && $result['longitude']) { ?>
														<img src="img/map.png" data-toggle="modal" data-target="#modal" onclick="initMap(<?php echo $result['latitude'] ?>,<?php echo $result['longitude'] ?>)">
													<?php } else {
														echo "-";
													} ?>
												</td>
												<td class="text-center">
													<?php 
													if ($result['status'] == "inactive"){ 
														echo '<span class="inactive">'.ucfirst($result['status']).'</span>';
													} else {
														echo ucfirst($result['status']);
													}
													?>	
												</td>
												<td class="text-center"><?php echo $result['technician']; ?></td>
												<td class="text-center">
													<?php if ($result['status'] == "created") { ?>
														<a href="<?php echo site_url('job-list/form/'.$result['job_id']).'?&method=add'; ?>"><i class="fas fa-user-edit"></i> Assign</a>
													<?php } else { ?>
														<a href="<?php echo site_url('job-list/form/'.$result['job_id']).'?&method=edit'; ?>"><i class="fas fa-edit"></i> Edit</a>
													<?php } ?>
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
							<label for="job_id">Job ID</label>
							<input type="text" class="form-control" id="job_id" name="filter_job_id" placeholder="Job ID">
						</div>
						<div class="form-group">
							<label for="customer">Customer</label>
							<input type="text" class="form-control" id="customer" name="filter_customer" placeholder="Customer">
						</div>
						<div class="form-group">
							<label for="service">Serive</label>
							<select class="form-control" id="service" name="filter_service">
								<option value=""></option>
								<?php 

								if ($this->data['service_list']) {

									foreach ($this->data['service_list'] as $service) {
										echo '<option value = "'. $service['service_name'] .'">'. ucfirst($service['service_name']) .'</option>';
									}
								}

								?>
							</select>
						</div>
						<div class="form-group">
							<label for="appointment_date">Appointment Date</label>
							<div class="input-group">
								<input type="text" id="appointment_datetime" name="filter_appointment_datetime" placeholder="Appointment Date" id="appointment_date" class="date-time form-control" />
							</div>
						</div>
						<div class="form-group">
							<label for="status">Status</label>
							<select class="form-control" id="status" name="filter_status">
								<option value=""></option>
								<?php 
								if ($this->data['status_list']) {

									foreach ($this->data['status_list'] as $status) {
										echo '<option value = "'. $status['status_name'] .'">'. ucfirst($status['status_name']) .'</option>';
									}
								}

								?>
							</select>
						</div>
						<div class="form-group">
							<label for="technician">Technician</label>
							<input type="text" class="form-control" id="technician" name="filter_technician" placeholder="Technician">
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

		var filter_job_id = $('input[name=\'filter_job_id\']').val();

		if (filter_job_id) {
			url += '&filter_job_id=' + encodeURIComponent(filter_job_id);
		}

		var filter_customer = $('input[name=\'filter_customer\']').val();

		if (filter_customer) {
			url += '&filter_customer=' + encodeURIComponent(filter_customer);
		}

		var filter_service = $('select[name=\'filter_service\']').val();

		if (filter_service !== '') {
			url += '&filter_service=' + encodeURIComponent(filter_service);
		}

		var filter_appointment_datetime = $('input[name=\'filter_appointment_datetime\']').val();

		if (filter_appointment_datetime !== '') {
			url += '&filter_appointment_datetime=' + encodeURIComponent(filter_appointment_datetime);
		}

		var filter_status = $('select[name=\'filter_status\']').val();

		if (filter_status !== '') {
			url += '&filter_status=' + encodeURIComponent(filter_status);
		}

		var filter_technician = $('input[name=\'filter_technician\']').val();

		if (filter_technician !== '') {
			url += '&filter_technician=' + encodeURIComponent(filter_technician);
		}

		location = 'job-list?' + url;
	});

	$('.date-time').datetimepicker({ footer: true, modal: true, format: 'dd/mm/yyyy HH:MM' });
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