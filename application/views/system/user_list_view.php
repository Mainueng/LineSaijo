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
				<h1 class="pl-3">User List</h1>
				<div class="float-right">
					<a href="<?php echo site_url('user-list/form/')."?&method=add"?>" data-toggle="tooltip" data-placement="top" title="Add User" class="btn btn-primary font-14"><i class="fas fa-plus"></i></a>
				</div>
			</div>
		</div>
		<div class="row p-4">
			<div class="col-9 px-2">
				<div class="card">
					<div class="card-header">
						<i class="fas fa-list mr-1"></i> User
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="job-list-table" class="table table-hover">
								<thead>
									<tr>
										<th scope="col" class="text-center">User ID</th>
										<th scope="col" class="text-center">Name</th>
										<th scope="col" class="text-center">Lastname</th>
										<th scope="col" class="text-center">User Role</th>
										<th scope="col" class="text-center">Status</th>
										<th scope="col" class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 

									if ($this->data['user_list']) {

										foreach ($this->data['user_list'] as $result) { ?>
											<tr>
												<th scope="row" class="text-center"><?php echo $result['id']; ?></th>
												<td class="text-center"><?php echo $result['name']; ?></td>
												<td class="text-center"><?php echo $result['lastname']; ?></td>
												<td class="text-center"><?php echo ucfirst($result['user_role_name']); ?></td>
												<td class="text-center">
													<?php if ($result['status'] == 1) {
														echo "Enable";
													} else {
														echo "Disable";
													}?>
												</td>
												<td class="text-center">
													<a href="<?php echo site_url('user-list/form/'.$result['id'].'?&method=edit'); ?>"><i class="fas fa-edit"></i> Edit</a>
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
							<label for="user_id">User ID</label>
							<input type="text" class="form-control" id="user_id" name="filter_user_id" placeholder="User ID">
						</div>
						<div class="form-group">
							<label for="customer">Name</label>
							<input type="text" class="form-control" id="name" name="filter_name" placeholder="Name">
						</div>
						<div class="form-group">
							<label for="telephone">Lastname</label>
							<input type="text" class="form-control" id="lastname" name="filter_lastname" placeholder="Lastname">
						</div>
						<div class="form-group">
							<label for="status">User Role</label>
							<select class="form-control" id="status" name="filter_user_role">
								<option value=""></option>
								<?php 

										if ($this->data['user_role_list']) {

											foreach ($this->data['user_role_list'] as $user_role) {
												if ($this->data['user_role'] == $user_role['id']) {
													echo '<option value = "'. $user_role['id'] .'" selected>'. ucfirst($user_role['name']) .'</option>';
												} else {
													echo '<option value = "'. $user_role['id'] .'">'. ucfirst($user_role['name']) .'</option>';
												}
											}
										}

										?>
							</select>
						</div>
						<div class="form-group">
							<label for="status">Status</label>
							<select class="form-control" id="status" name="filter_status">
								<option value=""></option>
								<option value="1">Enable</option>
								<option value="0">Disable</option>
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

		var filter_job_id = $('input[name=\'filter_user_id\']').val();

		if (filter_job_id) {
			url += '&filter_user_id=' + encodeURIComponent(filter_job_id);
		}

		var filter_name = $('input[name=\'filter_name\']').val();

		if (filter_name) {
			url += '&filter_name=' + encodeURIComponent(filter_name);
		}

		var filter_lastname = $('input[name=\'filter_lastname\']').val();

		if (filter_lastname) {
			url += '&filter_lastname=' + encodeURIComponent(filter_lastname);
		}

		var filter_user_role = $('select[name=\'filter_user_role\']').val();

		if (filter_user_role !== '') {
			url += '&filter_user_role=' + encodeURIComponent(filter_user_role);
		}

		var filter_status = $('select[name=\'filter_status\']').val();

		if (filter_status !== '') {
			url += '&filter_status=' + encodeURIComponent(filter_status);
		}

		location = 'user-list?' + url;
	});

</script>