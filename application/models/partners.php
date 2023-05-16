<?php
class partnersModel extends Model {
	public function createPartner($data) {
		$sql = "INSERT INTO `partners` SET ";
		$sql .= "email = '" . $this->db->escape($data['email']) . "', ";
		$sql .= "password = '" . $this->db->escape($data['password']) . "', ";
		$sql .= "balance = '" . (float)$data['balance'] . "', ";
		$sql .= "access = '" . (int)$data['access'] . "', ";
		$sql .= "plan = '0', ";
		$sql .= "create_date = NOW()";
		error_log($sql);
		$this->db->query($sql);
		return $this->db->getLastId();
	}
	
	public function deletePartner($partnerid) {
		$sql = "DELETE FROM `partners` WHERE id = '" . (int)$partnerid . "'";
		$this->db->query($sql);
	}
	
	public function updatePartner($partnerid, $data = array()) {
		$sql = "UPDATE `partners`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " SET";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		$sql .= " WHERE `id` = '" . (int)$partnerid . "'";
		$query = $this->db->query($sql);
		return true;
	}
	
	public function getPartners($data = array(), $sort = array(), $options = array()) {
		$sql = "SELECT * FROM `partners`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " WHERE";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= " AND";
			}
		}
		
		if(!empty($sort)) {
			$count = count($sort);
			$sql .= " ORDER BY";
			foreach($sort as $key => $value) {
				$sql .= " $key " . $value;
				
				$count--;
				if($count > 0) $sql .= ",";
			}
		}
		
		if(!empty($options)) {
			if($options['start'] < 0) {
				$options['start'] = 0;
			}
			if($options['limit'] < 1) {
				$options['limit'] = 20;
			}
			$sql .= " LIMIT " . (int)$options['start'] . "," . (int)$options['limit'];
		}
		$query = $this->db->query($sql);
		return $query->rows;
	}
	
	public function getPartnerById($partnerid) {
		$sql = "SELECT * FROM `partners` WHERE `id` = '" . (int)$partnerid . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getPartnerByEmail($email) {
		$sql = "SELECT * FROM `partners` WHERE `email` = '" . $this->db->escape($email) . "' LIMIT 1";
		$query = $this->db->query($sql);
		return $query->row;
	}
	
	public function getTotalPartners($data = array()) {
		$sql = "SELECT COUNT(*) AS count FROM `partners`";
		if(!empty($data)) {
			$count = count($data);
			$sql .= " WHERE";
			foreach($data as $key => $value) {
				$sql .= " $key = '" . $this->db->escape($value) . "'";
				
				$count--;
				if($count > 0) $sql .= " AND";
			}
		}
		$query = $this->db->query($sql);
		return $query->row['count'];
	}
	
	public function upPartnerBalance($partnerid, $value) {
	  	$query = $this->db->query("UPDATE `partners` SET balance = balance+" . (float)$value . " WHERE id = '" . (int)$partnerid . "'");
	}
	
	public function downPartnerBalance($partnerid, $value) {
	  	$query = $this->db->query("UPDATE `partners` SET balance = balance-" . (float)$value . " WHERE id = '" . (int)$partnerid . "'");
	}
}
?>
