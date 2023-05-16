<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Лог процесса #<?php echo $log['id']?></h2>
				</div>
				<div class="card mb-4">
					<div class="card-header">Основная информация</div>
					<div class="card-body">
						<table class="table mb-0">
							<tr>
								<th>Имя процесса:</th>
								<td><?php echo $log['title'] ?></td>
                            </tr>
                            <tr>
								<th>Путь к процессу:</th>
								<td><?php echo $log['path'] ?></td>
                            </tr>
                            <tr>
								<th>Описание:</th>
								<td><?php echo $log['description'] ?></td>
                            </tr>
                            <tr>
								<th>Видимый:</th>
								<td><?php echo $log['visible'] ? "Да, был открыт на момент детекта." : "Нет, был скрыт на момент детекта" ?></td>
                            </tr>
                            <tr>
								<th>Дескриптор окна:</th>
								<td><?php echo $log['hwnd'] ?></td>
                            </tr>
                        </table>
                    </div>
                    </div>
                    <div class="card mb-4">
					<div class="card-header">Информация о пользователе</div>
					<div class="card-body">
						<table class="table mb-0">
                            <tr>
								<th>ID Сессии пользователя:</th>
                                <td><?php echo $log['sessionID']; ?></td>
							</tr>
                            <tr>
								<th>ID пользователя:</th>
                                <td><?php echo $log['userid']; ?></td>
							</tr>
                            <tr>
								<th>Имя пользователя:</th>
                                <td><?php echo $log['username']; ?></td>
							</tr>
                            <tr>
								<th>Дата обнаружения:</th>
                                <td><?php echo $log['date']; ?></td>
							</tr>
						</table>
					</div>
				</div>
<?php echo $footer ?>
