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
				<h1 class="pl-3">Technician Verify List</h1>
				<div class="float-right">
					<a href="<?php echo site_url('technician-verify/form/')."?&method=add"?>" data-toggle="tooltip" data-placement="top" title="Add Technician Verify" class="btn btn-primary font-14"><i class="fas fa-plus"></i></a>
				</div>
			</div>
		</div>
		<div class="row p-4">
			<div class="col-9 px-2">
				<div class="card">
					<div class="card-header">
						<i class="fas fa-list mr-1"></i> Technician Verify List
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="job-list-table" class="table table-hover">
								<thead>
									<tr>
										<th scope="col" class="text-center">Technician ID</th>
										<th scope="col" class="text-center">Technician</th>
										<th scope="col" class="text-center">Saijo Certification</th>
										<th scope="col" class="text-center">Approved Date</th>
										<th scope="col" class="text-center">Expire Date</th>
										<th scope="col" class="text-center">Status</th>
										<th scope="col" class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 

									if ($this->data['technician_verify_list']) {

										foreach ($this->data['technician_verify_list'] as $result) { ?>
											<tr>
												<th scope="row" class="text-center"><?php echo $result['tech_id']; ?></th>
												<td class="text-center"><?php echo $result['name']." ".$result['lastname']; ?></td>
												<td class="text-center"><?php echo $result['saijo_certification']; ?></td>
												<td class="text-center"><?php echo $result['approved_date']; ?></td>
												<td class="text-center"><?php echo $result['expiry_date']; ?></td>
												<td class="text-center">
													<?php if ($result['status'] == 1) {
														echo "Enable";
													} else {
														echo "Disable";
													}?>	
												</td>
												<td class="text-center">
													<a href="<?php echo site_url('technician-verify/form/'.$result['tech_id']).'?&method=edit'; ?>"><i class="fas fa-edit"></i> Edit</a>
												</td>
											</td>
										</tr>
									<?php } } ?>
								</tbody>
							</table>
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
							<label for="saijo_certification">Saijo Certification</label>
							<input type="text" class="form-control" id="saijo_certification" name="filter_saijo_certification" placeholder="Saijo Certification">
						</div>
						<div class="form-group">
							<label for="approved_date">Approved Date</label>
							<div class="input-group">
								<input type="text" id="approved_date" name="filter_approved_date" placeholder="Approved Date" class="date form-control" />
							</div>
						</div>
						<div class="form-group">
							<label for="approved_date">Expire Date</label>
							<div class="input-group">
								<input type="text" id="expire_date" name="filter_expire_date" placeholder="Expire Date" class="date form-control" />
							</div>
						</div>
						<div class="form-group">
							<label for="verify_status">Status</label>
							<select class="form-control font-14" id="verify_status" name="filter_verify_status">
								<option value=""></option>
								<option value="0">Disabel</option>
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

		var filter_saijo_certification = $('input[name=\'filter_saijo_certification\']').val();

		if (filter_saijo_certification !== '') {
			url += '&filter_saijo_certification=' + encodeURIComponent(filter_saijo_certification);
		}

		var filter_approved_date = $('input[name=\'filter_approved_date\']').val();

		if (filter_approved_date !== '') {
			url += '&filter_approved_date=' + encodeURIComponent(filter_approved_date);
		}

		var filter_expire_date = $('input[name=\'filter_expire_date\']').val();

		if (filter_expire_date !== '') {
			url += '&filter_expire_date=' + encodeURIComponent(filter_expire_date);
		}

		var filter_verify_status = $('select[name=\'filter_verify_status\']').val();

		if (filter_verify_status !== '') {
			url += '&filter_verify_status=' + encodeURIComponent(filter_verify_status);
		}

		location = 'technician-verify?' + url;
	});

	$('#approved_date').datepicker({ format: 'dd/mm/yyyy' });
	$('#expire_date').datepicker({ format: 'dd/mm/yyyy' });

</script>