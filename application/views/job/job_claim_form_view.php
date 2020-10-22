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
				<h1 class="pl-3">Claim Form</h1>
				<div class="float-right ml-1">
					<a href="<?php echo site_url('job-claim'); ?>" data-toggle="tooltip" data-placement="top" title="Cancel" class="btn btn btn-default font-14"><i class="fas fa-reply"></i></a>
				</div>
				<div class="float-right ml-1">
					<a href="#" data-toggle="tooltip" data-placement="top" title="Assign Job" class="btn btn-primary font-14"><i class="fas fa-save"></i></a>
				</div>
				<div class="float-right">
					<a target="_blank" href="<?php echo site_url('job-claim-print/'.$this->data['claim_id']); ?>" data-toggle="tooltip" data-placement="top" title="Print" class="btn btn-success font-14"><i class="fa fa-print"></i></a>
				</div>
			</div>
		</div>
		<div class="row p-4">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<i class="fas fa-user-edit"></i> Claim Form
					</div>
					<div class="card-body">
						<form class="form-inline" action="<?php echo site_url('job-claim/form/'.$this->data['claim_id'])."?&method=".$this->data['method']; ?>" method="post">
							<legend>Claim Details</legend>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-total">หมายเลขงาน</label>
								<div class="col-10 form-input">
									<input type="text" name="cliam_id" value="<?php echo $this->data['claim_id']; ?>" id="cliam_id" name="input-cliam_id" class="form-control w-100" readonly="readonly">
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-total">วันที่แจ้ง</label>
								<div class="col-10 form-input">
									<input type="text" name="cliam_date" value="<?php echo $this->data['claim_date']; ?>" id="cliam_date" name="input-cliam_date" class="form-control w-100" readonly="readonly">
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-customer">ชื่อ-นามสกุล</label>
								<div class="col-10 form-input">
									<input type="text" name="customer" value="<?php echo $this->data['customer']; ?>" id="input-customer" class="form-control w-100" readonly="readonly">
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-phone_number">เบอร์โทร</label>
								<div class="col-10 form-input">
									<input type="text" name="phone_number" value="<?php echo $this->data['phone_number']; ?>" id="phone_number" class="form-control w-100" readonly="readonly">
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-address">ที่อยู่</label>
								<div class="col-10 form-input">
									<textarea class="form-control w-100" readonly="readonly" style="resize: none;" rows="4" name="address"><?php echo $this->data['address']; ?></textarea>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-cost">หมายเลขเครื่องตัวเย็น</label>
								<div class="col-10 form-input">
									<input type="text" name="serial_number_indoor" value="<?php echo $this->data['serial_number_indoor']; ?>" id="serial_number_indoor" class="form-control w-100">
									<small class="error-text pt-2 d-none">Serial Indoor Number must be 13 characters!</small>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-cost">หมายเลขเครื่องตัวร้อน</label>
								<div class="col-10 form-input">
									<input type="text" name="serial_number_outdoor" value="<?php echo $this->data['serial_number_outdoor']; ?>" id="serial_number_outdoor" class="form-control w-100">
									<small class="error-text pt-2 d-none">Serial Outdor Number must be 13 characters!</small>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-cost">หมายเลขอะไหล่</label>
								<div class="col-10 form-input">
									<input type="text" name="part_number" value="<?php echo $this->data['part_number']; ?>" id="part_number" class="form-control w-100">
									<small class="error-text pt-2 d-none">Part Number must be 13 characters!</small>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-type">ประเภท</label>
								<div class="col-10 form-input">
									<input type="text" name="type" value="<?php echo $this->data['type']; ?>" id="type" class="form-control w-100" readonly="readonly">
								</div>
							</div>
							<div class="form-group w-100 required">
								<div class="form-group w-100 required">
									<label class="col-2 control-label" for="input-warranty">การรับประกัน</label>
									<div class="col-10 form-input">
										<select class="form-control w-100" id="input-warranty" name="warranty">
											<?php if ($this->data['warranty'] == 'no') { ?>
												<option value="no" selected="">หมดประกัน</option>
												<option value="yes">อยู่ในประกัน</option>
											<?php } else { ?>
												<option value="no">หมดประกัน</option>
												<option value="yes" selected="">อยู่ในประกัน</option>
											<?php } ?>
										</select>
									</div>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-job_type">ประเภทงาน</label>
								<div class="col-10 form-input">
									<input type="text" name="job_type" value="<?php echo $this->data['job_type']; ?>" id="job_type" class="form-control w-100" readonly="readonly">
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-service_fee">ปัญหา</label>
								<div class="col-10 form-input">
									<textarea class="form-control w-100" readonly="readonly" style="resize: none;" rows="4" name="problem"><?php echo $this->data['problem']; ?></textarea>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-image">รูปถ่าย</label>
								<div class="col-10 form-input">
									<div class="d-flex-left">
										<?php 
										if ($this->data['img_total']) {

											for ($i=1; $i <= $this->data['img_total']; $i++) { ?>
												<div class="upload-wrapper d-flex p-relative mr-3 mr-2-sm">
													<img src="<?php echo base_url().'application/controllers/dashboard/claim/upload/'.$this->data['claim_id'].'_'.$i.'.png'; ?>" class="img-responsive upload-img" id="upload-img-1" data-toggle="modal" data-target="#modal-<?php echo $this->data['claim_id'].'_'.$i; ?>">
												</div>
												<div class="modal" id="modal-<?php echo $this->data['claim_id'].'_'.$i; ?>">
													<div class="modal-dialog modal-dialog-centered modal">
														<div class="modal-content">
															<div class="modal-body p-0">
																<button type="button" class="close" data-dismiss="modal" style="position: absolute; right: 15px; top: 15px;">&times;</button>
																<img src="<?php echo base_url().'application/controllers/dashboard/claim/upload/'.$this->data['claim_id'].'_'.$i.'.png'; ?>" class="img-fluid w-100">
															</div>
														</div>
													</div>
												</div>
												<?php 
											} 
										} ?>
									</div>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-officer">เจ้าหน้าที่รับเรื่อง</label>
								<div class="col-10 form-input">
									<input type="text" name="officer" value="<?php echo $this->data['officer']; ?>" id="input-officer" class="form-control w-100">
									<small class="error-text pt-2 d-none">Officer must be between 1 and 100 characters!</small>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-technician">ช่างผู้รับผิดชอบ</label>
								<div class="col-10 form-input">
									<input type="text" name="technician" value="<?php echo $this->data['technician']; ?>" id="input-technician" class="form-control w-100">
									<small class="error-text pt-2 d-none">Technician must be between 1 and 100 characters!</small>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-type">สถานะงาน</label>
								<div class="col-10 form-input">
									<select class="form-control w-100" id="input-status" name="status">
										<?php if ($this->data['status'] == 'Processing') { ?>
											<option value="Pending">รอดำเนินการติดต่อกลับ</option>
											<option value="Processing"  selected="">อยู่ระหว่างดำเนินการ</option>
											<option value="Complete">เสร็จสิ้น</option>
										<?php } else if ($this->data['status'] == 'Complete') { ?>
											<option value="Pending">รอดำเนินการติดต่อกลับ</option>
											<option value="Processing">อยู่ระหว่างดำเนินการ</option>
											<option value="Complete"  selected="">เสร็จสิ้น</option>
										<?php } else { ?>
											<option value="Pending" selected="">รอดำเนินการติดต่อกลับ</option>
											<option value="Processing">อยู่ระหว่างดำเนินการ</option>
											<option value="Complete">เสร็จสิ้น</option>
										<?php } ?>
									</select>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-update_date">วันที่อัพเดทสถานะงาน</label>
								<div class="col-10 form-input">
									<input type="text" name="update_date" value="<?php echo $this->data['update_date']; ?>" id="update_date" class="form-control w-100" readonly="readonly">
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-note">หมายเหตุ</label>
								<div class="col-10 form-input">
									<textarea class="form-control w-100" style="resize: none;" rows="4" name="note"><?php echo $this->data['note']; ?></textarea>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/javascript">

	$(".btn-primary").click(function(){

		if ($('#input-officer').val() && $('#input-officer').val().length < 100) {
			$('#input-officer').removeClass('error-input');
			$('#input-officer').next().addClass('d-none');
		} else {
			$('#input-officer').addClass('error-input');
			$('#input-officer').next().removeClass('d-none');

			$('html,body').animate({
				scrollTop: $('#input-officer').offset().top
			}, 1500);
		}

		if ($('#input-technician').val() && $('#input-technician').val().length < 100) {
			$('#input-technician').removeClass('error-input');
			$('#input-technician').next().addClass('d-none');
		} else {
			$('#input-technician').addClass('error-input');
			$('#input-technician').next().removeClass('d-none');

			$('html,body').animate({
				scrollTop: $('#input-technician').offset().top
			}, 1500);
		}

		if ($('#serial_number_indoor').val() && $('#serial_number_indoor').val().length < 100) {
			$('#serial_number_indoor').removeClass('error-input');
			$('#serial_number_indoor').next().addClass('d-none');
		} else {
			$('#part_number').addClass('error-input');
			$('#serial_number_indoor').next().removeClass('d-none');

			$('html,body').animate({
				scrollTop: $('#serial_number_indoor').offset().top
			}, 1500);
		}

		if ($('#type').val() == 'อะไหล่') {
			alert($('#type').val());

			if ($('#part_number').val() && $('#part_number').val().length < 100) {
				$('#part_number').removeClass('error-input');
				$('#part_number').next().addClass('d-none');
			} else {
				$('#part_number').addClass('error-input');
				$('#part_number').next().removeClass('d-none');

				$('html,body').animate({
					scrollTop: $('#part_number').offset().top
				}, 1500);
			}

			if ($('#input-officer').val() && $('#input-technician').val() && $('#serial_number_indoor').val() && $('#part_number').val()) {
				$("form").submit();
			}
		} else if ($('#type').val() == 'เครื่องฟอก') {
			
			if ($('#input-officer').val() && $('#input-technician').val() && $('#serial_number_indoor').val()) {
				$("form").submit();
			}

		} else {

			if ($('#serial_number_outdoor').val() && $('#serial_number_outdoor').val().length < 100) {
				$('#serial_number_outdoor').removeClass('error-input');
				$('#serial_number_outdoor').next().addClass('d-none');
			} else {
				$('#serial_number_outdoor').addClass('error-input');
				$('#serial_number_outdoor').next().removeClass('d-none');

				$('html,body').animate({
					scrollTop: $('#serial_number_outdoor').offset().top
				}, 1500);
			}

			if ($('#input-officer').val() && $('#input-technician').val() && $('#serial_number_indoor').val() && $('#serial_number_outdoor').val()) {
				$("form").submit();
			}

		}

	});
</script>