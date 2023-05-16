<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Все проекты</h2>
				</div>
				<table class="table table-td-custom">
					<thead>
						<tr>
							<th>ID</th>
							<th>Имя</th>
							<th>IP</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($projects as $item): ?> 
						<tr class="table-<?php if($item['status'] == 0){echo 'danger';}elseif($item['status'] == 1){echo 'success';}?>" onClick="redirect('/admin/projects/control/index/<?php echo $item['id'] ?>')">
							<td>#<?php echo $item['id'] ?></td>
							<td><b><?php echo $item['name'] ?></b></td>
							<td><?php echo $item['ip'] ?>:<?php echo $item['port'] ?></td>
						</tr>
						<?php endforeach; ?> 
						<?php if(empty($projects)): ?> 
						<tr style="background-color: rgba(0,0,0,.05)">
							<td colspan="5" class="text-center">На данный момент нет проектов.</td>
						<tr>
						<?php endif; ?> 
					</tbody>
				</table>
				<?php echo $pagination ?> 
<?php echo $footer ?>
