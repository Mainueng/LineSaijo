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
				<h1 class="pl-3">User Infomation</h1>
				<div class="float-right ml-1">
					<a href="<?php echo site_url('user-list'); ?>" data-toggle="tooltip" data-placement="top" title="Cancel" class="btn btn btn-default font-14"><i class="fas fa-reply"></i></a>
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
						<i class="fas fa-user-edit"></i> User Infomation Form
					</div>
					<div class="card-body">
						<form class="form-inline" action="<?php echo site_url('user-list/form/'.$this->data['user_id'])."?&method=".$this->data['method']; ?>" method="post">
							<legend>Infomation Details</legend>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-name">Name</label>
								<div class="col-10 form-input">
									<input type="text" name="name" value="<?php echo $this->data['name']; ?>" id="input-name" class="form-control w-100" placeholder="Name">
									<small class="error-text pt-2 d-none">Name must be between 1 and 32 characters!</small>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-lastname">Lastname</label>
								<div class="col-10 form-input">
									<input type="text" name="lastname" value="<?php echo $this->data['lastname']; ?>" id="input-lastname" class="form-control w-100" placeholder="Lastname">
									<small class="error-text pt-2 d-none">Lastname must be between 1 and 32 characters!</small>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-type">User Role</label>
								<div class="col-10 form-input">
									<select class="form-control w-100" id="input-user_role" name="user_role">
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
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-user">User Name</label>
								<div class="col-10 form-input">
									<input type="text" name="user_name" value="<?php echo $this->data['user_name']; ?>" id="input-user_name" class="form-control w-100" placeholder="User Name">
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-password">Password</label>
								<div class="col-10 form-input">
									<input type="password" name="password" id="input-password" class="form-control w-100" placeholder="Password">
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-password">Confirm Password</label>
								<div class="col-10 form-input">
									<input type="password" name="confirm_password" id="input-confirm_password" class="form-control w-100" placeholder="Confirm Password">
									<small class="error-text pt-2 d-none">Password and password confirmation do not match!</small>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-type">Status</label>
								<div class="col-10 form-input">
									<select class="form-control w-100 font-14" id="input-status" name="status">
										<?php if ($this->data['status']) { ?>
											<option value="0">Disable</option>
											<option value="1"selected="">Enable</option>
										<?php } else { ?>
											<option value="0" selected="">Disable</option>
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
	$(".btn-primary").click(function(){

		if ($('#input-name').val() && $('#input-name').val().length < 32) {
			$('#input-name').removeClass('error-input');
			$('#input-name').next().addClass('d-none');
		} else {
			$('#input-name').addClass('error-input');
			$('#input-name').next().removeClass('d-none');
			$('html,body').animate({
				scrollTop: $(this).offset().top
			}, 1500);
		}

		if ($('#input-lastname').val() && $('#input-lastname').val().length < 32) {
			$('#input-lastname').removeClass('error-input');
			$('#input-lastname').next().addClass('d-none');
		} else {
			$('#input-lastname').addClass('error-input');
			$('#input-lastname').next().removeClass('d-none');
			$('html,body').animate({
				scrollTop: $(this).offset().top
			}, 1500);
		}

		if ($('#input-password').val() != '') {

			if (($('#input-password').val() && $('#input-confirm_password').val()) && ($('#input-password').val() == $('#input-confirm_password').val())) {
				$('#input-confirm_password').removeClass('error-input');
				$('#input-confirm_password').next().addClass('d-none');
			} else {
				$('#input-confirm_password').addClass('error-input');
				$('#input-confirm_password').next().removeClass('d-none');
				$('html,body').animate({
					scrollTop: $(this).offset().top
				}, 1500);
			}
		}

		if (($('#input-name').val() && $('#input-lastname').val()) && ($('#input-password').val() == $('#input-confirm_password').val())) {
			$("form").submit();
		}

	});
</script>