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
				<h1 class="pl-3">Claim & Repair List</h1>
				<div class="float-right">
					<a href="#" data-toggle="tooltip" data-placement="top" title="Export Claim & Repair Report" class="btn btn-primary font-14" id="export"><i class="fas fa-file-export"> Export</i></a>
				</div>
				<div class="float-right mr-1">
					<a target="_blank" href="<?php echo site_url('job-claim-manual'); ?>" data-toggle="tooltip" data-placement="top" title="Assign Job" class="btn btn-success font-14"><i class="fas fa-book"></i> คู่มือ</a>
				</div>
			</div>
		</div>
		<div class="row p-4">
			<div class="col-9 px-2">
				<div class="card">
					<div class="card-header">
						<i class="fas fa-list mr-1"></i> Claim & Repair List
					</div>
					<div class="card-body">
						<div class="table-responsive">
							<table id="job-list-table" class="table table-hover">
								<thead>
									<tr>
										<th scope="col" class="text-center">หมายเลขงาน</th>
										<th scope="col" class="text-center">ชื่อ-นามสกุล</th>
										<th scope="col" class="text-center">หมายเลขเครื่อง</th>
										<th scope="col" class="text-center">ประเภทงาน</th>
										<th scope="col" class="text-center">วันที่แจ้ง</th>
										<th scope="col" class="text-center">ปัญหา</th>
										<th scope="col" class="text-center">สถานะ</th>
										<th scope="col" class="text-center">Action</th>
									</tr>
								</thead>
								<tbody>
									<?php 

									if ($this->data['claim_list']) {

										foreach ($this->data['claim_list'] as $result) { ?>
											<tr>
												<th scope="row" class="text-center"><?php echo $result['claim_id']; ?></th>
												<td class="text-center"><?php echo $result['firstname']." ".$result['lastname']; ?></td>
												<td class="text-center"><?php ?><?php echo $result['serial_number_indoor']; ?></td>
												<td class="text-center"><?php echo $result['job_type']; ?></td>
												<td class="text-center"><?php echo $result['date']; ?></td>
												<td class="text-center"><?php echo $result['problem']; ?></td>
												<td class="text-center">
													<?php if ($result['status'] == 'Complete') {
														echo 'เสร็จสิ้น';
													} else if ($result['status'] == 'Processing') {
														echo 'อยู่ระหว่างดำเนินการ';
													} else {
														echo "รอดำเนินการติดต่อกลับ";
													} ?>
												</td>
												<td class="text-center"><a href="<?php echo site_url('job-claim/form/'.$result['claim_id']).'?&method=edit'; ?>"><i class="fas fa-user-edit"></i> แก้ไข</a>
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
					<div class="card-header"><i class="fa fa-filter"></i> ตัวกรอง</div>
					<div class="card-body">
						<div class="form-group">
							<label for="job_id">หมายเลขงาน</label>
							<input type="text" class="form-control" id="claim_id" name="filter_claim_id" placeholder="หมายเลขงาน">
						</div>
						<div class="form-group">
							<label for="customer">ชื่อ-นามสกุล</label>
							<input type="text" class="form-control" id="customer" name="filter_customer" placeholder="ชื่อ-นามสกุล">
						</div>
						<div class="form-group">
							<label for="job_type">ประเภทงาน</label>
							<select class="form-control" id="job_type" name="filter_job_type">
								<option value=""></option>
								<option value="เคลม">เคลม</option>
								<option value="ซ่อม">ซ่อม</option>
							</select>
						</div>
						<div class="form-group">
							<label for="serial_number">หมายเลขเครื่อง</label>
							<input type="text" class="form-control" id="serial_number" name="filter_serial_number" placeholder="หมายเลขเครื่อง">
						</div>
						<div class="form-group">
							<label for="appointment_date">วันที่แจ้ง</label>
							<div class="input-group">
								<input type="text" id="date" name="filter_date" placeholder="Date" class="date-time form-control" />
							</div>
						</div>
						<div class="form-group">
							<label for="status">สถานะ</label>
							<select class="form-control" id="status" name="filter_status">
								<option value=""></option>
								<option value="Pending">รอดำเนินการติดต่อกลับ</option>
								<option value="Processing">อยู่ระหว่างดำเนินการ</option>
								<option value="Complete">เสร็จสิ้น</option>
							</select>
						</div>
						<div class="form-group text-right">
							<button type="button" id="button-filter" class="btn btn-default"><i class="fa fa-filter"></i> กรอง</button>
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

		var filter_claim_id = $('input[name=\'filter_claim_id\']').val();

		if (filter_claim_id) {
			url += '&filter_claim_id=' + encodeURIComponent(filter_claim_id);
		}

		var filter_customer = $('input[name=\'filter_customer\']').val();

		if (filter_customer) {
			url += '&filter_customer=' + encodeURIComponent(filter_customer);
		}

		var filter_job_type = $('select[name=\'filter_job_type\']').val();

		if (filter_job_type !== '') {
			url += '&filter_job_type=' + encodeURIComponent(filter_job_type);
		}

		var filter_serial_number = $('input[name=\'filter_serial_number\']').val();

		if (filter_serial_number !== '') {
			url += '&filter_serial_number=' + encodeURIComponent(filter_serial_number);
		}

		var filter_date = $('input[name=\'filter_date\']').val();

		if (filter_date !== '') {
			url += '&filter_date=' + encodeURIComponent(filter_date);
		}

		var filter_status = $('select[name=\'filter_status\']').val();

		if (filter_status !== '') {
			url += '&filter_status=' + encodeURIComponent(filter_status);
		}

		location = 'job-claim?' + url;
	});

	$('#export').on('click', function() {

		var url_string = window.location;
		var url = new URL(url_string);
		var urls = '';

		var filter_claim_id = url.searchParams.get("filter_claim_id");

		if (filter_claim_id) {
			urls += '&filter_claim_id=' + encodeURIComponent(filter_claim_id);
		}

		var filter_customer = url.searchParams.get("filter_customer");

		if (filter_customer) {
			urls += '&filter_customer=' + encodeURIComponent(filter_customer);
		}

		var filter_job_type = url.searchParams.get("filter_job_type");

		if (filter_job_type) {
			urls += '&filter_job_type=' + encodeURIComponent(filter_job_type);
		}

		var filter_serial_number = url.searchParams.get("filter_serial_number");

		if (filter_serial_number) {
			urls += '&filter_serial_number=' + encodeURIComponent(filter_serial_number);
		}

		var filter_date = url.searchParams.get("filter_date");

		if (filter_date) {
			urls += '&filter_date=' + encodeURIComponent(filter_date);
		}

		var filter_status = url.searchParams.get("filter_status");

		if (filter_status) {
			urls += '&filter_status=' + encodeURIComponent(filter_status);
		}

		location = 'job-claim-export?' + urls;
	});

	$('.date-time').datepicker({ footer: true, modal: true, format: 'dd/mm/yyyy' });
</script>