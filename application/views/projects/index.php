<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>My projects</h2>
				</div>
				<table class="table table-td-custom">
					<thead>
						<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Plan</th>
							<th>Expire</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($projects as $item): ?> 
						<tr class="table-<?php if($item['status'] == 0){echo 'danger';}elseif($item['status'] == 1){echo 'success';}?>" style="color: white" onClick="redirect('/projects/control/index/<?php echo $item['id'] ?>')">
							<td>№<?php echo $item['id'] ?></td>
							<td><b><?php echo $item['name'] ?></b></td>
							<td>
							<?php 
							switch($item['plan'])
							{
								case 0:
									echo "Minimal";
									break;
							}
							?></td>
							<td><?php echo $item['expired_date'] ?></td>
						</tr>
						<?php endforeach; ?> 
						<?php if(empty($projects)): ?> 
						<tr style="background-color: rgba(0,0,0,.05)">
							<td colspan="5" class="text-center">You currently have no active projects.</td>
						<tr>
						<?php endif; ?> 
					</tbody>
				</table>
				<?php echo $pagination ?> 
<?php echo $footer ?>
