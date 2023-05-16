<?php
class restoreController extends Controller {
	public function index() {
		$this->document->setActiveSection('account');
		$this->document->setActiveItem('restore');
		
		if($this->user->isLogged()) {
			$this->response->redirect($this->config->url);
		}

		$this->getChild(array('common/loginheader', 'common/loginfooter'));
		return $this->load->view('account/restore/index', $this->data);
	}
	
	public function complete($restoreKey = null) {
		$this->document->setActiveSection('account');
		$this->document->setActiveItem('restore');
		
		if($this->user->isLogged()) {
			$this->response->redirect($this->config->url);
		}
        if($this->session->data['restore']['key'] != $restoreKey) {
			$this->session->data['error'] = "Wrong activation link!";
			$this->response->redirect($this->config->url . 'account/restore');
        }
		
		$this->load->model('users');
		
		// Получаем данные
		$userid = $this->session->data['restore']['userid'];
		$email = $this->session->data['restore']['email'];
		$password = bin2hex(random_bytes(4));

		// Удаляем данные из сессии
        unset($this->session->data['restore']);
		
		$this->usersModel->updateUser($userid, array('password' => password_hash($password, PASSWORD_DEFAULT)));
		
		$this->user->login($email, $password); 

		$this->data['password'] = $password;
		
		$this->getChild(array('common/loginheader', 'common/loginfooter'));
		return $this->load->view('account/restore/complete', $this->data);
	}
	
	public function ajax() {
		$this->load->checkLicense();
		if($this->user->isLogged()) {  
	  		$this->data['status'] = "error";
			$this->data['error'] = "Already logged!";
			return json_encode($this->data);
		}
		
		$this->load->library('mail');
		$this->load->model('users');
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$email = @$this->request->post['email'];
				
				// Получаем данные пользователя
				$user = $this->usersModel->getUserByEmail($email);
				
				// Генерируем ключ восстановления
                $restoreKey = bin2hex(random_bytes(16));
				
				// Сохраняем данные в сессию
				$this->session->data['restore']['userid'] = $user['user_id'];
				$this->session->data['restore']['email'] = $email;
                $this->session->data['restore']['key'] = $restoreKey;

				// Отправляем Email
				$mailLib = new mailLibrary();
				
				$mailLib->setFrom($this->config->mail_from);
				$mailLib->setSender($this->config->mail_sender);
				$mailLib->setTo($email);
				$mailLib->setSubject('Password restoring');
				
				$mailData = array();

				$mailData['firstname'] = $user['user_firstname'];
				$mailData['lastname'] = $user['user_lastname'];
				$mailData['restorelink'] = ($this->config->url . 'account/restore/complete/' . $restoreKey);
				$text = $this->load->view('mail/account/restore', $mailData);
				
				$mailLib->setText($text);
				$mailLib->send();
				
				$this->data['status'] = "success";
				$this->data['success'] = "Mail with new password are sended to your E-mail!";
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
		}

		return json_encode($this->data);
	}
	
	private function validatePOST() {
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;
		
		$email = @$this->request->post['email'];
		$captcha = @$this->request->post['captcha'];
		
		$captchahash = @$this->session->data['captcha'];
		unset($this->session->data['captcha']);
		
		if(!$validateLib->email($email)) {
			$result = "Type a valid E-mail!";
		}
		elseif($captcha != $captchahash) {
			$result = "Type a vaild CAPTCHA-code!";
		}
		return $result;
	}
}
?>
