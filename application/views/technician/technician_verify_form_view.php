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
				<h1 class="pl-3">Verify Form</h1>
				<div class="float-right ml-1">
					<a href="<?php echo site_url('technician-verify'); ?>" data-toggle="tooltip" data-placement="top" title="Cancel" class="btn btn btn-default font-14"><i class="fas fa-reply"></i></a>
				</div>
				<div class="float-right">
					<a href="#" data-toggle="tooltip" data-placement="top" title="Assign Job" class="btn btn-primary font-14"><i class="fas fa-save"></i></a>
				</div>
			</div>
		</div>
		<div class="row p-4">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<i class="fas fa-user-edit"></i> Verify Form
					</div>
					<div class="card-body">
						<form class="form-inline" action="<?php echo site_url('technician-verify/form/?&method='.$this->data['method'].'&id='.$this->data['id']); ?>" method="post">
							<legend>Verify Details</legend>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-technician">Technician</label>
								<div class="col-10 form-input">
									<?php if ($this->data['method'] == 'add') { ?>
										<select class="form-control w-100" id="input-technician" name="technician">
											<?php

											if ($this->data['technician_list']) {

												foreach ($this->data['technician_list'] as $result) { ?>
													<option value="<?php echo $result['tech_id']; ?>"><?php echo $result['name'].' '.$result['lastname']; ?></option>

													<?php 
												} 
											} 
											?>
										</select>
									<?php } else { ?>
										<input class="form-control w-100" id="input-technician" name="technician" value="<?php echo $this->data['tech_name'].' '.$this->data['tech_lastname']; ?>" disabled>
									<?php } ?>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-saijo_certification">Saijo Certification</label>
								<div class="col-10 form-input">
									<input type="text" name="saijo_certification" value="<?php echo $this->data['saijo_certification']; ?>" id="input-saijo_certification" class="form-control w-100" placeholder="Saijo Certification">
									<small class="error-text pt-2 d-none">Saijo Certification must be 10 characters!</small>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-approved_date">Approved Date</label>
								<div class="col-10 form-input">
									<input type="text" name="approval_date" placeholder="Approved Date" id="input-approved_date" class="form-control w-100 date-time" value="<?php echo $this->data['approved_date']; ?>">
									<small class="error-text pt-2 d-none">Approved Date is required!</small>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-expire_date">Expire Date</label>
								<div class="col-10 form-input">
									<input type="text" name="expire_date" placeholder="Expire Date" id="input-expire_date" class="form-control w-100 date-time" value="<?php echo $this->data['expire_date']; ?>">
									<small class="error-text pt-2 d-none">Expire Date is required!</small>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-type">Status</label>
								<div class="col-10 form-input">
									<select class="form-control w-100" id="input-status" name="status">
										<?php if ($this->data['status'] == 1) { ?>
											<option value="0">Disable</option>
											<option value="1"selected="">Enable</option>
										<?php } else { ?>
											<option value="0" selected>Disable</option>
											<option value="1">Enable</option>
										<?php } ?>
									</select>
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
	$('#input-approved_date').datepicker({ format: 'dd/mm/yyyy' });
	$('#input-expire_date').datepicker({ format: 'dd/mm/yyyy' });

	$(".btn-primary").click(function(){

		if ($('#input-saijo_certification').val() && $('#input-saijo_certification').val().length == 10) {
			$('#input-saijo_certification').removeClass('error-input');
			$('#input-saijo_certification').next().addClass('d-none');
		} else {
			$('#input-saijo_certification').addClass('error-input');
			$('#input-saijo_certification').next().removeClass('d-none');
			$('html,body').animate({
				scrollTop: $(this).offset().top
			}, 1500);
		}

		if ($('#input-approved_date').val() && $('#input-approved_date').val().length == 10) {
			$('#input-approved_date').removeClass('error-input');
			$('#input-approved_date').parent().next().addClass('d-none');
		} else {
			$('#input-approved_date').addClass('error-input');
			$('#input-approved_date').parent().next().removeClass('d-none');
			$('html,body').animate({
				scrollTop: $(this).offset().top
			}, 1500);
		}

		if ($('#input-expire_date').val() && $('#input-expire_date').val().length == 10) {
			$('#input-expire_date').removeClass('error-input');
			$('#input-expire_date').parent().next().addClass('d-none');

		} else {
			$('#input-expire_date').addClass('error-input');
			$('#input-expire_date').parent().next().removeClass('d-none');
			$('html,body').animate({
				scrollTop: $(this).offset().top
			}, 1500);
		}

		if ($('#input-saijo_certification').val() && $('#input-saijo_certification').val().length == 10 && $('#input-approved_date').val() && $('#input-approved_date').val().length == 10 && $('#input-expire_date').val() && $('#input-expire_date').val().length == 10) {
			$("form").submit();
		}
	});
</script>