<?php
class Custom {
	private $registry;

	private $id;
	private $login;
	private $email;
	
	private $secret;
	
	/* админка */
	private $admin;
	
	/* доступ к тест серверу */
	private $tester;
	
	/* баланс доната */
	private $donate;
	
	/* 2fa */
	private $google_auth;
	
	/* премиум аккаунт */
	private $premium;
	private $premium_time;
	
	/* доступы */
	private $permission;
	
	/* ранги */
	private $arank;
	
	private $guard_method;
	
	/* стата */
	private $ucp_activity_time;
	
	/* проходил ли проверку на рп термины */
	private $verification_rp;
	
	/* инфа о бане */
	private $ban_time;
	private $ban_admin;
	private $ban_reason;
	private $ban_date;
	
	/* ивентерка */
	private $eventer;
	
	/* восстановление пароля */
	private $recovery_culldown;
	private $recovery_token;
	
	/* форумник */
	private $forum_account;
	
	/* режим стримера */
	private $streamer;
	
	/* промокод */
	private $promotion;
	private $promo;
	private $promo_hours;
	

  	public function __construct($registry) {
		$this->registry = $registry;
		if (isset($this->registry->session->data['id'])) {
			$query = $this->registry->db->query("SELECT * FROM accounts WHERE id = '" . (int)$this->registry->session->data['id'] . "'");
			
			if ($query->num_rows) {
				$this->id 					= $query->row['id'];
				$this->login 				= $query->row['login'];
				$this->email 				= $query->row['email'];
				$this->secret 				= $query->row['secret'];
				$this->admin 				= $query->row['admin'];
				$this->donate 				= $query->row['donate'];
				$this->google_auth 			= $query->row['google_auth'];
				$this->premium 				= $query->row['premium'];
				$this->premium_time 		= $query->row['premium_time'];
				$this->permission 			= $query->row['permission'];
				$this->arank 				= $query->row['arank'];
				$this->guard_method	 		= $query->row['guard_method'];
				$this->verification_rp 		= $query->row['verification_rp'];
				$this->ban_time 			= $query->row['ban_time'];
				$this->ban_admin 			= $query->row['ban_admin'];
				$this->ban_reason 			= $query->row['ban_reason'];
				$this->ban_date 			= $query->row['ban_date'];
				$this->eventer 				= $query->row['eventer'];
				$this->recovery_culldown 	= $query->row['recovery_culldown'];
				$this->recovery_token 		= $query->row['recovery_token'];
				$this->forum_account 		= $query->row['forum_account'];
				$this->streamer 			= $query->row['streamer'];
			} else {
				$this->logout();
			}
		}
  	}
		
  	public function login($login, $password) {
		$query = $this->registry->db->query("SELECT * FROM accounts WHERE login = '" . $this->registry->db->escape($login) . "'");

		if($query->num_rows) {
			$password = strtoupper(hash('whirlpool', $password));
			if($password != $query->row['password']) return false;

			$this->registry->session->data['id'] = $query->row['id'];
			
			$this->id 					= $query->row['id'];
			$this->login 				= $query->row['login'];
			$this->email 				= $query->row['email'];
			$this->secret 				= $query->row['secret'];
			$this->admin 				= $query->row['admin'];
			$this->donate 				= $query->row['donate'];
			$this->google_auth 			= $query->row['google_auth'];
			$this->premium 				= $query->row['premium'];
			$this->premium_time 		= $query->row['premium_time'];
			$this->permission 			= $query->row['permission'];
			$this->arank 				= $query->row['arank'];
			$this->guard_method	 		= $query->row['guard_method'];
			$this->verification_rp 		= $query->row['verification_rp'];
			$this->ban_time 			= $query->row['ban_time'];
			$this->ban_admin 			= $query->row['ban_admin'];
			$this->ban_reason 			= $query->row['ban_reason'];
			$this->ban_date 			= $query->row['ban_date'];
			$this->eventer 				= $query->row['eventer'];
			$this->recovery_culldown 	= $query->row['recovery_culldown'];
			$this->recovery_token 		= $query->row['recovery_token'];
			$this->forum_account 		= $query->row['forum_account'];
			$this->streamer 			= $query->row['streamer'];
	  		return true;
		} else {
	  		return false;
		}
  	}

  	public function logout() {
		unset($this->registry->session->data['id']);
	
		$this->id 					= null;
		$this->login 				= null;
		$this->email 				= null;
		$this->secret 				= null;
		$this->admin 				= 0;
		$this->donate 				= 0;
		$this->google_auth 			= null;
		$this->premium 				= 0;
		$this->premium_time 		= 0;
		$this->permission 			= 0;
		$this->arank 				= 0;
		$this->guard_method	 		= 0;
		$this->verification_rp 		= 0;
		$this->ban_time 			= 0;
		$this->ban_admin 			= null;
		$this->ban_reason 			= null;
		$this->ban_date 			= null;
		$this->eventer 				= 0;
		$this->recovery_culldown 	= null;
		$this->recovery_token 		= null;
		$this->forum_account 		= null;
		$this->streamer 			= null;
  	}
  
  	public function isLogged() {
		return $this->id;
  	}
  
  	public function getId() {
		return $this->id;
  	}
	
  	public function getLogin() {
		return $this->login;
  	}
	
	public function getEmail() {
		return $this->email;
  	}
	
	public function getSecret() {
		return $this->secret;
  	}
	
	public function getAdminLevel() {
		return $this->admin;
  	}
	
  	public function getDonate() {
		return $this->donate;
  	}
	
	public function getGoogleAuth() {
		return $this->google_auth;
  	}
	
	public function getPremium() {
		return $this->premium;
  	}
	
	public function getPremiumTime() {
		return $this->premium_time;
  	}
	
	public function getPermission() {
		return $this->permission;
  	}
	
	public function getArank() {
		return $this->arank;
  	}
	
	public function getGuardMethod() {
		return $this->guard_method;
  	}
	
	public function getVerificationRP() {
		return $this->verification_rp;
  	}
	
	public function getBanTime() {
		return $this->ban_time;
  	}
	
	public function getBanAdmin() {
		return $this->ban_admin;
  	}
	
	public function getBanDate() {
		return $this->ban_date;
  	}
	
	public function getBanReason() {
		return $this->ban_reason;
  	}
	
	public function getEventer() {
		return $this->eventer;
  	}
	
	public function getRecoveryCulldown() {
		return $this->recovery_culldown;
  	}
	
	public function getRecoveryToken() {
		return $this->recovery_token;
  	}
	
	public function getForumAccount() {
		return $this->forum_account;
  	}
	
	public function getStreamer() {
		return $this->streamer;
  	}
}
?>
