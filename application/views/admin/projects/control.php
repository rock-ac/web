<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Управление проектом</h2>
				</div>
				<div class="card mb-4">
					<div class="card-header">Информация о проекте</div>
					<div class="card-body">
						<table class="table mb-0">
							<tr>
								<th width="200px" rowspan="20" style="border-top: 0">
									<?php if($server['status'] == 0): ?> 
									<button style="width: 100%;margin-bottom: 5px;" type="button" class="btn btn-danger" onClick="sendAction(<?php echo $project['id'] ?>,'block')"><span class="glyphicon glyphicon-off"></span> Заблокировать</button>
									<?php elseif($server['status'] == 1): ?> 
									<button style="width: 100%;margin-bottom: 5px;" type="button" class="btn btn-success" onClick="sendAction(<?php echo $project['id'] ?>,'unblock')"><span class="glyphicon glyphicon-off"></span> Разблокировать</button>
									<?php endif; ?>
								</th>
								<th style="border-top: 0">Версия SA:MP:</th>
								<td style="border-top: 0"><?php echo $project['version'] ?></td>
							</tr>
							<tr>
								<th>Название:</th>
								<td><?php echo $project['name'] ?></td>
							</tr>
							<tr>
								<th>Тарифный план:</th>
								<td>
								<?php 
								switch($item['plan'])
								{
									case 0:
										echo "Minimal";
										break;
								}
								?></td>
							</tr>
							<tr>
								<th>Владелец:</th>
								<td><a href="/admin/partners/edit/index/<?php echo $project['partner_id'] ?>"><?php echo $partner['email'] ?></a></td>
							</tr>
							<tr>
								<th>IP:</th>
								<td><?php echo $project['ip'] . ":" . $project['port'] ?></td>
							</tr>
							<tr>
								<th>Дата окончания оплаты:</th>
								<td><?php echo date("d.m.Y", strtotime($project['expired_date'])) ?></td>
							</tr>
							<tr>
								<th>Статус:</th>
								<td>
									<?php if($project['status'] == 0): ?> 
									<span class="badge badge-danger">Заблокирован</span>
									<?php elseif($project['status'] == 1): ?> 
									<span class="badge badge-success">Не заблокирован</span>
									<?php endif; ?> 
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="card mb-4">
					<div class="card-header">Шифрование</div>
					<div class="card-body">
						<table class="table mb-0">
							<tr>
								<th>AES-key:</th>
								<td><?php echo $project['aes_key'] ?></td>
							</tr>
							<tr>
								<th>AES-IV:</th>
								<td><?php echo $project['aes_iv'] ?></td>
							</tr>
							<tr>
								<th>API-key:</th>
								<td><?php echo $project['api_key'] ?></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="card mb-4">
					<div class="card-header">Статистика</div>
					<div class="card-body">
						<table class="table mb-0">
							<tr>
								<th>Дата создания:</th>
								<td><?php echo $project['aes_key'] ?></td>
							</tr>
							<tr>
								<th>Кол-во детектов модулей:</th>
								<td><?php echo $project['aes_iv'] ?></td>
							</tr>
							<tr>
								<th>Кол-во детектов изменений памяти:</th>
								<td><?php echo $project['aes_iv'] ?></td>
							</tr>
							<tr>
								<th>Кол-во детектов CLEO:</th>
								<td><?php echo $project['aes_iv'] ?></td>
							</tr>
							<tr>
								<th>Кол-во детектов SAMPFUNCS:</th>
								<td><?php echo $project['aes_iv'] ?></td>
							</tr>
							<tr>
								<th>Кол-во HTTPS-запросов за сегодня:</th>
								<td><?php echo $project['aes_iv'] ?></td>
							</tr>
							<tr>
								<th>Кол-во сессий за сегодня:</th>
								<td><?php echo $project['aes_iv'] ?></td>
							</tr>
							<tr>
								<th>Кол-во авторизованных пользователей за сегодня:</th>
								<td><?php echo $project['aes_iv'] ?></td>
							</tr>
						</table>
					</div>
				</div>
				<div class="pb-2 mt-4 mb-2">
					<h3>Редактирование</h3>
				</div>
				<form action="#" id="editForm" method="POST">
					<div class="form-group row">
						<div class="offset-sm-3 col-sm-9">
							<div class="checkbox">
								<label><input type="checkbox" id="module_detect" name="module_detect"> Module Detection</label>
							</div>
							<div class="checkbox">
								<label><input type="checkbox" id="memory_detect" name="memory_detect"> Memory Detection</label>
							</div>
							<div class="checkbox">
								<label><input type="checkbox" id="cleo_detect" name="cleo_detect"> CLEO Detection</label>
							</div>
							<div class="checkbox">
								<label><input type="checkbox" id="sf_detect" name="sf_detect"> SAMPFUNCS Detection</label>
							</div>
							</br></br>
							<div class="checkbox">
								<label><input type="checkbox" id="gen_new_aes" name="gen_new_aes"> Сгенерировать новый AES-ключ</label>
							</div>
						</div>
					</div>
					<div class="form-group row">
						<div class="offset-sm-3 col-sm-9">
							<button type="submit" class="btn btn-primary">Сохранить</button>
						</div>
					</div>
				</form>
				<script>
					$('#editForm').ajaxForm({ 
						url: '/admin/projects/control/ajax/<?php echo $project['id'] ?>',
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
									showSuccess(data.success);
									break;
							}
						},
						beforeSubmit: function(arr, $form, options) {
							$('button[type=submit]').prop('disabled', true);
						}
					});
					
					function sendAction(projectid, action) {
						switch(action) {
							case "block":
							{
								if(!confirm("Вы уверены в том, что хотите заблокировать проект?")) return;
								break;
							}
							case "unblock":
							{
								if(!confirm("Вы уверены в том, что хотите разблокировать проект?")) return;
								break;
							}
						}
						$.ajax({ 
							url: '/admin/projects/control/action/'+projectid+'/'+action,
							dataType: 'text',
							success: function(data) {
								console.log(data);
								data = $.parseJSON(data);
								switch(data.status) {
									case 'error':
										showError(data.error);
										$('#controlBtns button').prop('disabled', false);
										break;
									case 'success':
										showSuccess(data.success);
										setTimeout("reload()", 1500);
										break;
								}
							}
						});
					}
				</script>
<?php echo $footer ?>
