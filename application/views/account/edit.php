<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Profile Settings</h2>
				</div>
				<form action="#" id="editForm" method="POST">
					<div class="pb-2 mt-4 mb-2">
						<h4>General</h4>
					</div>
					<div class="form-group row">
						<label for="email" class="col-sm-3 col-form-label text-sm-right">E-mail:</label>
						<div class="col-sm-5">
							<input type="text" class="form-control" id="email" name="email" placeholder="<?php echo $user['email']; ?>" disabled>
						</div>
					</div>
					<div class="pb-2 mt-4 mb-2">
						<h4>Password</h4>
					</div>
					<div class="form-group row">
						<div class="offset-sm-3 col-sm-9">
							<div class="checkbox">
								<label><input type="checkbox" id="editpassword" name="editpassword" onChange="togglePassword()"> Change password</label>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<label for="password" class="col-sm-3 col-form-label text-sm-right">Password:</label>
						<div class="col-sm-5">
							<input type="password" class="form-control" id="password" name="password" placeholder="Password" disabled>
						</div>
					</div>
					<div class="form-group row">
						<label for="password2" class="col-sm-3 col-form-label text-sm-right">Repeat password:</label>
						<div class="col-sm-5">
							<input type="password" class="form-control" id="password2" name="password2" placeholder="Repeat password" disabled>
						</div>
					</div>
					<div class="form-group row">
						<div class="offset-sm-3 col-sm-9">
							<button type="submit" class="btn btn-primary">Change</button>
						</div>
					</div>
				</form>
				<script>
					$(window).on('load', function () {
						if(location.hash == '#new-password') {
							$('#editpassword').prop('checked', true); togglePassword();
							$('#password').focus();
						}
				    });
					$('#editForm').ajaxForm({ 
						url: '/account/edit/ajax',
						dataType: 'text',
						success: function(data) {
							console.log(data);
							data = $.parseJSON(data);
							switch(data.status) {
								case 'error':
									showError(data.error);
									break;
								case 'success':
									showSuccess(data.success);
									break;
							}
							$('button[type=submit]').prop('disabled', false);
						},
						beforeSubmit: function(arr, $form, options) {
							$('button[type=submit]').prop('disabled', true);
						}
					});
					function togglePassword() {
						var status = $('#editpassword').is(':checked');
						if(status) {
							$('#password').prop('disabled', false);
							$('#password2').prop('disabled', false);
						} else {
							$('#password').prop('disabled', true);
							$('#password2').prop('disabled', true);
						}
					}
				</script>
<?php echo $footer ?>
