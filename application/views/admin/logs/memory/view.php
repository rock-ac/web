<?php echo $header ?>
				<div class="pb-2 mt-4 mb-2">
					<h2>Лог изменения памяти #<?php echo $log['id']?></h2>
				</div>
				<div class="card mb-4">
					<div class="card-header">Основная информация</div>
					<div class="card-body">
						<table class="table mb-0">
							<tr>
								<th>Адрес:</th>
								<td><?php echo "0x".dechex($log['base_address']) ?></td>
                            </tr>
                            <tr>
								<th>Размер:</th>
                                <td><?php echo $log['region_size'] ?></td>
							</tr>
                            <tr>
								<th>Характеристика:</th>
                                <td>
                                <?php
                                $names = array(
                                    '1' => "Информация о перемещении была удалена из файла. Файл должен быть загружен по его предпочтительному базовому адресу. Если базовый адрес недоступен, загрузчик сообщает об ошибке. (IMAGE_FILE_RELOCS_STRIPPED)",
                                    '2' => "Файл является исполняемым. (IMAGE_FILE_EXECUTABLE_IMAGE)",
                                    '3' => "Номера строк COFF были удалены из файла. (IMAGE_FILE_LINE_NUMS_STRIPPED)",
                                    '4' => "Записи таблицы символов COFF были удалены из файла. (IMAGE_FILE_LOCAL_SYMS_STRIPPED)",
                                    '5' => "Агрессивно обрезанный рабочий набор. Этот флаг устарел. (IMAGE_FILE_AGGRESIVE_WS_TRIM)",
                                    '6' => "Приложение может обрабатывать адреса объемом более 2 ГБ. (IMAGE_FILE_LARGE_ADDRESS_AWARE)",
                                    '7' => "Байты машинных инструкций зарезервированы. Этот флаг устарел. (IMAGE_FILE_BYTES_REVERSED_LO)",
                                    '8' => "Компьютер поддерживает 32-разрядные инструкции. (IMAGE_FILE_32BIT_MACHINE)",
                                    '9' => "Отладочная информация была удалена и сохранена отдельно в другом файле. (IMAGE_FILE_DEBUG_STRIPPED)",
                                    '10' => "Если изображение находится на съемном носителе, скопируйте и запустите из файла подкачки. (IMAGE_FILE_REMOVABLE_RUN_FROM_SWAP)",
                                    '11' => "Если изображение находится в Сети, скопируйте и запустите из файла подкачки. (IMAGE_FILE_NET_RUN_FROM_SWAP)",
                                    '12' => "Системный файл. (IMAGE_FILE_SYSTEM)",
                                    '13' => "Изображение представляет собой DLL-файл. Хотя это исполняемый файл, его нельзя запустить напрямую. (IMAGE_FILE_DLL)",
                                    '14' => "Файл должен быть запущен только на однопроцессорном компьютере. (IMAGE_FILE_UP_SYSTEM_ONLY)",
                                    '15' => "Байты машинных инструкций зарезервированы. Этот флаг устарел. (IMAGE_FILE_UP_SYSTEM_ONLY)"
                                );

                                for ($i=0; $i < sizeof($names); $i++) {
                                    if (($log['characteristics'] & (1 << $i)) == 0) continue; 

                                    if($log['characteristics'] & 1 << $i)
                                    {
                                        echo $names[$i] . "</br></br>";
                                    }
                                }

                                ?></td>
							</tr>
                            <tr>
								<th>Имя секции:</th>
                                <td><?php echo $log['name'] ?></td>
							</tr>
                            <tr>
								<th>CRC Хэш:</th>
                                <td><?php echo "0x".strtoupper(dechex($log['hash'])) ?></td>
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
