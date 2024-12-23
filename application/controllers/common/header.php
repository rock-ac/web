<?php
class headerController extends Controller {

	public function index() {
		$this->data['title'] = $this->config->title;
		$this->data['description'] = $this->config->description;
		$this->data['keywords'] = $this->config->keywords;
		
		$this->data['activesection'] = $this->document->getActiveSection();
		$this->data['activeitem'] = $this->document->getActiveItem();
		
		if($this->user->isLogged()) {
			$this->data['logged'] = true;
			$this->data['email'] = $this->user->getEmail();
			$this->data['user_access_level'] = $this->user->getAccessLevel();
		} else {
			$this->data['logged'] = false;
		}
		
		if(isset($this->session->data['error'])) {
			$this->data['error'] = $this->session->data['error'];
			unset($this->session->data['error']);
		}
		
		if(isset($this->session->data['warning'])) {
			$this->data['warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
		}
		
		if(isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		}
	
		return $this->load->view('common/header', $this->data);
	}
}
?>
