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
					<a href=""><img src="<?php echo base_url(); ?>img/user.png" class="user-img rounded-circle mr-1"><span class="px-2"><?php echo $this->data['account_name'].' '.$this->data['account_lastname']; ?></span></a>
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
				<h1 class="pl-3">Service Cost</h1>
				<div class="float-right">
					<a href="<?php echo site_url('api/service-cost/form/')."?&method=add"?>" data-toggle="tooltip" data-placement="top" title="Add Service Cost" class="btn btn-primary font-14"><i class="fas fa-plus"></i></a>
				</div>
			</div>
		</div>
		<div class="row p-4">
			<div class="col-9 px-2">
				<div class="card">
					<div class="card-header">
						<i class="fas fa-list mr-1"></i> Service Cost
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="job-list-table" class="table table-hover">
								<thead>
									<tr>
										<th scope="col" class="text-center">Service ID</th>
										<th scope="col" class="text-center">Service Type</th>
										<th scope="col" class="text-center">Service Name</th>
										<th scope="col" class="text-center">BTU</th>
										<th scope="col" class="text-center">Cost</th>
										<th scope="col" class="text-center">Status</th>
										<th scope="col" class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 

									if ($this->data['service_cost_list']) {

										foreach ($this->data['service_cost_list'] as $result) { ?>
											<tr>
												<th scope="row" class="text-center"><?php echo $result['id']; ?></th>
												<td class="text-center"><?php echo ucfirst($result['service_type']); ?></td>
												<td class="text-center"><?php echo $result['service_name_th']; ?></td>
												<td class="text-center"><?php echo $result['btu']; ?></td>
												<td class="text-center"><?php echo $result['cost'].' '.$result['unit']; ?></td>
												<td class="text-center">
													<?php 
													if ($result['status']) {
														echo "Enable";
													} else {
														echo "Disable";
													} ?>
												</td>
												<td class="text-center">
													<a href="<?php echo site_url('api/service-cost/form/'.$result['id']).'?&method=edit'; ?>"><i class="fas fa-edit"></i> Edit</a>
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
							<label for="service_id">Service ID</label>
							<input type="text" class="form-control" id="service_id" name="filter_service_id" placeholder="Service ID">
						</div>
						<div class="form-group">
							<label for="service_type">Service Type</label>
							<select class="form-control" id="service_type" name="filter_service_type">
								<option value=""></option>
								<?php 

								if ($this->data['service_list']) {

									foreach ($this->data['service_list'] as $result) {

										echo '<option value="'.$result['id'].'">'.ucfirst($result['service_name']).'</option>';

									} 
								} ?>
							</select>
						</div>
						<div class="form-group">
							<label for="service_name">Service Name</label>
							<select class="form-control" id="service_name" name="filter_service_name">
								<option value=""></option>
								<?php 

								if ($this->data['service_name']) {

									foreach ($this->data['service_name'] as $result) {

										echo '<option value="'.$result['service_name_th'].'">'.$result['service_name_th'].'</option>';

									} 
								} ?>
							</select>
						</div>
						<div class="form-group">
							<label for="cost">Cost</label>
							<input type="text" class="form-control" id="cost" name="filter_cost" placeholder="Cost">
						</div>
						<div class="form-group">
							<label for="status">Status</label>
							<select class="form-control" id="status" name="filter_status">
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

		var filter_service_id = $('input[name=\'filter_service_id\']').val();

		if (filter_service_id) {
			url += '&filter_service_id=' + encodeURIComponent(filter_service_id);
		}

		var filter_service_type = $('select[name=\'filter_service_type\']').val();

		if (filter_service_type) {
			url += '&filter_service_type=' + encodeURIComponent(filter_service_type);
		}

		var filter_service_name = $('select[name=\'filter_service_name\']').val();

		if (filter_service_name !== '') {
			url += '&filter_service_name=' + encodeURIComponent(filter_service_name);
		}

		var filter_cost = $('input[name=\'filter_cost\']').val();

		if (filter_cost !== '') {
			url += '&filter_cost=' + encodeURIComponent(filter_cost);
		}

		var filter_status = $('select[name=\'filter_status\']').val();

		if (filter_status !== '') {
			url += '&filter_status=' + encodeURIComponent(filter_status);
		}

		location = 'service-cost?' + url;
	});

</script>