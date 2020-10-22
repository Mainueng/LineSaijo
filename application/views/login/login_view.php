<div class="login d-flex">
	<div class="login-container d-flex">
		<div class="w-100 d-flex">
			<img src="img/logo-login.png" class="ml-5">
		</div>
		<div class="w-100 d-flex login-border">
			<div class="login-input">
				<form action="<?php echo site_url('login'); ?>" method="post">
						<input type="text" name="user" placeholder="E-mail" class="form-control mb-4" required>
						<input type="password" name="password" placeholder="Password" class="form-control mb-4" required>
						<?php if ($this->input->get('error')) { ?>
							<span class="error-login">Username or Password Invalid!</span>
						<?php } ?>
					<button class="mt-4 w-100" type="submit">LOGIN</button>
				</form>
			</div> 
		</div>
	</div>	
</div>
