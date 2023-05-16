<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Project Settings</h2>
				</div>
				<div class="card mb-4">
					<div class="card-header">Project Info</div>
					<div class="card-body">
						<table class="table mb-0">
							<tr>
								<th>Name:</th>
								<td><?php echo $project['name'] ?></td>
							</tr>
							<tr>
								<th>Plan:</th>
								<td>
								<?php 
								switch($item['plan'])
								{
									case 0:
										echo "Minimal";
										break;
								}
								?>
								</td>
							</tr>
							<tr>
								<th>Created:</th>
								<td><?php echo $project['create_date'] ?></td>
							</tr>
							<tr>
								<th>Expired:</th>
								<td><?php echo $project['expired_date'] ?></td>
							</tr>
							<tr>
								<th>Status:</th>
								<td>
								<?php 
								switch($project['status'])
								{
									case 0:
										echo '<span class="table-danger" style="border-radius: 2px; padding: 1%;"><b>Project blocked!</b></span>';
										break;

									case 1:
										echo '<span class="table-success" style="border-radius: 2px; padding: 1%;"><b>Al ok!</b></span>';
										break;
								}
								?>
								</td>
							</tr>
						</table>
					</div>
				</div>
				<div class="card mb-4">
					<div class="card-header">Detect settings</div>
					<div class="card-body">
						<table class="table mb-0">
							<tr>
								<th>SAMPFUNCS Scripts Detection:</th>
								<td>
								<?php 
								switch($project['sf_check'])
								{
									case 0:
										echo '<span class="table-success" style="border-radius: 2px; padding: 2%;"><b>Bypass</b></span>';
										break;

									case 1:
										echo '<span class="table-warning" style="border-radius: 2px; padding: 2%;"><b>Warn</b></span>';
										break;

									case 2:
										echo '<span class="table-danger" style="border-radius: 2px; padding: 2%;"><b>Block</b></span>';
										break;
								}
								?>
								</td>
							</tr>
							<tr>
								<th>CLEO Scripts Detection:</th>
								<td>
								<?php 
								switch($project['cleo_check'])
								{
									case 0:
										echo '<span class="table-success" style="border-radius: 2px; padding: 2%;"><b>Bypass</b></span>';
										break;

									case 1:
										echo '<span class="table-warning" style="border-radius: 2px; padding: 2%;"><b>Warn</b></span>';
										break;

									case 2:
										echo '<span class="table-danger" style="border-radius: 2px; padding: 2%;"><b>Block</b></span>';
										break;
								}
								?>
								</td>
							</tr>
							<tr>
								<th>DLL/ASI Inject Detection:</th>
								<td>
								<?php 
								switch($project['module_check'])
								{
									case 0:
										echo '<span class="table-success" style="border-radius: 2px; padding: 2%;"><b>Bypass</b></span>';
										break;

									case 1:
										echo '<span class="table-warning" style="border-radius: 2px; padding: 2%;"><b>Warn</b></span>';
										break;

									case 2:
										echo '<span class="table-danger" style="border-radius: 2px; padding: 2%;"><b>Block</b></span>';
										break;
								}
								?>
								</td>
							</tr>
							<tr>
								<th>Memory Integrity Detection:</th>
								<td>
								<?php 
								switch($project['memory_check'])
								{
									case 0:
										echo '<span class="table-success" style="border-radius: 2px; padding: 2%;"><b>Bypass</b></span>';
										break;

									case 1:
										echo '<span class="table-warning" style="border-radius: 2px; padding: 2%;"><b>Warn</b></span>';
										break;

									case 2:
										echo '<span class="table-danger" style="border-radius: 2px; padding: 2%;"><b>Block</b></span>';
										break;
								}
								?>
								</td>
							</tr>
						</table>
					</div>
				</div>
<?php echo $footer ?>
