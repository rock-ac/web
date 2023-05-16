<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Ban-list</h2>
				</div>
				<table class="table table-td-custom">
					<thead>
						<tr>
							<th>ID</th>
							<th>Username</th>
							<th>Date</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach($bans as $item): ?> 
						<tr class="table" onClick="redirect('/projects/bans/index/<?php echo $item['id'] ?>')">
							<td>№<?php echo $item['id'] ?></td>
							<td><?php echo $item['username'] ?></td>
							<td><?php echo $item['date'] ?></td>
						</tr>
						<?php endforeach; ?> 
						<?php if(empty($bans)): ?> 
						<tr style="background-color: rgba(0,0,0,.05)">
							<td colspan="5" class="text-center">You currently have no active bans.</td>
						<tr>
						<?php endif; ?> 
					</tbody>
				</table>
				<?php echo $pagination ?> 
<?php echo $footer ?>