<?php
class logoutController extends Controller {
	public function index() {
		$this->document->setActiveSection('account');
		$this->document->setActiveItem('logout');
		
		if(!$this->user->isLogged()) {
			$this->response->redirect($this->config->url . 'account/login');
		}
		
		$this->user->logout();
		
		$this->response->redirect($this->config->url);
		
		return null;
	}
}
?>
