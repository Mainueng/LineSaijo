<div id="container">
	<header id="header" class="navbar navbar-static-top">
		<div class="container-fluid">
			<div class="header-logo py-2 px-4 d-flex">
				<a href="<?php echo site_url('job-claim'); ?>">
					<img src="<?php echo base_url()?>img/logo.png" class="mx-auto d-block">
				</a> 
			</div>
			<div class="d-flex">
				<div class="header-user py-2 px-3 d-flex">
					<a href=""><img src="<?php echo base_url()?>img/user.png" class="user-img rounded-circle mr-1"><span class="px-2"><?php echo $this->data['account_name'].' '.$this->data['account_lastname']; ?></span></a>
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
				<h1 class="pl-3">Service Type</h1>
				<div class="float-right">
					<a href="<?php echo site_url('api/service-type/form/')."?&method=add"?>" data-toggle="tooltip" data-placement="top" title="Add Service Cost" class="btn btn-primary font-14"><i class="fas fa-plus"></i></a>
				</div>
			</div>
		</div>
		<div class="row p-4">
			<div class="col-9 px-2">
				<div class="card">
					<div class="card-header">
						<i class="fas fa-list mr-1"></i> Service Type
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="job-list-table" class="table table-hover">
								<thead>
									<tr>
										<th scope="col" class="text-center">Service Code</th>
										<th scope="col" class="text-center">Service Name En</th>
										<th scope="col" class="text-center">Service Name Th</th>
										<th scope="col" class="text-center">Status</th>
										<th scope="col" class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 

									if ($this->data['service_type']) {

										foreach ($this->data['service_type'] as $result) { ?>
											<tr>
												<th scope="row" class="text-center"><?php echo $result['code']; ?></th>
												<td class="text-center"><?php echo ucfirst($result['name_en']); ?></td>
												<td class="text-center"><?php echo ucfirst($result['name_th']); ?></td>
												<td class="text-center">
													<?php if ($result['status'] == 1) {
														echo "Enable";
													} else {
														echo "Disable";
													}?>	
												</td>
												<td class="text-center">
													<a href="<?php echo site_url('api/service-type/form/'.$result['code']).'?&method=edit'; ?>"><i class="fas fa-edit"></i> Edit</a>
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
							<label for="service_code">Service Code</label>
							<input type="text" class="form-control" id="job_id" name="filter_service_code" placeholder="Service Code">
						</div>
						<div class="form-group">
							<label for="name_en">Service Name En</label>
							<input type="text" class="form-control" id="name_en" name="filter_service_name_en" placeholder="Service Name En">
						</div>
						<div class="form-group">
							<label for="name_th">Service Name Th</label>
							<input type="text" class="form-control" id="name_th" name="filter_service_name_th" placeholder="Service Name En">
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

		var filter_service_code = $('input[name=\'filter_service_code\']').val();

		if (filter_service_code) {
			url += '&filter_service_code=' + encodeURIComponent(filter_service_code);
		}

		var filter_service_name_en = $('input[name=\'filter_service_name_en\']').val();

		if (filter_service_name_en) {
			url += '&filter_service_name_en=' + encodeURIComponent(filter_service_name_en);
		}

		var filter_service_name_th = $('input[name=\'filter_service_name_th\']').val();

		if (filter_service_name_th !== '') {
			url += '&filter_service_name_th=' + encodeURIComponent(filter_service_name_th);
		}

		var filter_status = $('select[name=\'filter_status\']').val();

		if (filter_status !== '') {
			url += '&filter_status=' + encodeURIComponent(filter_status);
		}

		location = 'service-type?' + url;
	});
</script>