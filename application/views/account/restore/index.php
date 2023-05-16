<?php echo $loginheader ?>
		<form class="form-signin" id="restoreForm" action="#" method="POST">
			<h2 class="form-signin-heading"><a href="/"><img src="/application/public/img/logo.svg" width=100px ></img></a></h2></br>
			<input type="text" class="form-control" id="email" name="email" placeholder="E-Mail">
			</br>
			<button class="btn btn-lg btn-primary btn-block" type="submit">Restore</button>
			<div class="other-link"><a href="/account/login">Back to log in</a></div>
		</form>
		<script>
			$('#restoreForm').ajaxForm({ 
				url: '/account/restore/ajax',
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
