<?php
class loginController extends Controller {
	public function index() {
		$this->document->setActiveSection('account');
		$this->document->setActiveItem('login');
		
		if($this->user->isLogged()) {
			$this->response->redirect($this->config->url);
		}

		$this->getChild(array('common/loginheader', 'common/loginfooter'));
		return $this->load->view('account/login', $this->data);
	}
	
	public function ajax() {
		if($this->user->isLogged()) {  
	  		$this->data['status'] = "error";
			$this->data['error'] = "Already logged!";
			return json_encode($this->data);
		}
		
		if($this->request->server['REQUEST_METHOD'] == 'POST') {
			$errorPOST = $this->validatePOST();
			if(!$errorPOST) {
				$email = @$this->request->post['email'];
				$password = @$this->request->post['password'];
				
				if($this->user->login($email, $password)) {
					$this->data['status'] = "success";
					$this->data['success'] = "Succesfully logged";
				} else {
					$this->data['status'] = "error";
					$this->data['error'] = "Wrong E-mail or password!";
				}
			} else {
				$this->data['status'] = "error";
				$this->data['error'] = $errorPOST;
			}
			
		}else{
		$this->data['status'] = "error";
		$this->data['error'] = "Request error";
		}

		return json_encode($this->data);
	}
	
	private function validatePOST() {
	
		$this->load->library('validate');
		
		$validateLib = new validateLibrary();
		
		$result = null;
		
		$email = @$this->request->post['email'];
		$password = @$this->request->post['password'];
		
		if(!$validateLib->email($email)) {
			$result = "Type a valid E-mail!";
		}
		elseif(!$validateLib->password($password)) {
			$result = "Password must contain 6 to 32 letters with <i>,.!?_-</i>!";
		}
		return $result;
	}
}
?>
