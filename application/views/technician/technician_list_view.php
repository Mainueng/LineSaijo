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
				<h1 class="pl-3">Technician List</h1>
			</div>
		</div>
		<div class="row p-4">
			<div class="col-9 px-2">
				<div class="card">
					<div class="card-header">
						<i class="fas fa-list mr-1"></i> Technician List
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="job-list-table" class="table table-hover">
								<thead>
									<tr>
										<th scope="col" class="text-center">Technician ID</th>
										<th scope="col" class="text-center">Technician</th>
										<th scope="col" class="text-center">Telephone</th>
										<th scope="col" class="text-center">Location</th>
										<th scope="col" class="text-center">Province</th>
										<th scope="col" class="text-center">Dealer Store</th>
										<th scope="col" class="text-center">Rating</th>
										<th scope="col" class="text-center">Saijo Certification</th>
										<th scope="col" class="text-center">Status</th>
										<th scope="col" class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 

									if ($this->data['technician_list']) {

										foreach ($this->data['technician_list'] as $result) { ?>
											<tr>
												<th scope="row" class="text-center" data-toggle="modal" data-target="#modal-<?php echo $result['tech_id']; ?>"><?php echo $result['tech_id']; ?></th>
												<td class="text-center"><?php echo $result['name']." ".$result['lastname']; ?></td>
												<td class="text-center"><?php echo $result['telephone']; ?></td>
												<td class="text-center pointer">
													<?php if ($result['latitude'] && $result['longitude']) { ?>
														<img src="img/map.png" data-toggle="modal" data-target="#modal" onclick="initMap(<?php echo $result['latitude'] ?>,<?php echo $result['longitude'] ?>)">
													<?php } else {
														echo "-";
													} ?>
												</td>
												<td class="text-center"><?php echo $result['province']; ?></td>
												<td class="text-center"><?php echo $result['dealer_store']; ?></td>
												<td class="text-center"><?php echo $result['rating']; ?></td>
												<td class="text-center"><?php echo $result['saijo_certification']; ?></td>
												<td class="text-center">
													<?php if ($result['status'] == 1) {
														echo "Enable";
													} else {
														echo "Disable";
													}?>	
												</td>
												<td class="text-center">
													<a href="<?php echo site_url('technician-list/form/'.$result['tech_id']).'?&method=edit'; ?>"><i class="fas fa-edit"></i> Edit</a>
												</td>
											</td>
										</tr>

										<div class="modal" id="modal-<?php echo $result['tech_id']; ?>">
											<div class="modal-dialog modal-dialog-centered modal">
												<div class="modal-content">
													<div class="modal-body">
														<button type="button" class="close" data-dismiss="modal">&times;</button>
														<div class="row">
															<div class="col-3 d-flex">
																<img src="<?php echo base_url().'application/controllers/v1/club/upload/profile_img/'.$result['profile_img']; ?>" class="information-img">
															</div>
															<div class="col-9">
																<p class="information-name"><?php echo $result['name']." ".$result['lastname']; ?></p>
																<p class="information-tel mb-2"><i class="fas fa-phone mr-2"></i><?php echo $result['telephone']; ?></p>
																<p class="information-email mb-2"><i class="fas fa-envelope mr-2"></i><?php echo $result['email']; ?></p>
																<p class="information-company mb-2"><i class="fas fa-briefcase mr-2"></i><?php echo $result['dealer_store']; ?></p>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
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
							<label for="job_id">Technician ID</label>
							<input type="text" class="form-control" id="technician_id" name="filter_technician_id" placeholder="Technician ID">
						</div>
						<div class="form-group">
							<label for="technician">Technician</label>
							<input type="text" class="form-control" id="technician" name="filter_technician" placeholder="Technician">
						</div>
						<div class="form-group">
							<label for="telephone">Telephone</label>
							<input type="text" class="form-control" id="telephone" name="filter_telephone" placeholder="Telephone">
						</div>
						<div class="form-group">
							<label for="email">E-mail</label>
							<input type="text" class="form-control" id="email" name="filter_email" placeholder="E-mail">
						</div>
						<div class="form-group">
							<label for="service">Province</label>
							<input type="text" class="form-control" id="province" name="filter_province" placeholder="Province">
						</div>
						<div class="form-group">
							<label for="dealer_store">Dealer Store</label>
							<select class="form-control font-14 w-100" id="dealer_store" name="filter_dealer_store">
								<option value=""></option>
								<?php 

								if ($this->data['dealer_list']) {

									foreach ($this->data['dealer_list'] as $result) {
										echo '<option value="'.$result['dealer_store'].'">'.$result['dealer_store'].'</option>';
									} 
								} ?>
							</select>
						</div>
						<div class="form-group">
							<label for="service">Rating</label>
							<input type="text" class="form-control" id="rating" name="filter_rating" placeholder="Rating">
						</div>
						<div class="form-group">
							<label for="appointment_date">Saijo Certification</label>
							<input type="text" class="form-control" id="saijo_certification" name="filter_saijo_certification" placeholder="Saijo Certification">
						</div>
						<div class="form-group">
							<label for="status">Status</label>
							<select class="form-control font-14" id="status" name="filter_status">
								<option value=""></option>
								<option value="0">Disable</option>
								<option value="1">Enable</option>
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

		var filter_technician_id = $('input[name=\'filter_technician_id\']').val();

		if (filter_technician_id) {
			url += '&filter_technician_id=' + encodeURIComponent(filter_technician_id);
		}

		var filter_technician = $('input[name=\'filter_technician\']').val();

		if (filter_technician) {
			url += '&filter_technician=' + encodeURIComponent(filter_technician);
		}

		var filter_telephone = $('input[name=\'filter_telephone\']').val();

		if (filter_telephone !== '') {
			url += '&filter_telephone=' + encodeURIComponent(filter_telephone);
		}

		var filter_email = $('input[name=\'filter_email\']').val();

		if (filter_email !== '') {
			url += '&filter_email=' + encodeURIComponent(filter_email);
		}

		var filter_province = $('input[name=\'filter_province\']').val();

		if (filter_province !== '') {
			url += '&filter_province=' + encodeURIComponent(filter_province);
		}

		var filter_rating = $('input[name=\'filter_rating\']').val();

		if (filter_rating !== '') {
			url += '&filter_rating=' + encodeURIComponent(filter_rating);
		}

		var filter_saijo_certification = $('input[name=\'filter_saijo_certification\']').val();

		if (filter_saijo_certification !== '') {
			url += '&filter_saijo_certification=' + encodeURIComponent(filter_saijo_certification);
		}

		var filter_status = $('select[name=\'filter_status\']').val();

		if (filter_status !== '') {
			url += '&filter_status=' + encodeURIComponent(filter_status);
		}

		var filter_dealer_store = $('select[name=\'filter_dealer_store\']').val();

		if (filter_dealer_store !== '') {
			url += '&filter_dealer_store=' + encodeURIComponent(filter_dealer_store);
		}

		location = 'technician-list?' + url;
	});

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