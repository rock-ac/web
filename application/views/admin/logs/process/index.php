<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Логи процессов</h2>
				</div>
				<table class="table table-td-custom">
					<thead>
						<tr>
							<th>ID</th>
							<th>Процесс</th>
							<th>Время</th>
                            <th>Имя пользователя</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($logs as $item): ?> 
						<tr class="table" onClick="redirect('/admin/logs/process/view/index/<?php echo $item['id']?>')">
                            <td>#<?php echo $item['id'] ?></td>
                            <td><?php echo $item['title'] ?></td>
                            <td><?php echo $item['date'] ?></td>
                            <td><?php echo $item['username'] ?></td>
						</tr>
						<?php endforeach; ?> 
						<?php if(empty($logs)): ?> 
						<tr style="background-color: rgba(0,0,0,.05)">
							<td colspan="7" class="text-center">На данный момент нет логов.</td>
						<tr>
						<?php endif; ?> 
					</tbody>
				</table>
				<?php echo $pagination ?> 
<?php echo $footer ?>
