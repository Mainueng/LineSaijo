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
				<h1 class="pl-3">Customer Information</h1>
				<div class="float-right ml-1">
					<a href="<?php echo site_url('customer-list'); ?>" data-toggle="tooltip" data-placement="top" title="Cancel" class="btn btn btn-default font-14"><i class="fas fa-reply"></i></a>
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
						<i class="fas fa-user-edit"></i> Customer Information Form
					</div>
					<div class="card-body">
						<form class="form-inline" action="<?php echo site_url('customer-list/form/'.$this->data['user_id']); ?>" method="post" enctype="multipart/form-data">
							<legend>Customer Details</legend>
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
								<label class="col-2 control-label" for="input-telephone">Telephone</label>
								<div class="col-10 form-input">
									<input type="text" name="telephone" value="<?php echo $this->data['telephone']; ?>" id="input-telephone" class="form-control w-100" placeholder="Telephone">
									<small class="error-text pt-2 d-none">Telephone must be 10 characters!</small>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-image">Image</label>
								<div class="col-10 form-input upload-btn-wrapper">
									<input type='file' name="profile_img" id="upload-img" onchange="readURL(this);" />
									<img id="profile-img" src="<?php echo base_url().'application/controllers/v1/core/upload/profile_img/'.$this->data['profile_img']; ?>">
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-address">Address</label>
								<div class="col-10 form-input">
									<textarea rows="5" name="address" placeholder="Address" id="input-address" class="form-control w-100"><?php echo $this->data['address']; ?></textarea>
									<small class="error-text pt-2 d-none">Address must be between 1 and 200 characters!</small>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-telephone">District</label>
								<div class="col-10 form-input">
									<input type="text" name="district" value="<?php echo $this->data['district']; ?>" id="input-district" class="form-control w-100" placeholder="District">
									<small class="error-text pt-2 d-none">District must be between 1 and 32 characters!</small>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-province">Province</label>
								<div class="col-10 form-input">
									<input type="text" name="province" value="<?php echo $this->data['province']; ?>" id="input-province" class="form-control w-100" placeholder="Province">
									<small class="error-text pt-2 d-none">Province must be between 1 and 32 characters!</small>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-postal_code">Postal Code</label>
								<div class="col-10 form-input">
									<input type="text" name="postal_code" value="<?php echo $this->data['postal_code']; ?>" id="input-postal_code" class="form-control w-100" placeholder="Postal Code">
									<small class="error-text pt-2 d-none">Postal Code must be 5 characters!</small>
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-telephone">Location</label>
								<div class="col-10 form-input">
									<div class="" id="map" style="width: 100%; height: 700px;"></div>
									<input type="hidden" id="latitude" name="latitude" value="<?php echo $this->data['latitude']; ?>">
									<input type="hidden" id="longitude" name="longitude" value="<?php echo $this->data['longitude']; ?>">
								</div>
							</div>
							<div class="form-group w-100 required">
								<label class="col-2 control-label" for="input-user">Username</label>
								<div class="col-10 form-input">
									<input type="text" name="user_name" value="<?php echo $this->data['user_name']; ?>" id="input-user_name" class="form-control w-100" placeholder="User Name">
									<small class="error-text pt-2 d-none">Username must be 1 characters or more!</small>
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
		}

		if ($('#input-lastname').val() && $('#input-lastname').val().length < 32) {
			$('#input-lastname').removeClass('error-input');
			$('#input-lastname').next().addClass('d-none');
		} else {
			$('#input-lastname').addClass('error-input');
			$('#input-lastname').next().removeClass('d-none');
		}

		if ($('#input-telephone').val() && $('#input-telephone').val().length > 8 && $('#input-telephone').val().length <= 10) {
			$('#input-telephone').removeClass('error-input');
			$('#input-telephone').next().addClass('d-none');
		} else {
			$('#input-telephone').addClass('error-input');
			$('#input-telephone').next().removeClass('d-none');
		}

		if ($('#input-address').val() && $('#input-address').val().length > 1 && $('#input-address').val().length <= 200) {
			$('#input-address').removeClass('error-input');
			$('#input-address').next().addClass('d-none');
		} else {
			$('#input-address').addClass('error-input');
			$('#input-address').next().removeClass('d-none');
		}

		if ($('#input-district').val() && $('#input-district').val().length > 1 && $('#input-district').val().length <= 32) {
			$('#input-district').removeClass('error-input');
			$('#input-district').next().addClass('d-none');
		} else {
			$('#input-district').addClass('error-input');
			$('#input-district').next().removeClass('d-none');
		}

		if ($('#input-province').val() && $('#input-province').val().length > 1 && $('#input-province').val().length <= 32) {
			$('#input-province').removeClass('error-input');
			$('#input-province').next().addClass('d-none');
		} else {
			$('#input-province').addClass('error-input');
			$('#input-province').next().removeClass('d-none');
		}

		if ($('#input-postal_code').val() && $('#input-postal_code').val().length == 5) {
			$('#input-postal_code').removeClass('error-input');
			$('#input-postal_code').next().addClass('d-none');
		} else {
			$('#input-postal_code').addClass('error-input');
			$('#input-postal_code').next().removeClass('d-none');
		}

		if ($('#input-user_name').val() && $('#input-user_name').val().length > 1) {
			$('#input-user_name').removeClass('error-input');
			$('#input-user_name').next().addClass('d-none');
		} else {
			$('#input-user_name').addClass('error-input');
			$('#input-user_name').next().removeClass('d-none');
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

		if (($('#input-name').val().length < 32
			&& $('#input-lastname').val().length < 32
			&& $('#input-telephone').val().length > 8 && $('#input-telephone').val().length <= 10
			&& $('#input-address').val().length > 1 && $('#input-address').val().length <= 200
			&& $('#input-district').val().length > 1 && $('#input-district').val().length <= 32
			&& $('#input-province').val().length > 1 && $('#input-province').val().length <= 32
			&& $('#input-postal_code').val().length == 5
			&& $('#input-user_name').val().length > 1) && ($('#input-password').val() == $('#input-confirm_password').val())) {
			$("form").submit();
	}

});
</script>
<script>

	function initMap() {

		var myLatLng = {lat: parseFloat($('#latitude').val()), lng: parseFloat($('#longitude').val())};

		var map = new google.maps.Map(document.getElementById('map'), {
			center: myLatLng,
			zoom: 16
		});

		var marker = new google.maps.Marker({
			position: myLatLng,
			map: map
		});
	}
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDqGCBqDj4d-4xRzUVDUIu0rySU5cnxr1U&callback=initMap" async defer></script>

<script type="text/javascript">
	function readURL(input) {
		if (input.files && input.files[0]) {
			var reader = new FileReader();

			reader.onload = function (e) {
				$('#profile-img').attr('src', e.target.result);
			}

			reader.readAsDataURL(input.files[0]);
		}
	}
</script>