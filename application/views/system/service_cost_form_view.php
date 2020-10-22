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
				<h1 class="pl-3">Service Cost Form</h1>
				<div class="float-right ml-1">
					<a href="<?php echo site_url('api/service-cost'); ?>" data-toggle="tooltip" data-placement="top" title="Cancel" class="btn btn btn-default font-14"><i class="fas fa-reply"></i></a>
				</div>
				<div class="float-right ml-1">
					<a href="#" data-toggle="tooltip" data-placement="top" title="Save" class="btn btn-primary font-14"><i class="fas fa-save"></i></a>
				</div>
				<?php if ($this->data['method'] == 'edit') { ?>
					<div class="float-right">
						<a href="#" data-toggle="tooltip" data-placement="top" title="Delete" class="btn btn-danger font-14" onclick="confirm_delete()"><i class="fas fa-trash-alt"></i></a>
					</div>
				<?php } ?>
			</div>
		</div>
		<div class="row p-4">
			<div class="col-12">
				<div class="card">
					<div class="card-header">
						<i class="fas fa-user-edit"></i> Service Cost Form
					</div>
					<div class="card-body">
						<form class="form-inline" action="<?php echo site_url('api/service-cost/form/'.$this->data['id'])."?&method=".$this->data['method']; ?>" method="post">
							<legend>Service Cost</legend>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-service_type">Service Type</label>
								<div class="col-10 form-input">
									<select class="form-control w-100" id="input-service_type" name="service_type">
										<?php 

										if ($this->data['service_list']) {

											foreach ($this->data['service_list'] as $result) {

												if ($this->data['service_type'] == $result['id']) {
													echo '<option value="'.$result['id'].'" selected>'.ucfirst($result['service_name']).'</option>';
												} else {
													echo '<option value="'.$result['id'].'">'.ucfirst($result['service_name']).'</option>';
												}
											} 
										} ?>
									</select>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-service_name_en">Service Name En</label>
								<div class="col-10 form-input">
									<?php 

									if ($this->data['service_name']) {
										echo '<select class="form-control w-100" id="input-service_name_en" name="service_name_en">';

										foreach ($this->data['service_name'] as $result) {

											if ($this->data['service_type'] == $result['id']) {
												echo '<option value="'.$result['service_name_en'].'" selected>'.ucfirst($result['service_name_en']).'</option>';
											} else {
												echo '<option value="'.$result['service_name_en'].'">'.ucfirst($result['service_name_en']).'</option>';
											}
										}

										echo '</select>';

									} else {
										echo '<input type="text" name="service_name_en" placeholder="Service Name En" id="input-service_name_en" class="form-control w-100">';
									} ?>

									<small class="error-text pt-2 d-none">Service Name En must have at least 1 character!</small>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-service_name_th">Service Name Th</label>
								<div class="col-10 form-input">
									<?php 

									if ($this->data['service_name']) {
										echo '<select class="form-control w-100" id="input-service_name_th" name="service_name_th">';

										foreach ($this->data['service_name'] as $result) {

											if ($this->data['service_type'] == $result['id']) {
												echo '<option value="'.$result['service_name_th'].'" selected>'.ucfirst($result['service_name_th']).'</option>';
											} else {
												echo '<option value="'.$result['service_name_th'].'">'.ucfirst($result['service_name_th']).'</option>';
											}
										}

										echo '</select>';

									} else {
										echo '<input type="text" name="service_name_th" placeholder="Service Name Th" id="input-service_name_th" class="form-control w-100">';
									} ?>

									<small class="error-text pt-2 d-none">Service Name Th must have at least 1 character!</small>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-btu">BTU</label>
								<div class="col-10 form-input">
									<select class="form-control w-100" id="input-btu" name="btu">
										<option value="">-</option>
										<?php 

										if ($this->data['btu_list']) {

											for ($i=0; $i < count($this->data['btu_list']); $i++) {

												if ($this->data['btu'] == $this->data['btu_list'][$i]) {
													echo '<option value="'.$this->data['btu_list'][$i].'" selected>'.$this->data['btu_list'][$i].'</option>';
												} else {
													echo '<option value="'.$this->data['btu_list'][$i].'">'.$this->data['btu_list'][$i].'</option>';
												}
											}
										} ?>
									</select>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-cost">Cost</label>
								<div class="col-10 form-input">
									<input type="text" name="cost" placeholder="Cost" id="input-cost" class="form-control w-100" value="<?php echo $this->data['cost']; ?>">
									<small class="error-text pt-2 d-none">Cost must have at least 1 character!</small>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-unit">Unit</label>
								<div class="col-10 form-input">
									<input type="text" name="unit" placeholder="Unit" id="input-unit" class="form-control w-100" value="<?php echo $this->data['unit']; ?>">
									<small class="error-text pt-2 d-none">Unit must have at least 1 character!</small>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-type">Status</label>
								<div class="col-10 form-input">
									<select class="form-control w-100" id="input-status" name="status">
										<?php 
										if ($this->data['status']){
											echo '<option value="0">Disable</option>';
											echo '<option value="1" selected>Enable</option>';
										} else {
											echo '<option value="0" selected>Disable</option>';
											echo '<option value="1">Enable</option>';
										} 
										?>
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
	$('.date-time').datetimepicker({ footer: true, modal: true, format: 'dd/mm/yyyy HH:MM' });

	$(".btn-primary").click(function(){

		if ($('#input-service_name_en').val()) {
			$("#input-service_name_en").removeClass('error-input');
			$('#input-service_name_en').next().addClass('d-none');
		} else {
			$("#input-service_name_en").addClass('error-input');
			$('#input-service_name_en').next().removeClass('d-none');

			$('html,body').animate({
				scrollTop: $("#input-service_name_en").offset().top
			}, 1500);
		}

		if ($('#input-service_name_th').val()) {
			$("#input-service_name_th").removeClass('error-input');
			$('#input-service_name_th').next().addClass('d-none');
		} else {
			$("#input-service_name_th").addClass('error-input');
			$('#input-service_name_th').next().removeClass('d-none');

			$('html,body').animate({
				scrollTop: $("#input-service_name_th").offset().top
			}, 1500);
		}

		if ($('#input-cost').val() && $.isNumeric($('#input-cost').val())) {
			$("#input-cost").removeClass('error-input');
			$('#input-cost').next().addClass('d-none');
		} else {
			$("#input-cost").addClass('error-input');
			$('#input-cost').next().removeClass('d-none');

			$('html,body').animate({
				scrollTop: $("#input-cost").offset().top
			}, 1500);
		}

		if ($('#input-unit').val() && $('#input-unit').val().length == 1) {
			$("#input-unit").removeClass('error-input');
			$('#input-unit').next().addClass('d-none');
		} else {
			$("#input-unit").addClass('error-input');
			$('#input-unit').next().removeClass('d-none');

			$('html,body').animate({
				scrollTop: $("#input-unit").offset().top
			}, 1500);
		}

		if ($('#input-service_name_en').val() && $('#input-service_name_th').val() && $('#input-cost').val() && $('#input-unit').val() && $('#input-unit').val().length == 1 && $.isNumeric($('#input-cost').val())) {
			$("form").submit();
		}

	});

	function confirm_delete() {
		var confirm_delete = confirm("Are you sure?");

		if (confirm_delete == true) {
			window.location = '?&method=delete';
		}
	}

</script>