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
				<h1 class="pl-3">Financial Report</h1>
				<div class="float-right">
					<a href="#" data-toggle="tooltip" data-placement="top" title="Export Financial Report" class="btn btn-primary font-14" id="export"><i class="fas fa-file-export"> Export</i></a>
				</div>
			</div>
		</div>
		<div class="row p-4">
			<div class="col-9 px-2">
				<div class="card">
					<div class="card-header">
						<i class="fas fa-list mr-1"></i> Financial Report
					</div>
					<div class="card-body">
						<?php if ($this->data['from'] && $this->data['to']) { ?>
							<p class="font-14"><b>Statement Period <?php echo $this->data['from'].' - '.$this->data['to']; ?></b></p>
						<?php } ?>
						<div class="table-responsive">
							<table id="financial-list-table" class="table table-hover font-14">
								<thead>
									<tr>
										<th scope="col" class="text-center">#</th>
										<th scope="col" class="text-center">Report Date</th>
										<th scope="col" class="text-center">Invoice ID</th>
										<th scope="col" class="text-center">Service</th>
										<th scope="col" class="text-center">Service Fee</th>
									</tr>
								</thead>
								<tbody>
									<?php 

									if ($this->data['financial_report']) {

										foreach ($this->data['financial_report'] as $result) { ?>
											<tr>
												<th scope="row" class="text-center"><?php echo $result['index']; ?></th>
												<td class="text-center"><?php echo $result['report_date']; ?></td>
												<td class="text-center"><?php echo $result['invoice_prefix'].'-'.$result['invoice_id']; ?></td>
												<td class="text-center"><?php echo ucfirst($result['service']); ?></td>
												<td class="text-center"><?php echo $result['service_fee']; ?></td>
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
								<label for="from">From</label>
								<input type="text" class="form-control" id="from" name="filter_from" placeholder="From" value="<?php echo $this->data['from']; ?>">
							</div>
							<div class="form-group">
								<label for="to">To</label>
								<input type="text" class="form-control" id="to" name="filter_to" placeholder="To" value="<?php echo $this->data['to']; ?>">
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

			var filter_from = $('input[name=\'filter_from\']').val();

			if (filter_from !== '') {
				url += '&filter_from=' + encodeURIComponent(filter_from);
			}

			var filter_to = $('input[name=\'filter_to\']').val();

			if (filter_to !== '') {
				url += '&filter_to=' + encodeURIComponent(filter_to);
			}

			location = 'financial-report?' + url;
		});

		$('#export').on('click', function() {

			url = '';

			var filter_from = $('input[name=\'filter_from\']').val();

			if (filter_from !== '') {
				url += '&filter_from=' + encodeURIComponent(filter_from);
			}

			var filter_to = $('input[name=\'filter_to\']').val();

			if (filter_to !== '') {
				url += '&filter_to=' + encodeURIComponent(filter_to);
			}

			location = 'financial-export?' + url;
		});

		$('#from').datepicker({ format: 'dd/mm/yyyy' });
		$('#to').datepicker({ format: 'dd/mm/yyyy' });

	</script>