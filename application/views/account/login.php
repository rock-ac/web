<?php echo $loginheader ?> 
		<form class="form-signin" id="loginForm" action="#" method="POST">
			<h2 class="form-signin-heading"><a href="/"><img src="/application/public/img/logo.svg" width=100px ></img></a></h2></br>
			<div class="form-group-vertical">
				<input type="text" class="form-control" id="email" name="email" placeholder="E-Mail">
				<input type="password" class="form-control" id="password" name="password" placeholder="Password">
			</div>
			<button class="btn btn-lg btn-primary btn-block" type="submit">Log in</button>
			<div class="other-link"><a href="/account/restore">Forgot your password?</a></div>
			<div class="other-link"><a href="/account/register">Sign up</a></div>
		</form>
		<script>
			$('#loginForm').ajaxForm({ 
				url: '/account/login/ajax',
				dataType: 'text',
				success: function(data) {
					console.log(data);
					data = $.parseJSON(data);
					switch(data.status) {
						case 'error':
							showError(data.error);
							$('button[type=submit]').prop('disabled', false);
							break;
						case 'success':
							setTimeout("redirect('/projects/index')", 0);
							break;
					}
				},
				beforeSubmit: function(arr, $form, options) {
					$('button[type=submit]').prop('disabled', true);
				}
			});
		</script>
<?php echo $loginfooter ?>
