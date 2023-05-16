<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Лог SAMPFUNCS #<?php echo $log['id']?></h2>
				</div>
				<div class="card mb-4">
					<div class="card-header">Основная информация</div>
					<div class="card-body">
						<table class="table mb-0">
							<tr>
								<th>Файл:</th>
								<td><?php echo $log['filename'] ?></td>
                            </tr>
                            <tr>
								<th>Путь к файлу:</th>
                                <td><?php echo $log['path'] ?></td>
							</tr>
                            <tr>
								<th>MD5-хэш:</th>
                                <td><a href="https://www.virustotal.com/gui/file/<?php echo $log['hash']?>?nocache=1" target="_blank"><?php echo $log['hash'] ?></a></td>
							</tr>
                            <tr>
								<th>Последнее изменение файла:</th>
                                <td>
                                <?php
                                $timeChange = $log['lastChange'];
                                $timeChange = str_replace("/", ' ', $timeChange);
                                $timeChange = str_replace(":", ' в ', $timeChange);

                                $pos = strpos($timeChange, "в");
                                $timeChange = substr_replace($timeChange, ":", $pos+5, 1);

                                $ru_months = array( 'Января', 'Февраля', 'Марта', 'Апреля', 'Мая', 'Июня', 'Июля', 'Августа', 'Сентября', 'Октября', 'Ноября', 'Декабря' );
                                $en_months = array( 'January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December' );
                                for($i=0; $i<count($en_months); $i++)
                                {
                                    $pos = strpos($timeChange, $en_months[$i]);
                                    if($pos === false) {}
                                    else 
                                    {
                                        $timeChange = substr_replace($timeChange, $ru_months[$i], $pos, strlen($en_months[$i]));
                                    }
                                }

                                echo $timeChange; 
                                ?>
                                </td>
							</tr>
                            <tr>
								<th>Размер файла:</th>
                                <td>
                                <?php
                                $size = $log['size'];
                                $units = array('Байт', 'Килобайт', 'Мегабайт', 'Гигабайт', 'Терабайт'); 

                                $bytes = max($size, 0); 
                                $pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
                                $pow = min($pow, count($units) - 1); 
                                
                                $bytes /= (1 << (10 * $pow)); 

                                echo round($bytes, 2) . ' ' . $units[$pow] . ' (' . $size . ' байт)';  
                                ?>
                                </td>
							</tr>
                            <tr>
								<th>Права доступа к файлу:</th>
                                <td title="Владелец / Группа / Остальные (r - чтение w - запись x - исполнение)"><?php echo $log['permission']; ?></td>
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
