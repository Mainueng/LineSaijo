<div id="container">
	<header id="header" class="navbar navbar-static-top">
		<div class="container-fluid">
			<div class="header-logo py-2 px-4 d-flex">
				<a href="<?php echo site_url('job-list'); ?>">
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
				<h1 class="pl-3">Financial List</h1>
			</div>
		</div>
		<div class="row p-4">
			<div class="col-9 px-2">
				<div class="card">
					<div class="card-header">
						<i class="fas fa-list mr-1"></i> Financial List
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="financial-list-table" class="table table-hover font-14">
								<thead>
									<tr>
										<th scope="col" class="text-center">Job ID</th>
										<th scope="col" class="text-center">Invoie No.</th>
										<th scope="col" class="text-center">Customer</th>
										<th scope="col" class="text-center">Telephone</th>
										<th scope="col" class="text-center">Service</th>
										<th scope="col" class="text-center">Service Fee</th>
										<th scope="col" class="text-center">Payment Status</th>
										<th scope="col" class="text-center">Update Datetime</th>
										<th scope="col" class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 

									if ($this->data['financial_list']) {

										foreach ($this->data['financial_list'] as $result) { ?>
											<tr>
												<th scope="row" class="text-center"><?php echo $result['job_id']; ?></th>
												<td class="text-center">
													<?php
													if ($result['payment_status']) {
														echo $result['invoice_prefix'].'-'.$result['invoice_id'];
													} else {
														echo "-";
													}
													?>		
												</td>
												<td class="text-center"><?php echo $result['cus_name']." ".$result['cus_lastname']; ?></td>
												<td class="text-center"><?php echo $result['telephone']; ?></td>
												<td class="text-center"><?php echo ucfirst($result['type_code']); ?></td>
												<td class="text-center"><?php echo $result['total']; ?></td>
												<td class="text-center">
													<?php 
													if ($result['payment_status'] == 1) {
														echo 'Paid';
													} else {
														echo '<span class="inactive">Not paid</span>';
													}
													?>		
												</td>
												<td class="text-center"><?php echo $result['update_datetime']; ?></td>
												<td class="text-center">
													<?php if ($result['payment_status']) { ?>
														<a href="<?php echo site_url('invoice-form/'.$result['job_id']); ?>"><i class="far fa-eye"></i> View</a>
													<?php } ?>
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
								<label for="job_id">Job ID</label>
								<input type="text" class="form-control" id="job_id" name="filter_job_id" placeholder="Job ID">
							</div>
							<div class="form-group">
								<label for="technician">Invoice ID</label>
								<input type="text" class="form-control" id="invoice_id" name="filter_invoice_id" placeholder="Invoice ID">
							</div>
							<div class="form-group">
								<label for="telephone">Customer</label>
								<input type="text" class="form-control" id="customer" name="filter_customer" placeholder="Customer">
							</div>
							<div class="form-group">
								<label for="telephone">Telephone</label>
								<input type="text" class="form-control" id="telephone" name="filter_telephone" placeholder="Telephone">
							</div>
							<div class="form-group">
								<label for="service_type">Service Type</label>
								<select class="form-control font-14" id="telephone" name="filter_service_type">
									<option value=""></option>
									<?php 
									if ($this->data['service_list']) {

										foreach ($this->data['service_list'] as $service) {
											echo '<option value="'.$service['id'].'">'.ucfirst($service['service_name']).'</option>';
										}
									}
									?>
									
								</select>
							</div>
							<div class="form-group">
								<label for="total">Total</label>
								<input type="text" class="form-control" id="total" name="filter_total" placeholder="Total">
							</div>
							<div class="form-group">
								<label for="status">Payment Status</label>
								<select class="form-control font-14" id="status" name="filter_payment_status">
									<option value=""></option>
									<option value="0">Not paid</option>
									<option value="1">Paid</option>
								</select>
							</div>
							<div class="form-group">
								<label for="status">Update Datetime</label>
								<input type="text" class="form-control" id="update_datetime" name="filter_update_datetime" placeholder="Update Datetime">
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

			var filter_invoice_id = $('input[name=\'filter_invoice_id\']').val();

			if (filter_invoice_id) {
				url += '&filter_invoice_id=' + encodeURIComponent(filter_invoice_id);
			}

			var filter_customer = $('input[name=\'filter_customer\']').val();

			if (filter_customer) {
				url += '&filter_customer=' + encodeURIComponent(filter_customer);
			}

			var filter_telephone = $('input[name=\'filter_telephone\']').val();

			if (filter_telephone !== '') {
				url += '&filter_telephone=' + encodeURIComponent(filter_telephone);
			}

			var filter_service_type = $('select[name=\'filter_service_type\']').val();

			if (filter_service_type !== '') {
				url += '&filter_service_type=' + encodeURIComponent(filter_service_type);
			}

			var filter_total = $('input[name=\'filter_total\']').val();

			if (filter_total !== '') {
				url += '&filter_total=' + encodeURIComponent(filter_total);
			}

			var filter_payment_status = $('select[name=\'filter_payment_status\']').val();

			if (filter_payment_status !== '') {
				url += '&filter_payment_status=' + encodeURIComponent(filter_payment_status);
			}

			var filter_update_datetime = $('input[name=\'filter_update_datetime\']').val();

			if (filter_update_datetime !== '') {
				url += '&filter_update_datetime=' + encodeURIComponent(filter_update_datetime);
			}

			location = 'financial-list?' + url;
		});

		$('#update_datetime').datetimepicker({ footer: true, modal: true, format: 'dd/mm/yyyy HH:MM' });

	</script>