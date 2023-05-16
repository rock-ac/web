<?php
class User {
	private $registry;

	private $partner_id;
	private $email;
	private $balance;
	private $access;
	private $subscription;

  	public function __construct($registry) {
		$this->registry = $registry;
		if (isset($this->registry->session->data['id'])) {
			$query = $this->registry->db->query("SELECT * FROM partners WHERE id = '" . (int)$this->registry->session->data['id'] . "'");
			
			if ($query->num_rows) {
				$this->partner_id = $query->row['id'];
				$this->email = $query->row['email'];
				$this->access = $query->row['access'];
				$this->balance = $query->row['balance'];
				$this->subscription = $query->row['subscription'];
			} else {
				$this->logout();
			}
		}
  	}
		
  	public function login($email, $password) {
		$query = $this->registry->db->query("SELECT * FROM partners WHERE email = '" . $this->registry->db->escape($email) . "'");

		if($query->num_rows) {
			if(!password_verify($password, $query->row['password'])) return false;

			$this->registry->session->data['id'] = $query->row['id'];
			
			$this->email = $query->row['email'];
			$this->access = $query->row['access'];
			$this->balance = $query->row['balance'];
			$this->subscription = $query->row['subscription'];
	  		return true;
		} else {
	  		return false;
		}
  	}

  	public function logout() {
		unset($this->registry->session->data['id']);
	
		$this->partner_id = null;
		$this->email = null;
		$this->balance = null;
		$this->access = 0;
		$this->subscription = 0;
  	}
  
  	public function isLogged() {
		return $this->partner_id;
  	}
  
  	public function getId() {
		return $this->partner_id;
  	}
	
  	public function getEmail() {
		return $this->email;
  	}
	
  	public function getBalance() {
		return $this->balance;
  	}
	
  	public function getAccessLevel() {
		return $this->access;
  	}
	
  	public function getSubscription() {
		return $this->subscription;
  	}
}
?>
