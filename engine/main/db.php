<?php
class DB {
	private $driver;
	
	public function __construct($driver, $hostname, $username, $password, $database) {
		$class = $driver . 'Driver';
		if(is_readable('/var/www/html/engine/database/' . $driver . '.php')) {
			require_once('/var/www/html/engine/database/' . $driver . '.php');
		} else {
			exit('Ошибка: Не удалось загрузить драйвер базы данных ' . $driver . '!');
		}
		$this->driver = new $class($hostname, $username, $password, $database);
	}
		
  	public function query($sql) {
		return $this->driver->query($sql);
  	}
	
	public function escape($value) {
		return $this->driver->escape($value);
	}
	
  	public function countAffected() {
		return $this->driver->countAffected();
  	}
  	public function getLastId() {
		return $this->driver->getLastId();
  	}
  	public function getCount() {
		return $this->driver->getCount();
  	}
}
?>
