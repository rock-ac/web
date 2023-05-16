<?php echo $loginheader ?>
		<form class="form-signin" id="registerForm" action="#" method="POST">
			<h2 class="form-signin-heading"><img src="/application/public/img/logo.svg" width=100px ></img></h2></br>
			<input type="text" autocomplete="new-password" class="form-control" id="email" name="email" placeholder="E-Mail">
			<div class="form-group-vertical">
				<input type="password" autocomplete="new-password" class="form-control" id="password" name="password" placeholder="Password">
				<input type="password" autocomplete="new-password" class="form-control" id="password2" name="password2" placeholder="Re-type password">
			</div>
			</br>
			<button class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
			<div class="other-link"><a href="/account/login">Log in</a></div>
		</form>
		<script>
			$('#registerForm').ajaxForm({ 
				url: '/account/register/ajax',
				dataType: 'text',
				success: function(data) {
					console.log(data);
					data = $.parseJSON(data);
					switch(data.status) {
						case 'error':
							showError(data.error);
							reloadImage('.captcha img');
							$('button[type=submit]').prop('disabled', false);
							break;
						case 'success':
							showSuccess(data.success);
							setTimeout("redirect('/')", 1500);
							break;
					}
				},
				beforeSubmit: function(arr, $form, options) {
					$('button[type=submit]').prop('disabled', true);
				}
			});
			$('.captcha img').click(function() {
				reloadImage(this);
			});
		</script>
<?php echo $loginfooter ?>
